<div class="content-section" id="ml-preview">
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Preview Rekomendasi</h1>
        <p>Lihat seluruh proses rekomendasi dari awal hingga hasil akhir</p>
    </div>

    <?php
        $eventsArr = $events['events'] ?? [];
        $detail    = $detail ?? [];
        $success   = !empty($detail['success']);
    ?>

    <!-- Event Selector -->
    <div class="content-area" style="margin-bottom: 20px">
        <div class="content-header">
            <h3><i class="fas fa-search"></i> Pilih Event untuk Dianalisa</h3>
        </div>
        <div class="content-body">
            <form method="GET" action="" id="previewForm">
                <div class="param-grid-2">
                    <div class="form-group">
                        <label for="event_select">Event Target</label>
                        <select class="form-control" id="event_select"
                            onchange="window.location.href='<?= BASEURL . '/admin/ml/preview/' ?>' + this.value">
                            <option value="">-- Pilih Event --</option>
                            <?php foreach ($eventsArr as $ev): ?>
                                <option value="<?= $ev['id'] ?>"
                                    <?= isset($event_id) && $ev['id'] == $event_id ? 'selected' : '' ?>>
                                    [<?= $ev['id'] ?>] <?= htmlspecialchars($ev['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="display:flex;align-items:flex-end">
                        <a href="<?= BASEURL . '/admin/ml' ?>" class="btn btn-warning">
                            <i class="fas fa-arrow-left"></i> Kembali ke ML Control
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!isset($event_id)): ?>
        <div class="empty-preview">
            <i class="fas fa-mouse-pointer fa-3x text-muted"></i>
            <p class="text-muted mt-3">Pilih event di atas untuk melihat proses rekomendasi.</p>
        </div>
    <?php elseif (!$success): ?>
        <div class="notification notification-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($detail['message'] ?? 'Gagal mengambil detail rekomendasi.') ?></span>
        </div>
    <?php else: ?>

        <?php
            $target   = $detail['target_event']    ?? [];
            $profile  = $detail['content_profile'] ?? '';
            $terms    = $detail['top_tfidf_terms']  ?? [];
            $neighbors= $detail['knn_neighbors']    ?? [];
            $results  = $detail['recommendations'] ?? [];
            $minfo    = $detail['model_info']       ?? [];
        ?>

        <!-- ══ STEP 1: EVENT TARGET ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">1</div>
                <h3>Event Target</h3>
                <small>Event yang menjadi acuan pencarian rekomendasi</small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <div class="target-event-card">
                        <div class="target-info">
                            <h4><?= htmlspecialchars($target['title'] ?? '') ?></h4>
                            <div class="meta-row">
                                <span><i class="fas fa-tag"></i> <?= htmlspecialchars($target['category'] ?? '-') ?></span>
                                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($target['location'] ?? '-') ?></span>
                                <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($target['start_time'] ?? '-') ?></span>
                            </div>
                            <p class="description-text"><?= htmlspecialchars(strip_tags(substr($target['description'] ?? '', 0, 300))) ?>...</p>
                        </div>
                        <div class="target-id-badge">ID: <?= $target['id'] ?? '' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ STEP 2: PREPROCESSING ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">2</div>
                <h3>Preprocessing & Content Profile</h3>
                <small>Teks gabungan setelah lowercase, hapus stopword, dan stemming (bobot: title×3, category×5, location×2, description×1)</small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <div class="token-cloud">
                        <?php
                            $tokens = array_filter(explode(' ', $profile));
                            $counted = array_count_values($tokens);
                            arsort($counted);
                            $maxCount = max(array_values($counted)) ?: 1;
                            foreach ($counted as $token => $count):
                                $size = 0.8 + ($count / $maxCount) * 0.8;
                        ?>
                            <span class="token" style="font-size:<?= round($size, 2) ?>em"><?= htmlspecialchars($token) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-muted mt-2" style="font-size:0.85em">Total token: <?= count($tokens) ?> | Unique: <?= count($counted) ?></p>
                </div>
            </div>
        </div>

        <!-- ══ STEP 3: TF-IDF ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">3</div>
                <h3>Vektor TF-IDF (Top 15 Term)</h3>
                <small>Term dengan bobot TF-IDF tertinggi untuk event target — vocabulary total: <?= number_format($minfo['vocab_size'] ?? 0) ?> term</small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <div class="tfidf-bars">
                        <?php
                            $maxScore = !empty($terms) ? $terms[0]['score'] : 1;
                            foreach ($terms as $t):
                                $pct = $maxScore > 0 ? round(($t['score'] / $maxScore) * 100) : 0;
                        ?>
                            <div class="tfidf-row">
                                <div class="tfidf-term"><?= htmlspecialchars($t['term']) ?></div>
                                <div class="tfidf-bar-wrap">
                                    <div class="tfidf-bar" style="width:<?= $pct ?>%"></div>
                                </div>
                                <div class="tfidf-score"><?= $t['score'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ STEP 4: KNN ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">4</div>
                <h3>KNN — Pencarian Tetangga Terdekat</h3>
                <small>
                    Metric: <strong><?= htmlspecialchars($minfo['metric'] ?? '') ?></strong> |
                    K: <strong><?= $minfo['k'] ?? '' ?></strong> |
                    Jarak lebih kecil = lebih mirip
                </small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event</th>
                                    <th>Kategori</th>
                                    <th>Jarak (<?= htmlspecialchars($minfo['metric'] ?? '') ?>)</th>
                                    <th>Skor Kemiripan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($neighbors as $i => $nb): ?>
                                    <tr class="<?= $nb['is_target'] ? 'row-target' : '' ?>">
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <?php if (!$nb['is_target']): ?>
                                                <a href="<?= BASEURL . '/admin/ml/preview/' . $nb['id'] ?>">
                                                    <?= htmlspecialchars($nb['title']) ?>
                                                </a>
                                            <?php else: ?>
                                                <strong><?= htmlspecialchars($nb['title']) ?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($nb['category'] ?? '-') ?></td>
                                        <td>
                                            <span class="distance-badge" style="--d:<?= min($nb['distance'] / 2, 1) ?>">
                                                <?= $nb['distance'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="score-bar-mini">
                                                <div class="score-fill" style="width:<?= $nb['similarity_score'] * 100 ?>%"></div>
                                                <span><?= $nb['similarity_score'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($nb['is_target']): ?>
                                                <span class="badge badge-warning">Target</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Kandidat</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ STEP 5: HASIL AKHIR ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">5</div>
                <h3>Hasil Rekomendasi Final</h3>
                <small>Event yang akan ditampilkan kepada pengunjung di halaman detail event</small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <?php if (empty($results)): ?>
                        <p class="text-muted">Tidak ada rekomendasi tersedia (mungkin data event terlalu sedikit).</p>
                    <?php else: ?>
                        <div class="result-cards">
                            <?php foreach ($results as $rank => $r): ?>
                                <div class="result-card">
                                    <div class="result-rank"><?= $rank + 1 ?></div>
                                    <div class="result-body">
                                        <h4><?= htmlspecialchars($r['title']) ?></h4>
                                        <div class="meta-row">
                                            <span><i class="fas fa-tag"></i> <?= htmlspecialchars($r['category'] ?? '-') ?></span>
                                            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($r['location'] ?? '-') ?></span>
                                        </div>
                                        <div class="result-score-wrap">
                                            <span class="score-label">Kemiripan</span>
                                            <div class="score-bar-large">
                                                <div class="score-fill" style="width:<?= $r['similarity_score'] * 100 ?>%"></div>
                                            </div>
                                            <span class="score-value"><?= $r['similarity_score'] * 100 ?>%</span>
                                        </div>
                                    </div>
                                    <a href="<?= BASEURL . '/admin/ml/preview/' . $r['id'] ?>"
                                        class="btn btn-info btn-sm result-action">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<style>
.param-grid-2 { display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end; }

/* Steps */
.preview-step { margin-bottom: 24px; }
.step-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.step-header h3 { margin: 0; font-size: 1.1em; }
.step-header small { color: #888; }
.step-number {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #4a7c22, #2d5016);
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.9em;
    flex-shrink: 0;
}

/* Target Event Card */
.target-event-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    background: #f8fdf8;
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    padding: 18px;
}
.target-info h4 { margin: 0 0 8px; color: #2d5016; }
.meta-row { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 8px; font-size: 0.88em; color: #555; }
.meta-row span { display: flex; align-items: center; gap: 5px; }
.meta-row i { color: #4a7c22; }
.description-text { font-size: 0.9em; color: #555; line-height: 1.5; margin: 0; }
.target-id-badge {
    background: #4a7c22; color: #fff;
    padding: 6px 12px; border-radius: 8px;
    font-weight: 600; white-space: nowrap;
}

/* Token Cloud */
.token-cloud {
    display: flex; flex-wrap: wrap; gap: 8px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 8px;
    max-height: 200px; overflow-y: auto;
}
.token {
    background: #e8f5e9; color: #2d5016;
    padding: 2px 8px; border-radius: 4px;
    transition: background .2s;
}
.token:hover { background: #c8e6c9; }

/* TF-IDF Bars */
.tfidf-bars { display: flex; flex-direction: column; gap: 8px; }
.tfidf-row { display: grid; grid-template-columns: 180px 1fr 70px; align-items: center; gap: 12px; }
.tfidf-term { font-size: 0.88em; font-weight: 500; text-align: right; }
.tfidf-bar-wrap { background: #e9ecef; border-radius: 4px; height: 18px; overflow: hidden; }
.tfidf-bar { height: 100%; background: linear-gradient(90deg, #4a7c22, #76b041); border-radius: 4px; transition: width .5s; }
.tfidf-score { font-size: 0.8em; color: #777; }

/* KNN table rows */
.row-target { background: #fffde7 !important; }
.distance-badge {
    display: inline-block;
    background: hsl(calc(120 - var(--d) * 120), 60%, 90%);
    color: hsl(calc(120 - var(--d) * 120), 60%, 30%);
    padding: 2px 8px; border-radius: 4px;
    font-size: 0.85em; font-weight: 600;
}
.score-bar-mini {
    display: flex; align-items: center; gap: 8px;
    background: #e9ecef; border-radius: 4px; height: 16px;
    overflow: hidden; position: relative; min-width: 100px;
}
.score-bar-mini .score-fill {
    height: 100%;
    background: linear-gradient(90deg, #4a7c22, #76b041);
    position: absolute; top: 0; left: 0;
}
.score-bar-mini span {
    position: relative; z-index: 1;
    font-size: 0.75em; font-weight: 600;
    padding-left: 6px; color: #333;
}

/* Result Cards */
.result-cards { display: flex; flex-direction: column; gap: 12px; }
.result-card {
    display: flex; align-items: center; gap: 16px;
    border: 1px solid #e0e0e0; border-radius: 10px;
    padding: 14px; background: #fff;
    transition: box-shadow .2s;
}
.result-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.result-rank {
    width: 36px; height: 36px; flex-shrink: 0;
    background: linear-gradient(135deg, #4a7c22, #2d5016);
    color: #fff; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
}
.result-body { flex: 1; }
.result-body h4 { margin: 0 0 6px; font-size: 1em; }
.result-score-wrap { display: flex; align-items: center; gap: 10px; margin-top: 8px; }
.score-label { font-size: 0.8em; color: #777; white-space: nowrap; }
.score-bar-large {
    flex: 1; height: 10px;
    background: #e9ecef; border-radius: 5px; overflow: hidden;
}
.score-bar-large .score-fill {
    height: 100%; background: linear-gradient(90deg, #4a7c22, #76b041);
}
.score-value { font-weight: 600; font-size: 0.88em; color: #2d5016; }
.result-action { flex-shrink: 0; }
.empty-preview { text-align: center; padding: 60px 20px; }
</style>