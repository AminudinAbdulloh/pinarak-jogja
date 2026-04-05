"""
recommender.py
==============
Modul inti Machine Learning untuk rekomendasi event.

Algoritma : Content-Based Filtering
  - Representasi konten  : TF-IDF (unigram + bigram)
  - Pengukuran kemiripan : KNN (metric dan k bisa dikonfigurasi)
  - Sumber data          : MySQL (pinarak_jogja.events) ATAU dataset manual (Excel/CSV)

Alur:
  1. Ambil event dari database ATAU dari file dataset manual (Excel/CSV).
  2. Preprocessing teks (lowercase, hapus stopword Bahasa Indonesia, stemming Sastrawi).
  3. Setiap event direpresentasikan sebagai profil konten dengan pembobotan field:
       title x3 | category x5 | location x2 | description x1
  4. Fit TF-IDF vectorizer pada seluruh profil konten.
  5. Fit KNN model (metric dan k bisa dikonfigurasi dari dashboard admin).
  6. Saat query diterima, cari k tetangga terdekat -> konversi jarak -> skor kemiripan.
"""

import re
import os
from datetime import datetime
from html.parser import HTMLParser
from typing import List, Dict, Optional

import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors

try:
    from Sastrawi.Stemmer.StemmerFactory import StemmerFactory  # type: ignore
    _stemmer = StemmerFactory().create_stemmer()
    _USE_SASTRAWI = True
except ImportError:
    _stemmer = None
    _USE_SASTRAWI = False
    print("WARNING: PySastrawi tidak terinstall. pip install PySastrawi")

# ─────────────────────────────────────────────────────────────────────────────
#  KONFIGURASI DATABASE
# ─────────────────────────────────────────────────────────────────────────────
DB_CONFIG = {
    "host":     os.environ.get("DB_HOST",   "localhost"),
    "port":     int(os.environ.get("DB_PORT", 3306)),
    "user":     os.environ.get("DB_USER",   "root"),
    "password": os.environ.get("DB_PASS",   "root"),
    "database": os.environ.get("DB_NAME",   "pinarak_jogja"),
    "charset":  "utf8mb4",
}

# Kolom wajib untuk dataset manual
REQUIRED_COLUMNS = {"id", "title", "description", "location", "category"}
OPTIONAL_COLUMNS = {"start_time", "image", "status"}

# Metric yang didukung
SUPPORTED_METRICS = ["euclidean", "cosine", "manhattan", "chebyshev"]


# ─────────────────────────────────────────────────────────────────────────────
#  HTML STRIPPER
# ─────────────────────────────────────────────────────────────────────────────
class _HTMLStripper(HTMLParser):
    def __init__(self):
        super().__init__()
        self._parts: List[str] = []

    def handle_data(self, data: str):
        self._parts.append(data)

    @staticmethod
    def strip(html: str) -> str:
        s = _HTMLStripper()
        s.feed(str(html))
        return " ".join(s._parts)


# ─────────────────────────────────────────────────────────────────────────────
#  STOPWORD BAHASA INDONESIA
# ─────────────────────────────────────────────────────────────────────────────
STOPWORDS_ID = {
    "yang", "dan", "di", "ke", "dari", "dengan", "untuk", "ini", "itu",
    "atau", "juga", "ada", "tidak", "akan", "pada", "dalam", "adalah",
    "oleh", "sebagai", "sudah", "telah", "saat", "bisa", "lebih",
    "kami", "anda", "kita", "mereka", "ia", "dia", "pun", "lah", "tah", "kah",
    "se", "per", "pra", "pasca", "antar", "inter", "supra",
    "serta", "maupun", "baik", "bagi", "para", "namun", "tetapi", "tapi",
    "karena", "agar", "supaya", "maka", "sehingga", "yaitu", "yakni",
    "antara", "lain", "berbagai", "beberapa", "sejumlah", "banyak",
    "seluruh", "semua", "tersebut", "bahwa", "dapat", "saja", "pula",
    "jika", "apabila", "bila", "ketika", "seiring", "sesuai",
    "acara", "event", "kegiatan", "agenda", "rangkaian", "serangkaian",
    "gelaran", "perhelatan", "diselenggarakan", "dilaksanakan",
    "pelaksanaan", "penyelenggaraan", "penyelenggara",
    "berlangsung", "digelar", "diadakan", "menggelar", "mengadakan",
    "merupakan", "menjadi", "memiliki", "melakukan",
    "menghadirkan", "menampilkan", "mempersembahkan",
    "hadir", "ikut", "turut", "sebagian", "lainnya",
    "berbasis", "berupa", "bersama", "bertujuan",
    "bidang", "tingkat", "skala",
    "tahunan", "bulan", "minggu", "kali", "sejak", "hingga", "sampai",
    "mulai", "setiap", "selama", "waktu", "kembali",
    "selanjutnya", "kemudian", "lalu", "setelah", "sebelum",
    "senin", "selasa", "rabu", "kamis", "jumat", "sabtu",
    "januari", "februari", "maret", "april", "mei", "juni",
    "juli", "agustus", "september", "oktober", "november", "desember",
    "the", "of", "and", "in", "a", "to", "is", "for", "on", "at",
    "an", "as", "by", "it", "its", "be", "are", "was", "were",
    "has", "have", "been", "with", "this", "that", "they", "will",
    "can", "than", "also", "from", "into", "which", "more", "held",
    "new", "their", "about", "year", "annual", "city", "local",
    "national", "international", "public", "open",
    "kota", "kawasan", "tempat", "lokasi",
    "wib", "pukul", "hari", "tahun", "tanggal", "malam", "sore",
    "awal", "sama", "satu",
    "yuk", "halo", "sahabat", "sobat", "teman", "kamu",
    "siap", "seru", "ceria", "jangan", "ketinggalan", "datang",
    "siapa", "bagaimana", "lanjut", "jumpa",
    "secara", "kepada", "hanya", "langsung", "terus", "melalui",
    "penuh", "berikut", "seperti", "sekitar", "salah", "sama",
    "bentuk", "wujud", "utama", "tema", "ruang",
    "https", "jffe", "bit",
}


# ═════════════════════════════════════════════════════════════════════════════
class EventRecommender:
    """
    Content-Based Filtering untuk rekomendasi event.

    Mendukung dua sumber data:
      1. Database MySQL (default)
      2. Dataset manual dari file Excel (.xlsx/.xls) atau CSV

    Parameter KNN (k dan metric) bisa dikonfigurasi secara dinamis dari API.

    Usage:
        rec = EventRecommender(k=11, metric='euclidean')
        rec.fit()                          # dari database
        rec.fit(file_path='data.xlsx')     # dari file Excel
        results = rec.recommend(event_id=3, n=5)
    """

    def __init__(self, k: int = 11, metric: str = "euclidean"):
        if metric not in SUPPORTED_METRICS:
            raise ValueError(f"Metric '{metric}' tidak didukung. Pilih: {SUPPORTED_METRICS}")

        self.k      = k
        self.metric = metric

        self.events: List[Dict] = []
        self._tfidf_matrix      = None
        self._vectorizer: Optional[TfidfVectorizer] = None
        self._id_to_idx: Dict[int, int] = {}
        self._knn_model: Optional[NearestNeighbors] = None
        self._data_source: str    = "database"
        self._dataset_filename: str = ""
        self._built_at: str       = ""

    # ── Preprocessing ──────────────────────────────────────────────────────────
    @staticmethod
    def _preprocess(text: str) -> str:
        text = _HTMLStripper.strip(text)
        text = str(text).lower()
        text = re.sub(r"[^a-z0-9\s]", " ", text)
        text = re.sub(r"\d+", " ", text)
        tokens = [t for t in text.split() if t not in STOPWORDS_ID and len(t) > 2]
        clean = []
        for token in tokens:
            if _USE_SASTRAWI:
                stemmed = _stemmer.stem(token)
                token = stemmed if len(stemmed) >= 2 else token
            else:
                for suffix in ("kan", "an", "nya", "lah", "kah", "pun"):
                    if token.endswith(suffix) and len(token) - len(suffix) >= 3:
                        token = token[: -len(suffix)]
                        break
            clean.append(token)
        return " ".join(clean)

    # ── Content profile ────────────────────────────────────────────────────────
    def _build_content_profile(self, ev: Dict) -> str:
        title       = self._preprocess(ev.get("title", ""))
        category    = self._preprocess(ev.get("category", ""))
        location    = self._preprocess(ev.get("location", ""))
        description = self._preprocess(ev.get("description", ""))
        return (
            f"{title} {title} {title} "
            f"{category} {category} {category} {category} {category} "
            f"{location} {location} "
            f"{description}"
        )

    # ── Load dari database ─────────────────────────────────────────────────────
    def _load_db_events(self) -> List[Dict]:
        try:
            import pymysql  # type: ignore
            conn   = pymysql.connect(**DB_CONFIG)
            cursor = conn.cursor(pymysql.cursors.DictCursor)
            cursor.execute(
                """
                SELECT id, title, description, start_time, location,
                       image, status, category
                FROM   events
                WHERE  status = 'published'
                ORDER  BY start_time ASC
                """
            )
            rows = cursor.fetchall()
            cursor.close()
            conn.close()

            if not rows:
                print("WARNING: Tabel events kosong (status='published').")
                return []

            for row in rows:
                if isinstance(row.get("start_time"), datetime):
                    row["start_time"] = row["start_time"].strftime("%Y-%m-%dT%H:%M:%S")

            print(f"OK: Loaded {len(rows)} events dari database.")
            return list(rows)
        except Exception as exc:
            raise RuntimeError(f"Koneksi DB gagal: {exc}") from exc

    # ── Load dari file Excel/CSV ───────────────────────────────────────────────
    def _load_file_events(self, file_path: str) -> List[Dict]:
        """
        Load dataset dari file Excel (.xlsx/.xls) atau CSV.

        Kolom wajib : id, title, description, location, category
        Kolom opsional: start_time, image, status
        """
        try:
            import pandas as pd  # type: ignore
        except ImportError:
            raise RuntimeError("pandas tidak terinstall. Jalankan: pip install pandas openpyxl")

        if not os.path.exists(file_path):
            raise RuntimeError(f"File tidak ditemukan: {file_path}")

        ext = os.path.splitext(file_path)[1].lower()
        try:
            if ext in (".xlsx", ".xls"):
                df = pd.read_excel(file_path)
            elif ext == ".csv":
                df = pd.read_csv(file_path)
            else:
                raise RuntimeError(f"Format tidak didukung: {ext}. Gunakan .xlsx, .xls, atau .csv")
        except Exception as exc:
            raise RuntimeError(f"Gagal membaca file: {exc}") from exc

        # Normalisasi nama kolom
        df.columns = [c.strip().lower() for c in df.columns]

        # Validasi kolom wajib
        missing = REQUIRED_COLUMNS - set(df.columns)
        if missing:
            raise RuntimeError(
                f"Kolom wajib tidak ada: {missing}. "
                f"Kolom yang tersedia: {list(df.columns)}"
            )

        # Isi kolom opsional yang tidak ada
        for col in OPTIONAL_COLUMNS:
            if col not in df.columns:
                df[col] = "" if col != "status" else "published"

        rows = []
        for _, row in df.iterrows():
            import pandas as pd
            ev = {
                "id":          int(row["id"]) if pd.notna(row["id"]) else 0,
                "title":       str(row.get("title", "") or ""),
                "description": str(row.get("description", "") or ""),
                "location":    str(row.get("location", "") or ""),
                "category":    str(row.get("category", "") or ""),
                "start_time":  str(row.get("start_time", "") or ""),
                "image":       str(row.get("image", "") or ""),
                "status":      str(row.get("status", "published") or "published"),
            }
            rows.append(ev)

        if not rows:
            raise RuntimeError("File tidak berisi data event.")

        print(f"OK: Loaded {len(rows)} events dari file: {os.path.basename(file_path)}")
        return rows

    # ── Fit model ──────────────────────────────────────────────────────────────
    def fit(self, file_path: Optional[str] = None):
        """
        Bangun model TF-IDF + KNN.

        Parameters
        ----------
        file_path : str | None
            None      -> ambil data dari database MySQL
            path file -> ambil data dari file Excel/CSV
        """
        if file_path:
            self.events = self._load_file_events(file_path)
            self._data_source      = "manual"
            self._dataset_filename = os.path.basename(file_path)
        else:
            self.events = self._load_db_events()
            self._data_source      = "database"
            self._dataset_filename = ""

        if not self.events:
            raise RuntimeError("Tidak ada event untuk diproses.")

        self._id_to_idx = {int(ev["id"]): idx for idx, ev in enumerate(self.events)}

        content_profiles = [self._build_content_profile(ev) for ev in self.events]

        self._vectorizer = TfidfVectorizer(
            ngram_range=(1, 2),
            min_df=1,
            max_df=0.95,
            sublinear_tf=True,
        )
        self._tfidf_matrix = self._vectorizer.fit_transform(content_profiles)

        n_neighbors = min(self.k, len(self.events))
        self._knn_model = NearestNeighbors(
            n_neighbors=n_neighbors,
            metric=self.metric,
            algorithm="brute",
        )
        self._knn_model.fit(self._tfidf_matrix)

        self._built_at = datetime.now().strftime("%Y-%m-%dT%H:%M:%S")

        print(
            f"Model siap | sumber={self._data_source} | "
            f"events={self._tfidf_matrix.shape[0]} | "
            f"vocab={self._tfidf_matrix.shape[1]} | "
            f"KNN {self.metric} k={n_neighbors}"
        )

    # ── Rekomendasi ────────────────────────────────────────────────────────────
    def recommend(self, event_id: int, n: int = 3) -> List[Dict]:
        if self._tfidf_matrix is None or self._knn_model is None:
            raise RuntimeError("Model belum di-fit. Panggil fit() terlebih dahulu.")

        if event_id not in self._id_to_idx:
            raise ValueError(f"Event id={event_id} tidak ditemukan.")

        target_idx = self._id_to_idx[event_id]
        target_vec = self._tfidf_matrix[target_idx]

        k = min(n + 1, len(self.events))
        distances, indices = self._knn_model.kneighbors(target_vec, n_neighbors=k)

        distances = distances.flatten()
        indices   = indices.flatten()

        results = []
        for dist, idx in zip(distances, indices):
            if int(idx) == target_idx:
                continue
            score = 1.0 / (1.0 + float(dist))
            ev = dict(self.events[int(idx)])
            ev["similarity_score"] = round(score, 4)
            results.append(ev)
            if len(results) >= n:
                break

        return results

    # ── Detail rekomendasi (untuk preview admin) ───────────────────────────────
    def recommend_detail(self, event_id: int, n: int = 5) -> Dict:
        """
        Kembalikan rekomendasi beserta detail proses untuk preview admin.
        Berisi:
          - event target
          - profil konten (tokens hasil preprocessing)
          - vektor TF-IDF (top terms)
          - jarak & skor ke setiap tetangga
          - hasil rekomendasi final
        """
        if self._tfidf_matrix is None or self._knn_model is None:
            raise RuntimeError("Model belum di-fit.")

        if event_id not in self._id_to_idx:
            raise ValueError(f"Event id={event_id} tidak ditemukan.")

        target_idx     = self._id_to_idx[event_id]
        target_event   = dict(self.events[target_idx])
        content_profile = self._build_content_profile(target_event)

        # Top TF-IDF terms untuk event target
        target_vec      = self._tfidf_matrix[target_idx]
        feature_names   = self._vectorizer.get_feature_names_out()
        tfidf_scores    = target_vec.toarray().flatten()
        top_indices     = np.argsort(tfidf_scores)[::-1][:15]
        top_terms       = [
            {"term": feature_names[i], "score": round(float(tfidf_scores[i]), 4)}
            for i in top_indices if tfidf_scores[i] > 0
        ]

        # KNN distances ke semua tetangga
        k = min(n + 1, len(self.events))
        distances, indices = self._knn_model.kneighbors(target_vec, n_neighbors=k)
        distances = distances.flatten()
        indices   = indices.flatten()

        neighbors = []
        results   = []
        for dist, idx in zip(distances, indices):
            score = 1.0 / (1.0 + float(dist))
            ev    = dict(self.events[int(idx)])
            neighbor_info = {
                "id":               ev["id"],
                "title":            ev["title"],
                "category":         ev.get("category", ""),
                "location":         ev.get("location", ""),
                "distance":         round(float(dist), 6),
                "similarity_score": round(score, 4),
                "is_target":        int(idx) == target_idx,
            }
            neighbors.append(neighbor_info)
            if int(idx) != target_idx:
                ev["similarity_score"] = round(score, 4)
                results.append(ev)
                if len(results) >= n:
                    break

        return {
            "target_event":    target_event,
            "content_profile": content_profile,
            "top_tfidf_terms": top_terms,
            "knn_neighbors":   neighbors,
            "recommendations": results,
            "model_info": {
                "metric":       self.metric,
                "k":            self.k,
                "data_source":  self._data_source,
                "total_events": len(self.events),
                "vocab_size":   int(self._tfidf_matrix.shape[1]),
            },
        }

    # ── Info model ────────────────────────────────────────────────────────────
    def get_info(self) -> Dict:
        return {
            "loaded":           self._tfidf_matrix is not None,
            "data_source":      self._data_source,
            "dataset_filename": self._dataset_filename,
            "total_events":     len(self.events),
            "vocab_size":       int(self._tfidf_matrix.shape[1]) if self._tfidf_matrix is not None else 0,
            "knn_k":            self.k,
            "knn_metric":       self.metric,
            "built_at":         self._built_at,
            "supported_metrics": SUPPORTED_METRICS,
        }