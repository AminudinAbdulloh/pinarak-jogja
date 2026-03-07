"""
recommender.py
==============
Modul inti Machine Learning untuk rekomendasi event.

Algoritma : Content-Based Filtering
  - Representasi konten  : TF-IDF (unigram + bigram)
  - Pengukuran kemiripan : Cosine Similarity antar vektor TF-IDF
  - Sumber data          : MySQL (pinarak_jogja.events) — hanya status 'published'

Alur:
  1. Ambil seluruh event published dari MySQL.
  2. Preprocessing teks (lowercase, hapus stopword Bahasa Indonesia, stemming Sastrawi).
  3. Setiap event direpresentasikan sebagai dokumen teks dengan pembobotan field:
       title ×3 | category ×5 | location ×2 | description ×1
  4. Fit TF-IDF vectorizer pada seluruh dokumen event DB.
  5. Saat query diterima, hitung Cosine Similarity antara event target
     dengan semua event lainnya → ambil top-N dengan skor tertinggi.
"""

import re
import os
from datetime import datetime
from html.parser import HTMLParser
from typing import List, Dict, Optional

import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

try:
    from Sastrawi.Stemmer.StemmerFactory import StemmerFactory  # type: ignore
    _stemmer = StemmerFactory().create_stemmer()
    _USE_SASTRAWI = True
except ImportError:
    _stemmer = None
    _USE_SASTRAWI = False
    print("⚠️  PySastrawi tidak terinstall. Jalankan: pip install PySastrawi\n"
          "    Fallback ke stemming berbasis aturan sufiks (kurang akurat).")

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


# ─────────────────────────────────────────────────────────────────────────────
#  HTML STRIPPER
# ─────────────────────────────────────────────────────────────────────────────
class _HTMLStripper(HTMLParser):
    """Parser sederhana untuk menghapus semua HTML tags dari teks."""

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
    # ── Kata Fungsi Bahasa Indonesia ──────────────────────────────────────────
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

    # ── Kata Umum Event / Penyelenggaraan (zero discriminative value) ─────────
    "acara", "event", "kegiatan", "agenda", "rangkaian", "serangkaian",
    "gelaran", "perhelatan", "diselenggarakan", "dilaksanakan",
    "pelaksanaan", "penyelenggaraan", "penyelenggara",
    "berlangsung", "digelar", "diadakan", "menggelar", "mengadakan",
    "merupakan", "menjadi", "memiliki", "melakukan",
    "menghadirkan", "menampilkan", "mempersembahkan",
    "hadir", "ikut", "turut", "sebagian", "lainnya",
    "berbasis", "berupa", "bersama", "bertujuan",
    "bidang", "tingkat", "skala",

    # ── Kata Waktu Generik ────────────────────────────────────────────────────
    "tahunan", "bulan", "minggu", "kali", "sejak", "hingga", "sampai",
    "mulai", "setiap", "selama", "waktu", "kembali",
    "selanjutnya", "kemudian", "lalu", "setelah", "sebelum",
    "senin", "selasa", "rabu", "kamis", "jumat", "sabtu",
    "januari", "februari", "maret", "april", "mei", "juni",
    "juli", "agustus", "september", "oktober", "november", "desember",

    # ── Kata Umum Bahasa Inggris ──────────────────────────────────────────────
    "the", "of", "and", "in", "a", "to", "is", "for", "on", "at",
    "an", "as", "by", "it", "its", "be", "are", "was", "were",
    "has", "have", "been", "with", "this", "that", "they", "will",
    "can", "than", "also", "from", "into", "which", "more", "held",
    "new", "their", "about", "year", "annual", "city", "local",
    "national", "international", "public", "open",

    # ── Lokasi generik ────────────────────────────────────────────────────────
    "kota", "kawasan", "tempat", "lokasi",

    # ── Kata waktu/jadwal non-informatif ──────────────────────────────────────
    "wib", "pukul", "hari", "tahun", "tanggal", "malam", "sore",
    "awal", "sama", "satu",

    # ── Ajakan/basa-basi promosi (zero semantic value) ────────────────────────
    "yuk", "halo", "sahabat", "sobat", "teman", "kamu",
    "siap", "seru", "ceria", "jangan", "ketinggalan", "datang",
    "siapa", "bagaimana", "lanjut", "jumpa",

    # ── Kata generik deskriptif ───────────────────────────────────────────────
    "secara", "kepada", "hanya", "langsung", "terus", "melalui",
    "penuh", "berikut", "seperti", "sekitar", "salah", "sama",
    "bentuk", "wujud", "utama", "tema", "ruang",

    # ── URL / noise teknis ────────────────────────────────────────────────────
    "https", "jffe", "bit",
}


# ═════════════════════════════════════════════════════════════════════════════
class EventRecommender:
    """
    Content-Based Filtering untuk rekomendasi event.

    Setiap event direpresentasikan sebagai profil konten (TF-IDF vector)
    yang dibangun dari: title, category, location, dan description.
    Kemiripan antar event diukur menggunakan Cosine Similarity.

    Usage:
        rec = EventRecommender()
        rec.fit()
        results = rec.recommend(event_id=3, n=5)
    """

    def __init__(self):
        self.events: List[Dict] = []
        self._tfidf_matrix = None
        self._vectorizer: Optional[TfidfVectorizer] = None
        self._id_to_idx: Dict[int, int] = {}

    # ── Preprocessing teks ────────────────────────────────────────────────────
    @staticmethod
    def _preprocess(text: str) -> str:
        """
        Bersihkan dan normalisasi teks:
          1. Strip HTML tags
          2. Lowercase
          3. Hapus karakter non-alfanumerik & angka
          4. Hapus stopword
          5. Stemming menggunakan Sastrawi (Algoritma ECS Enhanced Confix Stripping)
             → menangani prefiks (me-, ber-, di-, ter-, ...) DAN sufiks (-kan, -an, -i, ...)
             Contoh: "berlari" → "lari" ✓  (bukan "berlar" seperti naive suffix stripping)
             Fallback ke rule-based sufiks jika PySastrawi tidak terinstall.
        """
        text = _HTMLStripper.strip(text)
        text = str(text).lower()
        text = re.sub(r"[^a-z0-9\s]", " ", text)
        text = re.sub(r"\d+", " ", text)
        tokens = [t for t in text.split() if t not in STOPWORDS_ID and len(t) > 2]

        clean = []
        for token in tokens:
            if _USE_SASTRAWI:
                # Sastrawi: stemming morfologis penuh Bahasa Indonesia
                stemmed = _stemmer.stem(token)
                # Hasil stemming kadang jadi terlalu pendek (< 2 char) → pakai token asli
                token = stemmed if len(stemmed) >= 2 else token
            else:
                # Fallback: hapus sufiks umum saja (akurasi rendah)
                for suffix in ("kan", "an", "nya", "lah", "kah", "pun"):
                    if token.endswith(suffix) and len(token) - len(suffix) >= 3:
                        token = token[: -len(suffix)]
                        break
            clean.append(token)

        return " ".join(clean)

    # ── Bangun profil konten event (content-based representation) ─────────────
    def _build_content_profile(self, ev: Dict) -> str:
        """
        Gabungkan field event menjadi satu dokumen teks dengan pembobotan:
          - title    × 3  (sangat representatif)
          - category × 5  (fitur utama content-based filtering)
          - location × 2  (relevan untuk konteks lokal)
          - description × 1
        """
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

    # ── Load event dari database ───────────────────────────────────────────────
    def _load_db_events(self) -> List[Dict]:
        """
        Ambil semua event dengan status 'published' dari MySQL.
        Ini adalah satu-satunya sumber data — tidak ada file eksternal.
        """
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
                print("⚠️  Tabel events kosong (status='published').")
                return []

            # Serialisasi datetime → string ISO 8601
            for row in rows:
                if isinstance(row.get("start_time"), datetime):
                    row["start_time"] = row["start_time"].strftime("%Y-%m-%dT%H:%M:%S")

            print(f"✅  Loaded {len(rows)} events dari database.")
            return list(rows)

        except Exception as exc:
            raise RuntimeError(f"Koneksi DB gagal: {exc}") from exc

    # ── Fit model content-based filtering ─────────────────────────────────────
    def fit(self):
        """
        Bangun model content-based filtering:
          1. Muat event dari database (satu-satunya sumber data).
          2. Buat profil konten untuk setiap event.
          3. Fit TF-IDF vectorizer pada seluruh profil konten.
          4. Transform semua profil → TF-IDF matrix untuk pencarian kemiripan.
        """
        self.events = self._load_db_events()

        if not self.events:
            raise RuntimeError("Tidak ada event published di database.")

        # Indeks id → posisi array
        self._id_to_idx = {int(ev["id"]): idx for idx, ev in enumerate(self.events)}

        # Bangun profil konten untuk setiap event
        content_profiles = [self._build_content_profile(ev) for ev in self.events]

        # TF-IDF vectorizer:
        #   - ngram (1,2) : tangkap frasa dua kata (mis. "wayang kulit", "jazz festival")
        #   - min_df=1    : semua term dipakai (data terbatas)
        #   - max_df=0.95 : buang term yang muncul di >95% dokumen (terlalu umum)
        #   - sublinear_tf: log-scaling TF agar term dominan tidak terlalu kuat
        self._vectorizer = TfidfVectorizer(
            ngram_range=(1, 2),
            min_df=1,
            max_df=0.95,
            sublinear_tf=True,
        )

        self._tfidf_matrix = self._vectorizer.fit_transform(content_profiles)

        print(
            f"📐  Content-Based Filtering siap.\n"
            f"    Events     : {self._tfidf_matrix.shape[0]}\n"
            f"    Fitur TF-IDF: {self._tfidf_matrix.shape[1]} (vocabulary size)"
        )

    # ── Rekomendasi ────────────────────────────────────────────────────────────
    def recommend(self, event_id: int, n: int = 3) -> List[Dict]:
        """
        Kembalikan top-N event yang paling mirip secara konten dengan event_id.

        Langkah:
          1. Ambil vektor TF-IDF event target.
          2. Hitung Cosine Similarity terhadap semua event lain.
          3. Urutkan secara menurun, ambil top-N (kecualikan event itu sendiri).

        Parameters
        ----------
        event_id : int  – ID event target (harus ada di DB)
        n        : int  – jumlah rekomendasi yang dikembalikan

        Returns
        -------
        List[Dict] – event dengan tambahan field 'similarity_score' (0.0–1.0)
        """
        if self._tfidf_matrix is None:
            raise RuntimeError("Model belum di-fit. Panggil fit() terlebih dahulu.")

        if event_id not in self._id_to_idx:
            raise ValueError(f"Event dengan id={event_id} tidak ditemukan di database.")

        target_idx = self._id_to_idx[event_id]
        target_vec = self._tfidf_matrix[target_idx]  # vektor profil konten target

        # Hitung cosine similarity: skor mendekati 1.0 → sangat mirip
        sim_scores = cosine_similarity(target_vec, self._tfidf_matrix).flatten()
        sim_scores[target_idx] = -1  # exclude event itu sendiri

        top_indices = np.argsort(sim_scores)[::-1][:n]

        results = []
        for idx in top_indices:
            score = float(sim_scores[idx])
            if score < 0:
                continue
            ev = dict(self.events[idx])
            ev["similarity_score"] = round(score, 4)
            results.append(ev)

        return results