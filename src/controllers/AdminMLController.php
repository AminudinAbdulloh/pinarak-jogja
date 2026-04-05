<?php

class AdminMLController extends BaseController {

    private $recoApiBase = 'http://127.0.0.1:5000';

    public function __construct() {
        AuthMiddleware::checkAuth();
    }

    // ── Halaman utama ML Dashboard ─────────────────────────────────────────────
    public function index() {
        $success_message = '';
        $error_message   = '';

        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }

        // Ambil data dari Flask API
        $health   = $this->apiGet('/api/health');
        $config   = $this->apiGet('/api/ml/config');
        $datasets = $this->apiGet('/api/ml/dataset/list');
        $events   = $this->apiGet('/api/events');

        $data = [
            'title'           => 'Dashboard - ML Control',
            'health'          => $health,
            'config'          => $config,
            'datasets'        => $datasets,
            'events'          => $events,
            'success_message' => $success_message,
            'error_message'   => $error_message,
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/ml/index', $data);
        $this->view('templates/admin/footer');
    }

    // ── Halaman preview rekomendasi ────────────────────────────────────────────
    public function preview($event_id = null) {
        if (!$event_id) {
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        $detail = $this->apiGet('/api/recommendations/' . (int)$event_id . '/detail?n=5');
        $events = $this->apiGet('/api/events');

        $data = [
            'title'    => 'Dashboard - Preview Rekomendasi',
            'detail'   => $detail,
            'event_id' => (int)$event_id,
            'events'   => $events,
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/ml/preview', $data);
        $this->view('templates/admin/footer');
    }

    // ── Update konfigurasi ML ──────────────────────────────────────────────────
    public function update_config() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        $payload = [
            'knn_k'            => (int)($_POST['knn_k'] ?? 11),
            'knn_metric'       => $_POST['knn_metric'] ?? 'euclidean',
            'n_recommendations'=> (int)($_POST['n_recommendations'] ?? 3),
            'data_source'      => $_POST['data_source'] ?? 'database',
            'dataset_file'     => $_POST['dataset_file'] ?? '',
        ];

        $result = $this->apiPost('/api/ml/config', $payload);

        if (!empty($result['success'])) {
            $_SESSION['success_message'] = 'Konfigurasi ML berhasil diperbarui dan model di-rebuild.';
        } else {
            $msg = isset($result['errors'])
                ? implode(', ', $result['errors'])
                : ($result['message'] ?? 'Gagal memperbarui konfigurasi.');
            $_SESSION['error_message'] = 'Error: ' . $msg;
        }

        header('Location: ' . BASEURL . '/admin/ml');
        exit;
    }

    // ── Manual refresh model ───────────────────────────────────────────────────
    public function refresh_model() {
        $result = $this->apiPost('/api/recommendations/refresh', []);

        if (!empty($result['success'])) {
            $_SESSION['success_message'] = 'Model berhasil di-rebuild: ' . ($result['message'] ?? '');
        } else {
            $_SESSION['error_message'] = 'Gagal rebuild model: ' . ($result['message'] ?? 'Unknown error');
        }

        header('Location: ' . BASEURL . '/admin/ml');
        exit;
    }

    // ── Upload dataset manual ──────────────────────────────────────────────────
    public function upload_dataset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        if (!isset($_FILES['dataset_file']) || $_FILES['dataset_file']['error'] !== 0) {
            $_SESSION['error_message'] = 'Gagal mengupload file. Pastikan file sudah dipilih.';
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        $file  = $_FILES['dataset_file'];
        $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $valid = ['xlsx', 'xls', 'csv'];

        if (!in_array($ext, $valid)) {
            $_SESSION['error_message'] = 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv';
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        // Kirim ke Flask API via cURL multipart
        $result = $this->apiUploadFile('/api/ml/dataset/upload', $file['tmp_name'], $file['name']);

        if (!empty($result['success'])) {
            $_SESSION['success_message'] =
                "File '{$result['filename']}' berhasil diupload. " .
                "{$result['total_rows']} baris data ditemukan.";
        } else {
            $_SESSION['error_message'] = 'Upload gagal: ' . ($result['message'] ?? 'Unknown error');
        }

        header('Location: ' . BASEURL . '/admin/ml');
        exit;
    }

    // ── Hapus dataset ──────────────────────────────────────────────────────────
    public function delete_dataset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        $filename = $_POST['filename'] ?? '';
        if (!$filename) {
            $_SESSION['error_message'] = 'Nama file tidak valid.';
            header('Location: ' . BASEURL . '/admin/ml');
            exit;
        }

        $result = $this->apiPost('/api/ml/dataset/delete', ['filename' => $filename]);

        if (!empty($result['success'])) {
            $_SESSION['success_message'] = "File '{$filename}' berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus file: ' . ($result['message'] ?? '');
        }

        header('Location: ' . BASEURL . '/admin/ml');
        exit;
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  HELPER — cURL
    // ══════════════════════════════════════════════════════════════════════════

    private function apiGet(string $path): array {
        if (!function_exists('curl_init')) return [];
        $url = rtrim($this->recoApiBase, '/') . $path;
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw === false || $code === 0) return ['error' => 'API tidak dapat dijangkau'];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function apiPost(string $path, array $payload): array {
        if (!function_exists('curl_init')) return [];
        $url = rtrim($this->recoApiBase, '/') . $path;
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        if ($raw === false) return ['success' => false, 'message' => 'API tidak dapat dijangkau'];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : ['success' => false];
    }

    private function apiUploadFile(string $path, string $tmpPath, string $filename): array {
        if (!function_exists('curl_init')) return ['success' => false, 'message' => 'cURL tidak tersedia'];
        $url = rtrim($this->recoApiBase, '/') . $path;
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'file' => new CURLFile($tmpPath, 'application/octet-stream', $filename),
            ],
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        if ($raw === false) return ['success' => false, 'message' => 'API tidak dapat dijangkau'];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : ['success' => false];
    }
}