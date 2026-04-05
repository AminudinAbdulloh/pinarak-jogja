<div class="content-section" id="ml-dashboard">
    <div class="page-header">
        <h1><i class="fas fa-brain"></i> ML Control Center</h1>
        <p>Kelola model rekomendasi event — dataset, parameter KNN, dan monitoring</p>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="notification notification-success">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($success_message) ?></span>
            <span class="notification-close" onclick="this.closest('.notification').remove()"><i class="fas fa-times"></i></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="notification notification-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error_message) ?></span>
            <span class="notification-close" onclick="this.closest('.notification').remove()"><i class="fas fa-times"></i></span>
        </div>
    <?php endif; ?>

    <?php
        $isOnline     = !isset($health['error']) && !empty($health['status']);
        $modelLoaded  = !empty($health['model_loaded']);
        $currentConfig = $config['config'] ?? [];
        $datasetList   = $datasets['datasets'] ?? [];
        $eventList     = $events['events'] ?? [];
    ?>

    <!-- ═══════════════ STATUS PANEL ═══════════════ -->
    <div class="ml-grid-top">

        <!-- API Status Card -->
        <div class="ml-card status-card <?= $isOnline ? 'online' : 'offline' ?>">
            <div class="ml-card-icon">
                <i class="fas fa-server"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Status API</div>
                <div class="ml-card-value">
                    <?php if ($isOnline): ?>
                        <span class="badge-pill green"><i class="fas fa-circle"></i> Online</span>
                    <?php else: ?>
                        <span class="badge-pill red"><i class="fas fa-circle"></i> Offline</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Model Status Card -->
        <div class="ml-card status-card <?= $modelLoaded ? 'loaded' : 'unloaded' ?>">
            <div class="ml-card-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Model</div>
                <div class="ml-card-value">
                    <?php if ($modelLoaded): ?>
                        <span class="badge-pill green"><i class="fas fa-check"></i> Loaded</span>
                    <?php else: ?>
                        <span class="badge-pill yellow"><i class="fas fa-exclamation"></i> Not Loaded</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Total Events -->
        <div class="ml-card status-card">
            <div class="ml-card-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Events di Model</div>
                <div class="ml-card-value stat-number"><?= $health['total_events'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Vocabulary Size -->
        <div class="ml-card status-card">
            <div class="ml-card-icon">
                <i class="fas fa-font"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Vocabulary TF-IDF</div>
                <div class="ml-card-value stat-number"><?= number_format($health['vocab_size'] ?? 0) ?></div>
            </div>
        </div>

        <!-- Data Source -->
        <div class="ml-card status-card">
            <div class="ml-card-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Sumber Data</div>
                <div class="ml-card-value">
                    <?php if (($health['data_source'] ?? '') === 'manual'): ?>
                        <span class="badge-pill blue"><i class="fas fa-file-excel"></i> Manual</span>
                    <?php else: ?>
                        <span class="badge-pill teal"><i class="fas fa-database"></i> Database</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Last Built -->
        <div class="ml-card status-card">
            <div class="ml-card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="ml-card-body">
                <div class="ml-card-label">Model Dibangun</div>
                <div class="ml-card-value stat-small"><?= $health['built_at'] ?? '-' ?></div>
            </div>
        </div>
    </div>

    <!-- Manual Refresh Button -->
    <div class="ml-action-bar">
        <form method="POST" action="<?= BASEURL . '/admin/ml/refresh' ?>">
            <button type="submit" class="btn btn-primary" <?= !$isOnline ? 'disabled' : '' ?>>
                <i class="fas fa-sync-alt"></i> Rebuild Model Sekarang
            </button>
        </form>
        <a href="<?= BASEURL . '/admin/ml/preview' ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Preview Rekomendasi
        </a>
        <a href="<?= $this->recoApiBase ?? 'http://127.0.0.1:5000' ?>/api/ml/dataset/template" class="btn btn-success" target="_blank">
            <i class="fas fa-file-download"></i> Download Template Excel
        </a>
    </div>

    <div class="ml-grid-main">

        <!-- ═══════════════ KONFIGURASI PARAMETER ═══════════════ -->
        <div class="ml-section">
            <div class="content-area">
                <div class="content-header">
                    <h3><i class="fas fa-sliders-h"></i> Parameter KNN</h3>
                </div>
                <div class="content-body">
                    <form method="POST" action="<?= BASEURL . '/admin/ml/update_config' ?>" id="configForm">

                        <div class="param-grid">
                            <!-- KNN K -->
                            <div class="form-group">
                                <label for="knn_k">
                                    <i class="fas fa-ruler"></i> Nilai K (Jumlah Tetangga)
                                    <span class="param-badge current"><?= $currentConfig['knn_k'] ?? 11 ?></span>
                                </label>
                                <input type="number" class="form-control" id="knn_k" name="knn_k"
                                    min="1" max="50" value="<?= $currentConfig['knn_k'] ?? 11 ?>"
                                    oninput="document.getElementById('k_preview').textContent = this.value">
                                <small class="form-text">
                                    Jumlah tetangga terdekat yang dicari. Rekomendasi yang dikembalikan = K - 1 (event itu sendiri dikecualikan).
                                    Nilai lebih besar → hasil lebih beragam tapi lebih lambat.
                                </small>
                            </div>

                            <!-- Metric -->
                            <div class="form-group">
                                <label for="knn_metric">
                                    <i class="fas fa-compass"></i> Metric Jarak
                                    <span class="param-badge current"><?= $currentConfig['knn_metric'] ?? 'euclidean' ?></span>
                                </label>
                                <select class="form-control" id="knn_metric" name="knn_metric">
                                    <?php
                                    $metrics = ['euclidean', 'cosine', 'manhattan', 'chebyshev'];
                                    $current = $currentConfig['knn_metric'] ?? 'euclidean';
                                    foreach ($metrics as $m):
                                    ?>
                                        <option value="<?= $m ?>" <?= $m === $current ? 'selected' : '' ?>><?= ucfirst($m) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text">
                                    <strong>Euclidean</strong>: jarak garis lurus (default) |
                                    <strong>Cosine</strong>: sudut antar vektor |
                                    <strong>Manhattan</strong>: jarak blok kota |
                                    <strong>Chebyshev</strong>: maks dimensi
                                </small>
                            </div>

                            <!-- N Recommendations -->
                            <div class="form-group">
                                <label for="n_recommendations">
                                    <i class="fas fa-list-ol"></i> Jumlah Rekomendasi (N)
                                    <span class="param-badge current"><?= $currentConfig['n_recommendations'] ?? 3 ?></span>
                                </label>
                                <input type="number" class="form-control" id="n_recommendations" name="n_recommendations"
                                    min="1" max="10" value="<?= $currentConfig['n_recommendations'] ?? 3 ?>">
                                <small class="form-text">
                                    Jumlah event yang direkomendasikan di halaman detail event publik. Maksimal 10.
                                </small>
                            </div>

                            <!-- Data Source -->
                            <div class="form-group">
                                <label for="data_source">
                                    <i class="fas fa-database"></i> Sumber Dataset
                                    <span class="param-badge current"><?= $currentConfig['data_source'] ?? 'database' ?></span>
                                </label>
                                <select class="form-control" id="data_source" name="data_source"
                                    onchange="toggleDatasetSelect(this.value)">
                                    <option value="database" <?= ($currentConfig['data_source'] ?? '') === 'database' ? 'selected' : '' ?>>
                                        Database MySQL (otomatis)
                                    </option>
                                    <option value="manual" <?= ($currentConfig['data_source'] ?? '') === 'manual' ? 'selected' : '' ?>>
                                        Dataset Manual (Excel/CSV)
                                    </option>
                                </select>
                                <small class="form-text">
                                    Database: event diambil langsung dari tabel events (status=published).
                                    Manual: gunakan file Excel/CSV yang diupload.
                                </small>
                            </div>

                            <!-- Dataset File (tampil hanya jika manual) -->
                            <div class="form-group" id="dataset_file_group"
                                style="<?= ($currentConfig['data_source'] ?? '') === 'manual' ? '' : 'display:none' ?>">
                                <label for="dataset_file">
                                    <i class="fas fa-file-excel"></i> File Dataset Aktif
                                </label>
                                <select class="form-control" id="dataset_file" name="dataset_file">
                                    <option value="">-- Pilih File --</option>
                                    <?php foreach ($datasetList as $ds): ?>
                                        <option value="<?= htmlspecialchars($ds['filename']) ?>"
                                            <?= ($currentConfig['dataset_file'] ?? '') === $ds['filename'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ds['filename']) ?> (<?= $ds['size_kb'] ?> KB)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan & Rebuild Model
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ═══════════════ DATASET MANUAL ═══════════════ -->
        <div class="ml-section">
            <div class="content-area">
                <div class="content-header">
                    <h3><i class="fas fa-file-excel"></i> Dataset Manual</h3>
                </div>
                <div class="content-body">

                    <!-- Upload Form -->
                    <form method="POST" action="<?= BASEURL . '/admin/ml/upload_dataset' ?>"
                        enctype="multipart/form-data" id="uploadForm">
                        <div class="form-group">
                            <label for="dataset_file_upload">
                                <i class="fas fa-upload"></i> Upload File Dataset
                            </label>
                            <div class="upload-zone" onclick="document.getElementById('dataset_file_upload').click()">
                                <i class="fas fa-file-excel fa-2x"></i>
                                <p>Klik atau drag & drop file di sini</p>
                                <small>Format: .xlsx, .xls, .csv — Kolom wajib: id, title, description, location, category</small>
                            </div>
                            <input type="file" id="dataset_file_upload" name="dataset_file"
                                accept=".xlsx,.xls,.csv" style="display:none"
                                onchange="document.getElementById('upload_filename').textContent = this.files[0]?.name || ''">
                            <p id="upload_filename" class="mt-1 text-muted"></p>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                        <a href="http://127.0.0.1:5000/api/ml/dataset/template"
                            class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-download"></i> Template Excel
                        </a>
                    </form>

                    <hr style="margin: 20px 0">

                    <!-- Dataset List -->
                    <h4 style="margin-bottom:12px">File Tersedia</h4>
                    <?php if (empty($datasetList)): ?>
                        <div class="empty-state">
                            <i class="fas fa-folder-open fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Belum ada file dataset yang diupload.</p>
                        </div>
                    <?php else: ?>
                        <div class="dataset-list">
                            <?php foreach ($datasetList as $ds): ?>
                                <div class="dataset-item <?= $ds['active'] ? 'active' : '' ?>">
                                    <div class="dataset-info">
                                        <i class="fas fa-file-excel"></i>
                                        <span class="dataset-name"><?= htmlspecialchars($ds['filename']) ?></span>
                                        <span class="dataset-size"><?= $ds['size_kb'] ?> KB</span>
                                        <?php if ($ds['active']): ?>
                                            <span class="badge-pill green" style="font-size:0.75em">Aktif</span>
                                        <?php endif; ?>
                                    </div>
                                    <form method="POST" action="<?= BASEURL . '/admin/ml/delete_dataset' ?>"
                                        onsubmit="return confirm('Hapus file <?= htmlspecialchars($ds['filename']) ?>?')">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($ds['filename']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /ml-grid-main -->

    <!-- ═══════════════ EVENT LIST (dari model) ═══════════════ -->
    <div class="content-area" style="margin-top: 20px">
        <div class="content-header">
            <h3><i class="fas fa-list"></i> Event yang Dimuat ke Model (<?= count($eventList) ?>)</h3>
        </div>
        <div class="content-body">
            <?php if (empty($eventList)): ?>
                <p class="text-muted">Tidak ada event di model. Pastikan API berjalan dan ada event published.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventList as $ev): ?>
                                <tr>
                                    <td><?= htmlspecialchars($ev['id']) ?></td>
                                    <td><?= htmlspecialchars($ev['title']) ?></td>
                                    <td><span class="badge badge-warning"><?= htmlspecialchars($ev['category'] ?? '-') ?></span></td>
                                    <td><?= htmlspecialchars($ev['location']) ?></td>
                                    <td><?= htmlspecialchars($ev['start_time']) ?></td>
                                    <td>
                                        <a href="<?= BASEURL . '/admin/ml/preview/' . $ev['id'] ?>"
                                            class="btn btn-info btn-sm" title="Preview Rekomendasi">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /content-section -->

<style>
/* ── Status Cards ── */
.ml-grid-top {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}
.ml-card {
    background: #fff;
    border-radius: 10px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    border-left: 4px solid #e0e0e0;
}
.ml-card.online, .ml-card.loaded { border-left-color: #28a745; }
.ml-card.offline               { border-left-color: #dc3545; }
.ml-card.unloaded              { border-left-color: #ffc107; }
.ml-card-icon {
    font-size: 1.6em;
    color: #4a7c22;
    width: 36px;
    text-align: center;
}
.ml-card-label {
    font-size: 0.78em;
    color: #777;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.ml-card-value { font-weight: 600; }
.stat-number { font-size: 1.5em; color: #2d5016; }
.stat-small  { font-size: 0.85em; }
.badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 600;
}
.badge-pill.green  { background:#d4edda; color:#155724; }
.badge-pill.red    { background:#f8d7da; color:#721c24; }
.badge-pill.yellow { background:#fff3cd; color:#856404; }
.badge-pill.blue   { background:#d1ecf1; color:#0c5460; }
.badge-pill.teal   { background:#d1f0e8; color:#0d6645; }

/* ── Action Bar ── */
.ml-action-bar {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

/* ── Main Grid ── */
.ml-grid-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
@media (max-width: 900px) {
    .ml-grid-main { grid-template-columns: 1fr; }
}
.ml-section .content-area { height: 100%; }

/* ── Param Badge ── */
.param-badge.current {
    display: inline-block;
    background: #e8f5e9;
    color: #2d5016;
    padding: 1px 8px;
    border-radius: 10px;
    font-size: 0.8em;
    font-weight: 600;
    margin-left: 6px;
}
.param-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 700px) {
    .param-grid { grid-template-columns: 1fr; }
}

/* ── Upload Zone ── */
.upload-zone {
    border: 2px dashed #b0c4b1;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all .3s;
    color: #555;
    background: #f8fdf8;
}
.upload-zone:hover {
    border-color: #4a7c22;
    background: #f0f9f0;
    color: #2d5016;
}
.upload-zone i { color: #4a7c22; margin-bottom: 8px; }

/* ── Dataset List ── */
.dataset-list { display: flex; flex-direction: column; gap: 8px; }
.dataset-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    transition: border-color .2s;
}
.dataset-item.active {
    border-color: #4a7c22;
    background: #f8fdf8;
}
.dataset-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9em;
}
.dataset-info i { color: #217346; }
.dataset-name  { font-weight: 500; }
.dataset-size  { color: #888; font-size: 0.85em; }

.empty-state { text-align: center; padding: 30px; }
</style>

<script>
function toggleDatasetSelect(value) {
    const group = document.getElementById('dataset_file_group');
    group.style.display = value === 'manual' ? '' : 'none';
}
</script>