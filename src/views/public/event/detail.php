<section class="event-detail-section">
    <header class="event-detail-header">
        <h1><?= htmlspecialchars($event['title'] ?? 'Detail Event') ?></h1>
    </header>

    <div class="event-detail-layout">
        <!-- KOLUMEN KIRI: POSTER + DESKRIPSI -->
        <div class="event-detail-left">
            <!-- Poster Event -->
            <figure class="event-detail-poster">
                <img src="<?= BASEURL . '/' . (!empty($event['image']) ? $event['image'] : 'assets/images/events/default.png') ?>"
                    alt="<?= htmlspecialchars($event['title'] ?? 'Poster Event') ?>">
            </figure>

            <!-- Deskripsi Event -->
            <section class="event-detail-description">
                <h2>Deskripsi Event</h2>
                <?php if (!empty($event['description'])): ?>
                    <div class="description-content">
                        <?= $event['description'] ?>
                    </div>
                <?php else: ?>
                    <p>Belum ada deskripsi untuk event ini.</p>
                <?php endif; ?>

                <!-- Bagikan Event -->
                <div class="event-share">
                    <span class="share-label">Bagikan event ini:</span>
                    <?php
                        $shareUrl   = BASEURL . '/events/detail/' . urlencode($event['id'] ?? '');
                        $shareTitle = htmlspecialchars($event['title'] ?? 'Event');
                    ?>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>"
                           target="_blank" rel="noopener" class="share-btn fb">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl) ?>&text=<?= $shareTitle ?>"
                           target="_blank" rel="noopener" class="share-btn x">
                            <i class="fab fa-x-twitter"></i> X
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($shareTitle . ' - ' . $shareUrl) ?>"
                           target="_blank" rel="noopener" class="share-btn wa">
                            <i class="fab fa-whatsapp"></i> Whatsapp
                        </a>
                        <a href="mailto:?subject=<?= $shareTitle ?>&body=<?= urlencode($shareUrl) ?>"
                           class="share-btn email">
                            <i class="far fa-envelope"></i> Email
                        </a>
                        <button type="button" class="share-btn copy-link" data-link="<?= htmlspecialchars($shareUrl) ?>">
                            <i class="far fa-copy"></i> Salin Link
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- KOLUMEN KANAN: INFO SINGKAT + REKOMENDASI -->
        <aside class="event-detail-right">
            <!-- Info Singkat Event -->
            <section class="event-summary-card">
                <h2 class="event-summary-title">Informasi Event</h2>
                <div class="event-summary-meta">
                    <div class="meta-row">
                        <span class="meta-label">Tanggal</span>
                        <span class="meta-value">
                            <?php if (!empty($event['start_time'])): ?>
                                <?= formatDateIndonesia($event['start_time']); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Jam</span>
                        <span class="meta-value">
                            <?php if (!empty($event['start_time'])): ?>
                                <?= date('H:i', strtotime($event['start_time'])); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Lokasi</span>
                        <span class="meta-value">
                            <?= !empty($event['location']) ? htmlspecialchars($event['location']) : '-' ?>
                        </span>
                    </div>
                </div>

                <!-- Tambahkan ke Kalender (contoh: Google Calendar) -->
                <?php
                    if (!empty($event['start_time'])) {
                        $startDate   = date('Ymd\THis', strtotime($event['start_time']));
                        $endTimeRaw  = $event['end_time'] ?? null;
                        $endDate     = !empty($endTimeRaw)
                            ? date('Ymd\THis', strtotime($endTimeRaw))
                            : date('Ymd\THis', strtotime($event['start_time'] . ' +2 hours'));
                        $location  = $event['location'] ?? '';
                        $details   = strip_tags($event['description'] ?? '');
                        $gcUrl     = 'https://www.google.com/calendar/render?action=TEMPLATE'
                            . '&text=' . urlencode($event['title'] ?? 'Event')
                            . '&dates=' . $startDate . '/' . $endDate
                            . '&details=' . urlencode($details)
                            . '&location=' . urlencode($location)
                            . '&sf=true&output=xml';
                    }
                ?>
                <?php if (!empty($event['start_time'])): ?>
                    <a href="<?= $gcUrl ?>" target="_blank" rel="noopener" class="btn-add-calendar">
                        Tambahkan ke Kalender
                    </a>
                <?php endif; ?>
            </section>

            <!-- Rekomendasi Event Terkait -->
            <section class="event-recommendation">
                <header class="section-header">
                    <h2>Rekomendasi Event Terkait</h2>
                </header>

                <?php if (!empty($relatedEvents)): ?>
                    <div class="recommendation-list">
                        <?php foreach ($relatedEvents as $rel): ?>
                            <article class="recommendation-card">
                                <a href="<?= BASEURL . '/events/detail/' . urlencode($rel['id']) ?>">
                                    <h3 class="rec-title"><?= htmlspecialchars($rel['title']) ?></h3>
                                </a>
                                <div class="rec-meta">
                                    <span class="rec-item">
                                        <i class="far fa-calendar"></i>
                                        <?= !empty($rel['start_time']) ? formatDateIndonesia($rel['start_time']) : '-' ?>
                                    </span>
                                    <span class="rec-item">
                                        <i class="far fa-clock"></i>
                                        <?= !empty($rel['start_time']) ? date('H:i', strtotime($rel['start_time'])) : '-' ?>
                                    </span>
                                    <span class="rec-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= !empty($rel['location']) ? htmlspecialchars($rel['location']) : '-' ?>
                                    </span>
                                </div>

                                <?php
                                    if (!empty($rel['start_time'])) {
                                        $rStart    = date('Ymd\THis', strtotime($rel['start_time']));
                                        $rEndRaw   = $rel['end_time'] ?? null;
                                        $rEnd      = !empty($rEndRaw)
                                            ? date('Ymd\THis', strtotime($rEndRaw))
                                            : date('Ymd\THis', strtotime($rel['start_time'] . ' +2 hours'));
                                        $rLoc   = $rel['location'] ?? '';
                                        $rDesc  = strip_tags($rel['description'] ?? '');
                                        $rGcUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
                                            . '&text=' . urlencode($rel['title'] ?? 'Event')
                                            . '&dates=' . $rStart . '/' . $rEnd
                                            . '&details=' . urlencode($rDesc)
                                            . '&location=' . urlencode($rLoc)
                                            . '&sf=true&output=xml';
                                    }
                                ?>
                                <?php if (!empty($rel['start_time'])): ?>
                                    <a href="<?= $rGcUrl ?>" target="_blank" rel="noopener" class="btn-add-calendar small">
                                        Tambahkan ke Kalender
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-recommendation">Belum ada rekomendasi event terkait.</p>
                <?php endif; ?>
            </section>
        </aside>
    </div>

    <!-- Tombol Kembali -->
    <div class="back-button">
        <a href="<?= BASEURL . '/events' ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Event
        </a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.querySelector('.share-btn.copy-link');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            const link = this.getAttribute('data-link');
            if (!link) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(link).then(function () {
                    alert('Link berhasil disalin.');
                }).catch(function () {
                    alert('Gagal menyalin link.');
                });
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = link;
                document.body.appendChild(tempInput);
                tempInput.select();
                try {
                    document.execCommand('copy');
                    alert('Link berhasil disalin.');
                } catch (e) {
                    alert('Gagal menyalin link.');
                }
                document.body.removeChild(tempInput);
            }
        });
    }
});
</script>


