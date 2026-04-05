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
                <small>Transformasi teks mentah → token bersih dengan pembobotan field (title×3, category×5, location×2, description×1)</small>
            </div>
            <div class="content-area">
                <div class="content-body">

                    <!-- Before / After Grid -->
                    <div class="before-after-grid">
                        <!-- BEFORE -->
                        <div class="ba-panel before-panel">
                            <div class="ba-panel-header">
                                <i class="fas fa-file-alt"></i> SEBELUM
                                <span class="ba-badge red">Raw Text</span>
                            </div>
                            <div class="ba-panel-body">
                                <?php
                                $fields = [
                                    'title'       => ['label' => 'Judul',    'weight' => '×3', 'color' => '#e74c3c'],
                                    'category'    => ['label' => 'Kategori', 'weight' => '×5', 'color' => '#8e44ad'],
                                    'location'    => ['label' => 'Lokasi',   'weight' => '×2', 'color' => '#2980b9'],
                                    'description' => ['label' => 'Deskripsi','weight' => '×1', 'color' => '#27ae60'],
                                ];
                                foreach ($fields as $key => $meta):
                                    $rawVal = strip_tags($target[$key] ?? '-');
                                    $preview = mb_strlen($rawVal) > 120 ? mb_substr($rawVal, 0, 120) . '…' : $rawVal;
                                ?>
                                <div class="ba-field">
                                    <div class="ba-field-label" style="border-left: 3px solid <?= $meta['color'] ?>;">
                                        <strong><?= $meta['label'] ?></strong>
                                        <span class="weight-badge" style="background:<?= $meta['color'] ?>;"><?= $meta['weight'] ?></span>
                                    </div>
                                    <div class="ba-field-value raw"><?= htmlspecialchars($preview) ?></div>
                                    <div class="ba-field-stats">
                                        <span><?= mb_strlen($rawVal) ?> karakter</span>
                                        <span><?= str_word_count($rawVal) ?> kata</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="ba-arrow">
                            <div class="pipeline-steps">
                                <div class="pipe-step"><i class="fas fa-font"></i> Lowercase</div>
                                <div class="pipe-arrow">↓</div>
                                <div class="pipe-step"><i class="fas fa-eraser"></i> Hapus angka &amp; simbol</div>
                                <div class="pipe-arrow">↓</div>
                                <div class="pipe-step"><i class="fas fa-filter"></i> Hapus stopword ID</div>
                                <div class="pipe-arrow">↓</div>
                                <div class="pipe-step"><i class="fas fa-cut"></i> Stemming Sastrawi</div>
                                <div class="pipe-arrow">↓</div>
                                <div class="pipe-step"><i class="fas fa-balance-scale"></i> Bobot field</div>
                            </div>
                        </div>

                        <!-- AFTER -->
                        <div class="ba-panel after-panel">
                            <div class="ba-panel-header">
                                <i class="fas fa-check-circle"></i> SESUDAH
                                <span class="ba-badge green">Content Profile</span>
                            </div>
                            <div class="ba-panel-body">
                                <?php
                                // Parse the profile back into weighted segments
                                $profileTokens = array_filter(explode(' ', $profile));
                                $counted = array_count_values($profileTokens);
                                arsort($counted);
                                $maxCount = max(array_values($counted) ?: [1]);

                                // Show token cloud with frequency coloring
                                echo '<div class="token-cloud">';
                                $shown = 0;
                                foreach ($counted as $token => $count) {
                                    if ($shown >= 80) break;
                                    $size = 0.75 + ($count / $maxCount) * 0.9;
                                    $opacity = 0.5 + ($count / $maxCount) * 0.5;
                                    $hue = max(0, 120 - ($count / $maxCount) * 120);
                                    echo '<span class="token" style="font-size:' . round($size, 2) . 'em;opacity:' . round($opacity, 2) . ';background:hsl(' . $hue . ',60%,88%);" title="muncul ' . $count . 'x">' . htmlspecialchars($token) . '</span>';
                                    $shown++;
                                }
                                echo '</div>';
                                ?>

                                <div class="ba-stats-row">
                                    <div class="ba-stat">
                                        <span class="stat-val"><?= count($profileTokens) ?></span>
                                        <span class="stat-lbl">Total Token</span>
                                    </div>
                                    <div class="ba-stat">
                                        <span class="stat-val"><?= count($counted) ?></span>
                                        <span class="stat-lbl">Token Unik</span>
                                    </div>
                                    <div class="ba-stat">
                                        <span class="stat-val"><?= mb_strlen($profile) ?></span>
                                        <span class="stat-lbl">Karakter</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /before-after-grid -->

                    <!-- Stopword removal detail -->
                    <?php
                    // Compute removed tokens (rough estimate from raw vs clean)
                    $rawAllText = strip_tags(implode(' ', [
                        $target['title'] ?? '',
                        $target['category'] ?? '',
                        $target['location'] ?? '',
                        $target['description'] ?? '',
                    ]));
                    $rawAllText = strtolower(preg_replace('/[^a-z0-9\s]/i', ' ', $rawAllText));
                    $rawTokensAll = array_filter(explode(' ', $rawAllText));
                    $rawCount = count($rawTokensAll);
                    $cleanUniqueCount = count($counted);
                    $reduction = $rawCount > 0 ? round((1 - count($profileTokens) / max($rawCount,1)) * 100) : 0;
                    ?>
                    <div class="reduction-bar-wrap">
                        <div class="reduction-label">
                            <span>Token awal: <strong><?= $rawCount ?></strong></span>
                            <span>→</span>
                            <span>Setelah preprocessing: <strong><?= count($profileTokens) ?></strong></span>
                            <span class="reduction-pct">Reduksi <?= $reduction ?>%</span>
                        </div>
                        <div class="reduction-bar">
                            <div class="reduction-fill" style="width:<?= max(5, 100 - $reduction) ?>%">
                                <span><?= count($profileTokens) ?> token tersisa</span>
                            </div>
                            <div class="reduction-removed" style="width:<?= min(95, $reduction) ?>%">
                                <span><?= $rawCount - count($profileTokens) ?> dihapus</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ══ STEP 3: TF-IDF ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">3</div>
                <h3>Vektor TF-IDF — Proses &amp; Hasil</h3>
                <small>
                    Vocabulary total: <strong><?= number_format($minfo['vocab_size'] ?? 0) ?></strong> term |
                    Skema: sublinear TF, bigram (1,2)
                </small>
            </div>
            <div class="content-area">
                <div class="content-body">

                    <!-- Formula explanation -->
                    <div class="formula-box">
                        <div class="formula-title"><i class="fas fa-calculator"></i> Rumus TF-IDF (sublinear)</div>
                        <div class="formula-cols">
                            <div class="formula-item">
                                <div class="formula-name">TF (Term Frequency)</div>
                                <div class="formula-eq">
                                    TF(t,d) = 1 + log(count(t,d)) &nbsp; <em>jika count &gt; 0</em>
                                </div>
                                <div class="formula-note">sublinear_tf=True meredam efek pengulangan kata yang berlebihan</div>
                            </div>
                            <div class="formula-sep">×</div>
                            <div class="formula-item">
                                <div class="formula-name">IDF (Inverse Document Freq)</div>
                                <div class="formula-eq">
                                    IDF(t) = log( N / df(t) ) + 1
                                </div>
                                <div class="formula-note">N = total dokumen (<?= $minfo['total_events'] ?? '?' ?>), df = jumlah dokumen yang mengandung term t</div>
                            </div>
                            <div class="formula-sep">⇒</div>
                            <div class="formula-item result">
                                <div class="formula-name">TF-IDF Score</div>
                                <div class="formula-eq">
                                    w(t,d) = TF(t,d) × IDF(t)
                                </div>
                                <div class="formula-note">Dinormalisasi dengan L2-norm agar panjang dokumen tidak mempengaruhi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step-by-step for top 5 terms -->
                    <?php if (!empty($terms)): ?>
                    <div class="tfidf-calc-table-wrap">
                        <div class="tfidf-calc-title">
                            <i class="fas fa-table"></i> Detail Perhitungan — Top <?= min(count($terms), 10) ?> Term
                            <span class="calc-note">(nilai setelah normalisasi L2)</span>
                        </div>
                        <table class="tfidf-calc-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Term</th>
                                    <th>Frekuensi di Profil</th>
                                    <th>TF (sublinear)</th>
                                    <th>IDF (estimasi)</th>
                                    <th>Skor TF-IDF</th>
                                    <th>Visualisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $maxScore = !empty($terms) ? max(array_column($terms, 'score')) : 1;
                                foreach (array_slice($terms, 0, 10) as $i => $t):
                                    $termFreq = isset($counted[$t['term']]) ? $counted[$t['term']] : (isset($counted[strtolower($t['term'])]) ? $counted[strtolower($t['term'])] : 1);
                                    $tfVal = $termFreq > 0 ? round(1 + log($termFreq), 4) : 0;
                                    $idfEst = $tfVal > 0 && $t['score'] > 0 ? round($t['score'] / $tfVal, 4) : '-';
                                    $pct = $maxScore > 0 ? round($t['score'] / $maxScore * 100) : 0;
                                ?>
                                <tr>
                                    <td class="rank-cell"><?= $i + 1 ?></td>
                                    <td><span class="term-chip"><?= htmlspecialchars($t['term']) ?></span></td>
                                    <td class="center-cell"><?= $termFreq ?>×</td>
                                    <td class="center-cell mono">
                                        <?php if ($termFreq > 0): ?>
                                            1 + log(<?= $termFreq ?>) = <strong><?= $tfVal ?></strong>
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    </td>
                                    <td class="center-cell mono">≈ <?= $idfEst ?></td>
                                    <td class="center-cell">
                                        <span class="score-chip" style="background:hsl(<?= 120 - $pct ?>,60%,88%);">
                                            <?= $t['score'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="tfidf-bar-wrap">
                                            <div class="tfidf-bar" style="width:<?= $pct ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <!-- Sparsity info -->
                    <div class="sparsity-info">
                        <div class="sparsity-item">
                            <i class="fas fa-vector-square"></i>
                            <span>Dimensi vektor: <strong><?= number_format($minfo['vocab_size'] ?? 0) ?></strong></span>
                        </div>
                        <div class="sparsity-item">
                            <i class="fas fa-dot-circle"></i>
                            <span>Non-zero elemen: <strong><?= count($terms) > 0 ? '~' . count($terms) : '?' ?></strong></span>
                        </div>
                        <div class="sparsity-item">
                            <i class="fas fa-compress-alt"></i>
                            <span>Sparsity: <strong>~<?= $minfo['vocab_size'] > 0 ? round((1 - count($terms)/max($minfo['vocab_size'],1))*100,1) : '?' ?>%</strong></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ══ STEP 4: KNN ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">4</div>
                <h3>KNN — Proses Pencarian Tetangga Terdekat</h3>
                <small>
                    Metric: <strong>Euclidean</strong> |
                    K: <strong><?= $minfo['k'] ?? '' ?></strong> |
                    Jarak Euclidean dihitung di ruang TF-IDF <?= number_format($minfo['vocab_size'] ?? 0) ?>-dimensi
                </small>
            </div>
            <div class="content-area">
                <div class="content-body">

                    <!-- Formula -->
                    <div class="formula-box">
                        <div class="formula-title"><i class="fas fa-ruler-combined"></i> Rumus Jarak &amp; Skor Kemiripan</div>
                        <div class="formula-cols">
                            <div class="formula-item">
                                <div class="formula-name">Jarak Euclidean</div>
                                <div class="formula-eq">
                                    d(q, p) = √ Σ(qᵢ − pᵢ)²
                                </div>
                                <div class="formula-note">q = vektor event target, p = vektor event kandidat, i = indeks dimensi</div>
                            </div>
                            <div class="formula-sep">→</div>
                            <div class="formula-item result">
                                <div class="formula-name">Similarity Score</div>
                                <div class="formula-eq">
                                    sim = 1 / (1 + d)
                                </div>
                                <div class="formula-note">Konversi jarak → kemiripan: d=0 → sim=1 (identik), d→∞ → sim→0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculation steps table -->
                    <div class="knn-calc-wrap">
                        <div class="knn-calc-title">
                            <i class="fas fa-sort-amount-up-alt"></i> Detail Perhitungan Jarak — Semua K=<?= $minfo['k'] ?? '' ?> Tetangga
                        </div>
                        <div class="table-responsive">
                            <table class="table knn-detail-table">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Event</th>
                                        <th>Kategori</th>
                                        <th>d = √Σ(qᵢ−pᵢ)²</th>
                                        <th>sim = 1/(1+d)</th>
                                        <th>Perbandingan Jarak</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $maxDist = !empty($neighbors) ? max(array_column($neighbors, 'distance')) : 1;
                                    foreach ($neighbors as $i => $nb):
                                        $dPct = $maxDist > 0 ? round($nb['distance'] / $maxDist * 100) : 0;
                                        $simPct = round($nb['similarity_score'] * 100);
                                    ?>
                                    <tr class="<?= $nb['is_target'] ? 'row-target' : '' ?>">
                                        <td class="rank-cell"><?= $i + 1 ?></td>
                                        <td>
                                            <?php if (!$nb['is_target']): ?>
                                                <a href="<?= BASEURL . '/admin/ml/preview/' . $nb['id'] ?>">
                                                    <?= htmlspecialchars(mb_substr($nb['title'], 0, 35)) ?><?= mb_strlen($nb['title']) > 35 ? '…' : '' ?>
                                                </a>
                                            <?php else: ?>
                                                <strong>⭐ <?= htmlspecialchars($nb['title']) ?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="cat-chip"><?= htmlspecialchars($nb['category'] ?? '-') ?></span></td>
                                        <td>
                                            <div class="dist-calc">
                                                <span class="mono">√Σ = <strong><?= $nb['distance'] ?></strong></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sim-calc">
                                                <span class="mono">1/(1+<?= $nb['distance'] ?>) = <strong><?= $nb['similarity_score'] ?></strong></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dual-bar">
                                                <div class="dual-bar-d" title="Jarak: <?= $nb['distance'] ?>">
                                                    <div class="dual-fill-d" style="width:<?= $dPct ?>%"></div>
                                                    <span>d=<?= $nb['distance'] ?></span>
                                                </div>
                                                <div class="dual-bar-s" title="Kemiripan: <?= $simPct ?>%">
                                                    <div class="dual-fill-s" style="width:<?= $simPct ?>%"></div>
                                                    <span>sim=<?= $simPct ?>%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($nb['is_target']): ?>
                                                <span class="badge badge-warning">Target</span>
                                            <?php elseif ($i <= 3): ?>
                                                <span class="badge badge-success">Top Kandidat</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Kandidat</span>
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
        </div>

        <!-- ══ STEP 5: VISUALISASI 2D ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">5</div>
                <h3>Visualisasi 2D — Kedekatan Antar Event</h3>
                <small>Proyeksi 2D dari jarak Euclidean K=<?= $minfo['k'] ?? '' ?> tetangga. Semakin dekat titik → semakin mirip event.</small>
            </div>
            <div class="content-area">
                <div class="content-body">
                    <div id="knn-viz-container">
                        <canvas id="knnCanvas" width="800" height="480"></canvas>
                        <div class="viz-legend">
                            <div class="legend-item"><span class="legend-dot" style="background:#e74c3c;"></span> Event Target</div>
                            <div class="legend-item"><span class="legend-dot" style="background:#27ae60;border:2px solid #1e8449;"></span> Top 3 Rekomendasi</div>
                            <div class="legend-item"><span class="legend-dot" style="background:#3498db;"></span> Tetangga Lainnya</div>
                            <div class="legend-item"><span class="legend-line"></span> Koneksi KNN</div>
                        </div>
                        <div class="viz-note">
                            <i class="fas fa-info-circle"></i>
                            Posisi dihitung menggunakan <strong>Multidimensional Scaling (MDS)</strong> berbasis matriks jarak Euclidean.
                            Jarak pada visualisasi 2D merepresentasikan kemiripan konten antar event.
                        </div>
                    </div>

                    <!-- Distance matrix mini -->
                    <div class="dist-matrix-wrap">
                        <div class="dist-matrix-title"><i class="fas fa-th"></i> Matriks Jarak dari Target ke Setiap Tetangga</div>
                        <div class="dist-matrix-scroll">
                            <div class="dist-matrix">
                                <div class="dm-row header-row">
                                    <div class="dm-cell dm-label">Target \ Tetangga</div>
                                    <?php foreach ($neighbors as $nb): ?>
                                        <div class="dm-cell dm-header" title="<?= htmlspecialchars($nb['title']) ?>">
                                            <?= htmlspecialchars(mb_substr($nb['title'], 0, 12)) ?><?= mb_strlen($nb['title']) > 12 ? '…' : '' ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="dm-row">
                                    <div class="dm-cell dm-label"><?= htmlspecialchars(mb_substr($target['title'] ?? 'Target', 0, 14)) ?>…</div>
                                    <?php
                                    $minDist = !empty($neighbors) ? min(array_column($neighbors, 'distance')) : 0;
                                    $maxDistM = !empty($neighbors) ? max(array_column($neighbors, 'distance')) : 1;
                                    foreach ($neighbors as $nb):
                                        $norm = $maxDistM > 0 ? ($nb['distance'] - $minDist) / max($maxDistM - $minDist, 0.001) : 0;
                                        $r = round(255 * $norm);
                                        $g = round(255 * (1 - $norm));
                                        $b = 80;
                                    ?>
                                        <div class="dm-cell dm-val <?= $nb['is_target'] ? 'dm-self' : '' ?>"
                                             style="background:rgba(<?= $r ?>,<?= $g ?>,<?= $b ?>,0.25)"
                                             title="<?= htmlspecialchars($nb['title']) ?>: d=<?= $nb['distance'] ?>">
                                            <?= $nb['is_target'] ? '—' : $nb['distance'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ══ STEP 6: HASIL AKHIR ══ -->
        <div class="preview-step">
            <div class="step-header">
                <div class="step-number">6</div>
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
                                            <span class="score-value"><?= round($r['similarity_score'] * 100, 1) ?>%</span>
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

<!-- ══════════════ STYLES ══════════════ -->
<style>
.param-grid-2 { display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end; }

/* Steps */
.preview-step { margin-bottom: 24px; }
.step-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.step-header h3 { margin: 0; font-size: 1.1em; }
.step-header small { color: #888; }
.step-number {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #4a7c22, #2d5016);
    color: #fff; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.9em; flex-shrink: 0;
}

/* Target Event Card */
.target-event-card {
    display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;
    background: #f8fdf8; border: 1px solid #c8e6c9; border-radius: 10px; padding: 18px;
}
.target-info h4 { margin: 0 0 8px; color: #2d5016; }
.meta-row { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 8px; font-size: 0.88em; color: #555; }
.meta-row span { display: flex; align-items: center; gap: 5px; }
.meta-row i { color: #4a7c22; }
.description-text { font-size: 0.9em; color: #555; line-height: 1.5; margin: 0; }
.target-id-badge { background: #4a7c22; color: #fff; padding: 6px 12px; border-radius: 8px; font-weight: 600; white-space: nowrap; }

/* ── Before / After ── */
.before-after-grid {
    display: grid;
    grid-template-columns: 1fr 140px 1fr;
    gap: 0;
    margin-bottom: 20px;
    align-items: start;
}
.ba-panel { border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.before-panel { border: 2px solid #e74c3c33; }
.after-panel  { border: 2px solid #27ae6033; }
.ba-panel-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px; font-weight: 600; font-size: 0.9em;
}
.before-panel .ba-panel-header { background: #fdf0ee; color: #c0392b; }
.after-panel  .ba-panel-header { background: #eafaf1; color: #1e8449; }
.ba-badge { padding: 2px 8px; border-radius: 10px; font-size: 0.75em; font-weight: 600; }
.ba-badge.red { background: #e74c3c; color: #fff; }
.ba-badge.green { background: #27ae60; color: #fff; }
.ba-panel-body { padding: 14px; background: #fff; }

.ba-field { margin-bottom: 14px; border-bottom: 1px dashed #eee; padding-bottom: 12px; }
.ba-field:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.ba-field-label { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; padding-left: 8px; font-size: 0.85em; }
.weight-badge { padding: 1px 6px; border-radius: 8px; font-size: 0.75em; color: #fff; font-weight: 700; }
.ba-field-value.raw { font-size: 0.82em; color: #555; background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 8px; line-height: 1.5; }
.ba-field-stats { display: flex; gap: 12px; margin-top: 4px; font-size: 0.75em; color: #999; }

/* Pipeline arrow */
.ba-arrow {
    display: flex; align-items: center; justify-content: center;
    padding: 10px 8px;
}
.pipeline-steps { display: flex; flex-direction: column; align-items: center; gap: 4px; }
.pipe-step {
    background: linear-gradient(135deg, #4a7c22, #2d5016);
    color: #fff; border-radius: 6px; padding: 5px 10px;
    font-size: 0.72em; text-align: center; width: 120px;
    display: flex; align-items: center; gap: 5px;
}
.pipe-arrow { color: #4a7c22; font-size: 1.2em; font-weight: bold; }

/* Token cloud */
.token-cloud { display: flex; flex-wrap: wrap; gap: 5px; padding: 10px; background: #f9f9f9; border-radius: 6px; max-height: 160px; overflow-y: auto; }
.token { padding: 2px 7px; border-radius: 4px; cursor: default; transition: background .2s; font-size: 0.8em; }
.token:hover { filter: brightness(0.9); }

/* Stats row */
.ba-stats-row { display: flex; gap: 12px; margin-top: 12px; justify-content: center; }
.ba-stat { text-align: center; background: #f0f9f0; border-radius: 8px; padding: 8px 14px; }
.stat-val { display: block; font-size: 1.4em; font-weight: 700; color: #2d5016; }
.stat-lbl { font-size: 0.72em; color: #777; }

/* Reduction bar */
.reduction-bar-wrap { margin-top: 14px; }
.reduction-label { display: flex; gap: 10px; align-items: center; font-size: 0.85em; margin-bottom: 6px; flex-wrap: wrap; }
.reduction-pct { background: #e74c3c; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 0.85em; }
.reduction-bar { display: flex; height: 26px; border-radius: 6px; overflow: hidden; border: 1px solid #ddd; }
.reduction-fill { background: linear-gradient(90deg, #27ae60, #2ecc71); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75em; font-weight: 600; min-width: 60px; }
.reduction-removed { background: linear-gradient(90deg, #e74c3c, #c0392b); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75em; font-weight: 600; flex: 1; }

/* ── TF-IDF Formula ── */
.formula-box {
    background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 10px;
    padding: 16px 20px; margin-bottom: 20px;
}
.formula-title { font-weight: 700; color: #2d5016; margin-bottom: 14px; font-size: 0.95em; }
.formula-cols { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.formula-item { flex: 1; min-width: 180px; background: #fff; border-radius: 8px; padding: 12px; border: 1px solid #e0e0e0; }
.formula-item.result { background: #eafaf1; border-color: #27ae60; }
.formula-name { font-size: 0.78em; text-transform: uppercase; letter-spacing: .5px; color: #888; margin-bottom: 6px; }
.formula-eq { font-family: 'Courier New', monospace; font-size: 0.9em; color: #2d5016; font-weight: 600; background: #f0faf0; padding: 6px 10px; border-radius: 4px; margin-bottom: 6px; }
.formula-note { font-size: 0.75em; color: #777; line-height: 1.4; }
.formula-sep { font-size: 1.5em; color: #4a7c22; font-weight: 700; flex-shrink: 0; }

/* TF-IDF calc table */
.tfidf-calc-table-wrap { margin-bottom: 16px; }
.tfidf-calc-title { font-weight: 600; color: #2d5016; margin-bottom: 10px; font-size: 0.9em; }
.calc-note { color: #999; font-weight: 400; font-size: 0.85em; margin-left: 8px; }
.tfidf-calc-table { width: 100%; border-collapse: collapse; font-size: 0.85em; }
.tfidf-calc-table thead tr { background: #2d5016; color: #fff; }
.tfidf-calc-table th, .tfidf-calc-table td { padding: 8px 10px; border: 1px solid #e0e0e0; vertical-align: middle; }
.tfidf-calc-table tbody tr:nth-child(even) { background: #f9f9f9; }
.tfidf-calc-table tbody tr:hover { background: #f0f9f0; }
.rank-cell { text-align: center; font-weight: 700; color: #777; width: 36px; }
.center-cell { text-align: center; }
.mono { font-family: 'Courier New', monospace; font-size: 0.88em; }
.term-chip { background: #e8f5e9; color: #2d5016; padding: 3px 10px; border-radius: 12px; font-weight: 600; }
.score-chip { padding: 3px 10px; border-radius: 12px; font-weight: 700; font-size: 0.9em; }
.tfidf-bar-wrap { background: #e9ecef; border-radius: 4px; height: 16px; overflow: hidden; }
.tfidf-bar { height: 100%; background: linear-gradient(90deg, #4a7c22, #76b041); border-radius: 4px; }

/* Sparsity */
.sparsity-info { display: flex; gap: 20px; flex-wrap: wrap; background: #f8f9fa; border-radius: 8px; padding: 12px 16px; }
.sparsity-item { display: flex; align-items: center; gap: 8px; font-size: 0.88em; color: #555; }
.sparsity-item i { color: #4a7c22; }

/* ── KNN calc ── */
.knn-calc-wrap { margin-top: 16px; }
.knn-calc-title { font-weight: 600; color: #2d5016; margin-bottom: 10px; font-size: 0.9em; }
.knn-detail-table { font-size: 0.82em; }
.knn-detail-table thead tr { background: #2d5016; color: #fff; }
.knn-detail-table th, .knn-detail-table td { padding: 7px 9px; border: 1px solid #e0e0e0; vertical-align: middle; }
.knn-detail-table tbody tr:nth-child(even) { background: #f9f9f9; }
.knn-detail-table tbody tr:hover { background: #fffde7; }
.row-target { background: #fffde7 !important; }
.cat-chip { background: #fff3cd; color: #856404; padding: 2px 7px; border-radius: 8px; font-size: 0.82em; }
.dist-calc { white-space: nowrap; }
.sim-calc { white-space: nowrap; font-size: 0.82em; }
.dual-bar { display: flex; flex-direction: column; gap: 3px; min-width: 140px; }
.dual-bar-d, .dual-bar-s {
    height: 14px; background: #f0f0f0; border-radius: 3px; overflow: hidden;
    position: relative; display: flex; align-items: center;
}
.dual-fill-d { height: 100%; background: linear-gradient(90deg, #e74c3c, #c0392b); position: absolute; top: 0; left: 0; }
.dual-fill-s { height: 100%; background: linear-gradient(90deg, #27ae60, #2ecc71); position: absolute; top: 0; left: 0; }
.dual-bar-d span, .dual-bar-s span { position: relative; z-index: 1; font-size: 0.7em; padding-left: 5px; color: #333; font-weight: 600; }

/* ── 2D Viz ── */
#knn-viz-container { position: relative; text-align: center; }
#knnCanvas {
    border: 1px solid #e0e0e0; border-radius: 10px;
    background: #fafff9; max-width: 100%; cursor: crosshair;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.viz-legend {
    display: flex; gap: 20px; justify-content: center; margin-top: 10px; flex-wrap: wrap;
}
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.82em; color: #555; }
.legend-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.legend-line { width: 24px; height: 2px; background: #aaa; display: inline-block; }
.viz-note { margin-top: 10px; font-size: 0.8em; color: #777; background: #f8f9fa; border-radius: 6px; padding: 8px 14px; text-align: left; }

/* Distance matrix */
.dist-matrix-wrap { margin-top: 20px; }
.dist-matrix-title { font-weight: 600; color: #2d5016; margin-bottom: 10px; font-size: 0.9em; }
.dist-matrix-scroll { overflow-x: auto; }
.dist-matrix { display: flex; flex-direction: column; font-size: 0.75em; min-width: max-content; }
.dm-row { display: flex; }
.dm-cell { padding: 5px 8px; border: 1px solid #e0e0e0; text-align: center; white-space: nowrap; }
.dm-label { background: #f0f9f0; color: #2d5016; font-weight: 600; min-width: 100px; }
.dm-header { background: #2d5016; color: #fff; font-weight: 600; min-width: 85px; max-width: 85px; overflow: hidden; text-overflow: ellipsis; }
.header-row .dm-label { background: #2d5016; color: #fff; }
.dm-val { min-width: 85px; font-family: monospace; }
.dm-self { background: #fffde7 !important; }

/* Result cards */
.result-cards { display: flex; flex-direction: column; gap: 12px; }
.result-card { display: flex; align-items: center; gap: 16px; border: 1px solid #e0e0e0; border-radius: 10px; padding: 14px; background: #fff; transition: box-shadow .2s; }
.result-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.result-rank { width: 36px; height: 36px; flex-shrink: 0; background: linear-gradient(135deg, #4a7c22, #2d5016); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.result-body { flex: 1; }
.result-body h4 { margin: 0 0 6px; font-size: 1em; }
.result-score-wrap { display: flex; align-items: center; gap: 10px; margin-top: 8px; }
.score-label { font-size: 0.8em; color: #777; white-space: nowrap; }
.score-bar-large { flex: 1; height: 10px; background: #e9ecef; border-radius: 5px; overflow: hidden; }
.score-bar-large .score-fill { height: 100%; background: linear-gradient(90deg, #4a7c22, #76b041); }
.score-value { font-weight: 600; font-size: 0.88em; color: #2d5016; }
.result-action { flex-shrink: 0; }
.empty-preview { text-align: center; padding: 60px 20px; }

@media (max-width: 900px) {
    .before-after-grid { grid-template-columns: 1fr; }
    .ba-arrow { padding: 10px; }
    .pipeline-steps { flex-direction: row; flex-wrap: wrap; justify-content: center; }
    .formula-cols { flex-direction: column; }
    .formula-sep { transform: rotate(90deg); }
}
</style>

<!-- ══ CANVAS 2D VIZ SCRIPT ══ -->
<?php if ($success && !empty($neighbors)): ?>
<script>
(function() {
    const neighborsData = <?= json_encode($neighbors) ?>;
    const targetTitle   = <?= json_encode(mb_substr($target['title'] ?? '', 0, 20)) ?>;

    // ── MDS from distance matrix ──
    function mds(nodes) {
        const n = nodes.length;
        // Construct D² matrix
        const D2 = Array.from({length: n}, () => Array(n).fill(0));
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                if (i === j) { D2[i][j] = 0; continue; }
                // Use known distances from target (index 0 for target)
                const di = nodes[i].distance;
                const dj = nodes[j].distance;
                // Approximate d_ij using law of cosines heuristic
                D2[i][j] = Math.abs(di * di + dj * dj - 2 * di * dj * 0.3);
            }
        }
        // Double centering
        const rowMean = D2.map(row => row.reduce((a,b) => a+b, 0) / n);
        const totalMean = rowMean.reduce((a,b) => a+b, 0) / n;
        const B = D2.map((row, i) => row.map((val, j) =>
            -0.5 * (val - rowMean[i] - rowMean[j] + totalMean)
        ));
        // Power iteration for top-2 eigenvectors
        function powerIter(B, iters) {
            const n = B.length;
            let v = Array.from({length: n}, () => Math.random() - 0.5);
            let lambda = 0;
            for (let k = 0; k < iters; k++) {
                const Bv = B.map(row => row.reduce((s, val, j) => s + val * v[j], 0));
                lambda = Math.sqrt(Bv.reduce((s, x) => s + x*x, 0));
                v = lambda > 0 ? Bv.map(x => x / lambda) : v;
            }
            return { v, lambda };
        }
        const { v: v1, lambda: l1 } = powerIter(B, 60);
        const deflated = B.map((row, i) => row.map((val, j) => val - l1 * v1[i] * v1[j]));
        const { v: v2, lambda: l2 } = powerIter(deflated, 60);
        return v1.map((x, i) => [x * Math.sqrt(Math.max(0, l1)), v2[i] * Math.sqrt(Math.max(0, l2))]);
    }

    // Assign virtual distance 0 to target itself
    const nodes = neighborsData.map(nb => ({...nb, distance: nb.is_target ? 0 : nb.distance}));
    const coords = mds(nodes);

    // Normalize to canvas
    const canvas = document.getElementById('knnCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const W = canvas.width, H = canvas.height;
    const PAD = 70;

    let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
    coords.forEach(([x, y]) => {
        if (x < minX) minX = x; if (x > maxX) maxX = x;
        if (y < minY) minY = y; if (y > maxY) maxY = y;
    });
    const rangeX = maxX - minX || 1, rangeY = maxY - minY || 1;

    function toCanvas(x, y) {
        return [
            PAD + (x - minX) / rangeX * (W - PAD * 2),
            PAD + (y - minY) / rangeY * (H - PAD * 2)
        ];
    }

    const positions = coords.map(([x, y]) => toCanvas(x, y));
    const targetIdx = nodes.findIndex(n => n.is_target);

    // Find recommended indices
    const recIds = <?= json_encode(array_column($results, 'id')) ?>;

    function draw() {
        ctx.clearRect(0, 0, W, H);

        // Grid lines
        ctx.strokeStyle = '#f0f0f0';
        ctx.lineWidth = 1;
        for (let i = 0; i <= 5; i++) {
            const x = PAD + i * (W - PAD*2) / 5;
            const y = PAD + i * (H - PAD*2) / 5;
            ctx.beginPath(); ctx.moveTo(x, PAD); ctx.lineTo(x, H - PAD); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(PAD, y); ctx.lineTo(W - PAD, y); ctx.stroke();
        }

        // Draw connection lines
        if (targetIdx >= 0) {
            const [tx, ty] = positions[targetIdx];
            positions.forEach(([px, py], i) => {
                if (i === targetIdx) return;
                const isRec = recIds.includes(nodes[i].id);
                ctx.beginPath();
                ctx.moveTo(tx, ty);
                ctx.lineTo(px, py);
                ctx.strokeStyle = isRec ? 'rgba(39,174,96,0.4)' : 'rgba(170,170,170,0.25)';
                ctx.lineWidth = isRec ? 2 : 1;
                ctx.setLineDash(isRec ? [] : [4, 4]);
                ctx.stroke();
                ctx.setLineDash([]);

                // Distance label on line
                if (isRec) {
                    const mx = (tx + px) / 2, my = (ty + py) / 2;
                    ctx.font = '10px monospace';
                    ctx.fillStyle = '#27ae60';
                    ctx.textAlign = 'center';
                    ctx.fillText('d=' + nodes[i].distance, mx, my - 4);
                }
            });
        }

        // Draw nodes
        positions.forEach(([px, py], i) => {
            const nb = nodes[i];
            const isTarget = nb.is_target;
            const isRec = recIds.includes(nb.id);

            const radius = isTarget ? 18 : (isRec ? 14 : 9);

            // Shadow
            ctx.shadowColor = isTarget ? '#e74c3c' : (isRec ? '#27ae60' : '#aaa');
            ctx.shadowBlur = isTarget ? 14 : (isRec ? 8 : 3);

            ctx.beginPath();
            ctx.arc(px, py, radius, 0, Math.PI * 2);
            if (isTarget) {
                ctx.fillStyle = '#e74c3c';
            } else if (isRec) {
                ctx.fillStyle = '#27ae60';
            } else {
                const norm = nb.distance / (Math.max(...nodes.map(n=>n.distance)) || 1);
                const r = Math.round(100 + norm * 60);
                const g = Math.round(149 + (1-norm)*40);
                const b = Math.round(210 - norm*50);
                ctx.fillStyle = `rgb(${r},${g},${b})`;
            }
            ctx.fill();
            ctx.shadowBlur = 0;

            if (isTarget || isRec) {
                ctx.strokeStyle = isTarget ? '#c0392b' : '#1e8449';
                ctx.lineWidth = 2;
                ctx.stroke();
            }

            // Node label
            const label = isTarget ? targetTitle : mb_truncate(nb.title, 14);
            ctx.font = isTarget ? 'bold 11px sans-serif' : (isRec ? 'bold 10px sans-serif' : '9px sans-serif');
            ctx.fillStyle = '#222';
            ctx.textAlign = 'center';
            ctx.shadowBlur = 0;

            // Background for readability
            const tw = ctx.measureText(label).width + 6;
            const lx = px, ly = py + radius + 13;
            ctx.fillStyle = 'rgba(255,255,255,0.85)';
            ctx.fillRect(lx - tw/2, ly - 11, tw, 14);
            ctx.fillStyle = isTarget ? '#c0392b' : (isRec ? '#1e8449' : '#444');
            ctx.fillText(label, lx, ly);

            // Distance badge for non-target
            if (!isTarget) {
                ctx.font = '9px monospace';
                ctx.fillStyle = '#666';
                ctx.fillText('d=' + nb.distance, px, py + radius + 26);
            }
        });

        // Title
        ctx.font = 'bold 12px sans-serif';
        ctx.fillStyle = '#2d5016';
        ctx.textAlign = 'left';
        ctx.fillText('Visualisasi Jarak KNN (MDS 2D)', 12, 20);
        ctx.font = '10px sans-serif';
        ctx.fillStyle = '#999';
        ctx.fillText('Total events dalam model: <?= $minfo['total_events'] ?? '?' ?>', 12, 34);
    }

    function mb_truncate(str, len) {
        return str.length > len ? str.substring(0, len) + '…' : str;
    }

    draw();

    // Tooltip on hover
    canvas.addEventListener('mousemove', function(e) {
        const rect = canvas.getBoundingClientRect();
        const mx = (e.clientX - rect.left) * (W / rect.width);
        const my = (e.clientY - rect.top) * (H / rect.height);

        let hit = -1;
        positions.forEach(([px, py], i) => {
            const r = nodes[i].is_target ? 18 : (recIds.includes(nodes[i].id) ? 14 : 9);
            if (Math.hypot(mx - px, my - py) <= r + 4) hit = i;
        });

        draw();
        if (hit >= 0) {
            const nb = nodes[hit];
            const [px, py] = positions[hit];
            // Tooltip box
            const lines = [
                nb.title,
                'Kategori: ' + (nb.category || '-'),
                'Jarak: ' + nb.distance,
                'Kemiripan: ' + (nb.similarity_score * 100).toFixed(1) + '%',
            ];
            const tw = 220, th = lines.length * 16 + 12;
            let bx = px + 20, by = py - th / 2;
            if (bx + tw > W) bx = px - tw - 10;
            if (by < 0) by = 0;
            if (by + th > H) by = H - th;
            ctx.fillStyle = 'rgba(30,40,30,0.92)';
            ctx.beginPath();
            ctx.roundRect(bx, by, tw, th, 6);
            ctx.fill();
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 10px sans-serif';
            ctx.textAlign = 'left';
            lines.forEach((line, i) => {
                ctx.font = i === 0 ? 'bold 10px sans-serif' : '10px sans-serif';
                ctx.fillText(line.substring(0, 32), bx + 8, by + 14 + i * 16);
            });
            canvas.style.cursor = 'pointer';
        } else {
            canvas.style.cursor = 'crosshair';
        }
    });

    canvas.addEventListener('mouseleave', draw);

})();
</script>
<?php endif; ?>