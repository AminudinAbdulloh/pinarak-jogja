"""
Pinarak Jogja - Event Recommendation REST API
=============================================
Algoritma : TF-IDF (ekstraksi fitur) + KNN / Cosine Similarity (pencarian)
Database  : MySQL (pinarak_jogja.events)
Framework : Flask
"""

from flask import Flask, jsonify, request
import json
import os
import threading

# ── Recommendation engine ──────────────────────────────────────────────────
from recommender import EventRecommender

app = Flask(__name__)

# ── CORS sederhana tanpa library eksternal ─────────────────────────────────
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

# ── Inisialisasi recommender (lazy, thread-safe) ───────────────────────────
_recommender: EventRecommender | None = None
_recommender_lock = threading.Lock()

def get_recommender() -> EventRecommender:
    global _recommender
    # Double-checked locking: cek di luar lock dulu agar tidak ada overhead
    # lock di setiap request setelah model selesai dibangun.
    if _recommender is None:
        with _recommender_lock:
            # Cek ulang di dalam lock — request kedua yang sudah menunggu
            # tidak akan memanggil fit() lagi setelah request pertama selesai.
            if _recommender is None:
                r = EventRecommender()
                r.fit()
                _recommender = r   # assign atomik setelah fit() selesai
    return _recommender


# ══════════════════════════════════════════════════════════════════════════
#  ENDPOINTS
# ══════════════════════════════════════════════════════════════════════════

# ── GET /api/recommendations/<event_id> ───────────────────────────────────
@app.route("/api/recommendations/<int:event_id>", methods=["GET"])
def get_recommendations(event_id: int):
    """
    Kembalikan top-N event yang mirip dengan event_id.

    Query params:
      n   (int)  – jumlah rekomendasi (default 3, max 10)

    Response JSON:
      {
        "success": true,
        "event_id": 5,
        "total": 3,
        "recommendations": [
          {
            "id": 12,
            "title": "...",
            "description": "...",
            "start_time": "2025-08-10T09:00:00",
            "location": "...",
            "image": "uploads/events/xxx.jpg",
            "status": "published",
            "similarity_score": 0.82
          },
          ...
        ]
      }
    """
    n = min(int(request.args.get("n", 3)), 10)

    try:
        rec = get_recommender()
        results = rec.recommend(event_id, n=n)
        return jsonify({
            "success": True,
            "event_id": event_id,
            "total": len(results),
            "recommendations": results,
        })
    except ValueError as e:
        return jsonify({"success": False, "message": str(e)}), 404
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": f"Internal error: {e}"}), 500


# ── GET /api/recommendations/refresh ──────────────────────────────────────
@app.route("/api/recommendations/refresh", methods=["POST"])
def refresh_model():
    """
    Paksa rebuild model TF-IDF dari database.
    Gunakan ini setelah ada perubahan data event (tambah / edit / hapus).
    Thread-safe: menggunakan lock agar tidak konflik dengan request rekomendasi
    yang sedang berjalan.
    """
    global _recommender
    try:
        r = EventRecommender()
        r.fit()                          # bangun model baru terlebih dahulu
        with _recommender_lock:
            _recommender = r             # lalu swap atomik di dalam lock
        return jsonify({
            "success": True,
            "message": f"Model rebuilt with {len(_recommender.events)} events.",
        })
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500


# ── GET /api/events ────────────────────────────────────────────────────────
@app.route("/api/events", methods=["GET"])
def list_events():
    """Tampilkan semua event published (untuk debugging / testing)."""
    try:
        rec = get_recommender()
        events = [
            {
                "id":         e["id"],
                "title":      e["title"],
                "location":   e["location"],
                "start_time": e["start_time"],
                "status":     e["status"],
            }
            for e in rec.events
        ]
        return jsonify({"success": True, "total": len(events), "events": events})
    except RuntimeError as e:
        return jsonify({"success": False, "message": str(e)}), 503
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500


# ── GET /api/health ────────────────────────────────────────────────────────
@app.route("/api/health", methods=["GET"])
def health():
    """Health-check endpoint."""
    loaded = _recommender is not None
    return jsonify({
        "status":       "ok",
        "model_loaded": loaded,
        "total_events": len(_recommender.events) if loaded else 0,
    })


# ══════════════════════════════════════════════════════════════════════════
if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    debug = os.environ.get("FLASK_DEBUG", "false").lower() == "true"
    print(f"🚀  Pinarak Recommendation API running on http://0.0.0.0:{port}")
    app.run(host="0.0.0.0", port=port, debug=debug)