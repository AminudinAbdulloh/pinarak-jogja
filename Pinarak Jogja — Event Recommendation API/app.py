"""
Pinarak Jogja - Event Recommendation REST API
=============================================
Algoritma : TF-IDF (ekstraksi fitur) + KNN (pencarian)
Database  : MySQL (pinarak_jogja.events)
Framework : Flask
"""

from flask import Flask, jsonify, request
import os
import threading

from recommender import EventRecommender

app = Flask(__name__)

# ── CORS ───────────────────────────────────────────────────────────────────────
@app.after_request
def add_cors_headers(response):
    response.headers["Access-Control-Allow-Origin"]  = "*"
    response.headers["Access-Control-Allow-Headers"] = "Content-Type, Authorization"
    response.headers["Access-Control-Allow-Methods"] = "GET, POST, OPTIONS"
    return response

@app.route("/", methods=["OPTIONS"])
@app.route("/<path:path>", methods=["OPTIONS"])
def options_handler(path=""):
    return jsonify({}), 200

# ── Folder untuk dataset manual ────────────────────────────────────────────────
UPLOAD_FOLDER = os.path.join(os.path.dirname(__file__), "datasets")
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# ── State global recommender ───────────────────────────────────────────────────
_recommender: EventRecommender | None = None
_recommender_lock = threading.Lock()

# Config default (bisa di-override via API)
_config = {
    "knn_k":          11,
    "n_recommendations": 3,
    "data_source":    "database",   # "database" | "manual"
    "dataset_file":   "",           # nama file di UPLOAD_FOLDER
}


def get_recommender() -> EventRecommender:
    global _recommender
    if _recommender is None:
        with _recommender_lock:
            if _recommender is None:
                _build_recommender()
    return _recommender


def _build_recommender():
    """Bangun recommender baru berdasarkan _config saat ini. Harus dipanggil di dalam lock."""
    global _recommender
    r = EventRecommender(k=_config["knn_k"])
    file_path = None
    if _config["data_source"] == "manual" and _config["dataset_file"]:
        file_path = os.path.join(UPLOAD_FOLDER, _config["dataset_file"])

    r.fit(file_path=file_path)
    _recommender = r


# ══════════════════════════════════════════════════════════════════════════════
#  ENDPOINTS — REKOMENDASI
# ══════════════════════════════════════════════════════════════════════════════

@app.route("/api/recommendations/<int:event_id>", methods=["GET"])
def get_recommendations(event_id: int):
    """
    Kembalikan top-N event yang mirip dengan event_id.
    Query params: n (int, default dari config, max 10)
    """
    n = int(request.args.get("n", _config["n_recommendations"]))
    n = min(max(n, 1), 10)

    try:
        rec     = get_recommender()
        results = rec.recommend(event_id, n=n)
        return jsonify({
            "success":         True,
            "event_id":        event_id,
            "total":           len(results),
            "recommendations": results,
        })
    except ValueError as e:
        return jsonify({"success": False, "message": str(e)}), 404
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": f"Internal error: {e}"}), 500


@app.route("/api/recommendations/<int:event_id>/detail", methods=["GET"])
def get_recommendation_detail(event_id: int):
    """
    Kembalikan detail proses rekomendasi untuk event_id (digunakan oleh panel admin).
    Berisi: content profile, top TF-IDF terms, KNN neighbors, hasil final.
    Query params: n (int, default 5)
    """
    n = int(request.args.get("n", 5))
    n = min(max(n, 1), 10)

    try:
        rec    = get_recommender()
        detail = rec.recommend_detail(event_id, n=n)
        return jsonify({"success": True, **detail})
    except ValueError as e:
        return jsonify({"success": False, "message": str(e)}), 404
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": f"Internal error: {e}"}), 500


# ══════════════════════════════════════════════════════════════════════════════
#  ENDPOINTS — KONTROL MODEL (ADMIN)
# ══════════════════════════════════════════════════════════════════════════════

@app.route("/api/recommendations/refresh", methods=["POST"])
def refresh_model():
    """
    Rebuild model dari sumber data saat ini.
    Dipanggil otomatis oleh PHP setelah create/update/delete event.
    """
    global _recommender
    try:
        with _recommender_lock:
            _build_recommender()
        return jsonify({
            "success": True,
            "message": f"Model rebuilt. {len(_recommender.events)} events.",
        })
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500


@app.route("/api/ml/config", methods=["GET"])
def get_config():
    """Ambil konfigurasi ML saat ini."""
    return jsonify({
        "success": True,
        "config":  _config,
    })


@app.route("/api/ml/config", methods=["POST"])
def update_config():
    """
    Update parameter ML dan rebuild model.
    Body JSON:
      {
        "knn_k":             int (1-50),
        "knn_metric":        string,
        "n_recommendations": int (1-10),
        "data_source":       "database" | "manual",
        "dataset_file":      string (nama file di folder datasets/)
      }
    Semua field opsional — hanya field yang dikirim yang akan diubah.
    """
    global _recommender, _config
    body = request.get_json(silent=True) or {}

    errors = []

    if "knn_k" in body:
        val = body["knn_k"]
        if not isinstance(val, int) or not (1 <= val <= 50):
            errors.append("knn_k harus integer antara 1-50.")
        else:
            _config["knn_k"] = val

    if "n_recommendations" in body:
        val = body["n_recommendations"]
        if not isinstance(val, int) or not (1 <= val <= 10):
            errors.append("n_recommendations harus integer antara 1-10.")
        else:
            _config["n_recommendations"] = val

    if "data_source" in body:
        val = body["data_source"]
        if val not in ("database", "manual"):
            errors.append("data_source harus 'database' atau 'manual'.")
        else:
            _config["data_source"] = val

    if "dataset_file" in body:
        _config["dataset_file"] = str(body["dataset_file"])

    if errors:
        return jsonify({"success": False, "errors": errors}), 400

    # Rebuild model dengan config baru
    try:
        with _recommender_lock:
            _build_recommender()
        return jsonify({
            "success": True,
            "message": "Konfigurasi diperbarui dan model di-rebuild.",
            "config":  _config,
            "model_info": _recommender.get_info(),
        })
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500


@app.route("/api/ml/dataset/upload", methods=["POST"])
def upload_dataset():
    """
    Upload file dataset manual (Excel/CSV).
    Form-data: file = <file>
    Setelah upload, file tersimpan di folder datasets/ dan bisa diaktifkan via /api/ml/config.
    """
    if "file" not in request.files:
        return jsonify({"success": False, "message": "Field 'file' tidak ada."}), 400

    f = request.files["file"]
    if not f.filename:
        return jsonify({"success": False, "message": "Nama file kosong."}), 400

    ext = os.path.splitext(f.filename)[1].lower()
    if ext not in (".xlsx", ".xls", ".csv"):
        return jsonify({
            "success": False,
            "message": "Format tidak didukung. Gunakan .xlsx, .xls, atau .csv"
        }), 400

    save_path = os.path.join(UPLOAD_FOLDER, f.filename)
    f.save(save_path)

    # Validasi file dengan mencoba load tanpa fit
    try:
        r = EventRecommender()
        rows = r._load_file_events(save_path)
        return jsonify({
            "success":       True,
            "message":       f"File '{f.filename}' berhasil diupload.",
            "filename":      f.filename,
            "total_rows":    len(rows),
            "preview":       rows[:3],  # 3 baris pertama sebagai preview
        })
    except RuntimeError as e:
        os.remove(save_path)
        return jsonify({"success": False, "message": str(e)}), 400


@app.route("/api/ml/dataset/list", methods=["GET"])
def list_datasets():
    """Daftar semua file dataset yang tersedia di folder datasets/."""
    files = []
    for fname in os.listdir(UPLOAD_FOLDER):
        if fname.lower().endswith((".xlsx", ".xls", ".csv")):
            fpath = os.path.join(UPLOAD_FOLDER, fname)
            files.append({
                "filename": fname,
                "size_kb":  round(os.path.getsize(fpath) / 1024, 1),
                "active":   fname == _config.get("dataset_file"),
            })
    return jsonify({"success": True, "datasets": files})


@app.route("/api/ml/dataset/delete", methods=["POST"])
def delete_dataset():
    """Hapus file dataset. Body JSON: {"filename": "..."}"""
    body     = request.get_json(silent=True) or {}
    filename = body.get("filename", "")

    if not filename:
        return jsonify({"success": False, "message": "filename diperlukan."}), 400

    fpath = os.path.join(UPLOAD_FOLDER, filename)
    if not os.path.exists(fpath):
        return jsonify({"success": False, "message": "File tidak ditemukan."}), 404

    os.remove(fpath)

    # Jika file yang dihapus adalah file aktif, switch ke database
    if _config.get("dataset_file") == filename:
        _config["dataset_file"] = ""
        _config["data_source"]  = "database"

    return jsonify({"success": True, "message": f"File '{filename}' dihapus."})


@app.route("/api/ml/dataset/template", methods=["GET"])
def download_template():
    """Buat dan kirim file template Excel untuk dataset manual."""
    try:
        import pandas as pd  # type: ignore
        from flask import send_file
        import io

        df = pd.DataFrame([
            {
                "id":          1,
                "title":       "Festival Kesenian Yogyakarta",
                "description": "Festival tahunan yang menampilkan berbagai kesenian tradisional Yogyakarta.",
                "location":    "Taman Budaya Yogyakarta",
                "category":    "Seni Budaya",
                "start_time":  "2025-08-10 09:00:00",
                "image":       "uploads/events/example.jpg",
                "status":      "published",
            },
            {
                "id":          2,
                "title":       "Jogja Jazz Festival",
                "description": "Festival jazz internasional dengan penampilan musisi lokal dan mancanegara.",
                "location":    "Prambanan",
                "category":    "Musik",
                "start_time":  "2025-09-15 18:00:00",
                "image":       "uploads/events/example2.jpg",
                "status":      "published",
            },
        ])

        buf = io.BytesIO()
        with pd.ExcelWriter(buf, engine="openpyxl") as writer:
            df.to_excel(writer, index=False, sheet_name="Events")
        buf.seek(0)

        return send_file(
            buf,
            mimetype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            as_attachment=True,
            download_name="template_dataset_events.xlsx",
        )
    except ImportError:
        return jsonify({
            "success": False,
            "message": "pandas/openpyxl tidak terinstall. pip install pandas openpyxl"
        }), 500


# ══════════════════════════════════════════════════════════════════════════════
#  ENDPOINTS — UTILITY
# ══════════════════════════════════════════════════════════════════════════════

@app.route("/api/events", methods=["GET"])
def list_events():
    """Tampilkan semua event yang dimuat ke model (untuk debugging / preview admin)."""
    try:
        rec = get_recommender()
        events = [
            {
                "id":         e["id"],
                "title":      e["title"],
                "category":   e.get("category", ""),
                "location":   e["location"],
                "start_time": e["start_time"],
                "status":     e.get("status", ""),
            }
            for e in rec.events
        ]
        return jsonify({"success": True, "total": len(events), "events": events})
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500


@app.route("/api/health", methods=["GET"])
def health():
    """Health-check endpoint."""
    loaded = _recommender is not None
    info   = _recommender.get_info() if loaded else {}
    return jsonify({
        "status":       "ok",
        "model_loaded": loaded,
        **info,
    })


# ══════════════════════════════════════════════════════════════════════════════
if __name__ == "__main__":
    port  = int(os.environ.get("PORT", 5000))
    debug = os.environ.get("FLASK_DEBUG", "false").lower() == "true"
    print(f"Pinarak Recommendation API running on http://0.0.0.0:{port}")
    app.run(host="0.0.0.0", port=port, debug=debug)