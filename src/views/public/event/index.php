<section class="event-section">
    <h1>SEMUA EVENT</h1>
    <div class="event-container">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <article class="event-card">
                    <img src="<?= BASEURL . '/' . ($event['image'] ?: 'assets/images/events/default.png') ?>"
                        alt="<?= htmlspecialchars($event['title']) ?>">
                    <div class="event-content">
                        <div class="event-meta">
                            <span class="tanggal meta-item">
                                <i class="far fa-calendar"></i>
                                <?php echo formatDateIndonesia($event['start_time']); ?>
                            </span>
                            <span class="event-location meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($event['location']); ?>
                            </span>
                        </div>
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <?php if (!empty($event['description'])): ?>
                            <p><?php echo truncateText(strip_tags($event['description']), 150); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-events">
                <p>Belum ada event yang dipublikasikan.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <!-- Tombol Previous -->
            <?php if ($currentPage > 1): ?>
                <a href="<?= BASEURL . '/events/' . ($currentPage - 1) ?>" class="pagination-btn">←</a>
            <?php else: ?>
                <span class="pagination-btn disabled">←</span>
            <?php endif; ?>

            <!-- Nomor Halaman -->
            <div class="pagination-numbers">
                <?php 
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    
                    if ($startPage > 1): ?>
                        <a href="<?= BASEURL . '/events/1' ?>" class="pagination-link">1</a>
                        <?php if ($startPage > 2): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                    <?php endif; 
                    
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="pagination-link active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= BASEURL . '/events/' . $i ?>" class="pagination-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; 
                    
                    if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                        <a href="<?= BASEURL . '/events/' . $totalPages ?>" class="pagination-link"><?= $totalPages ?></a>
                    <?php endif; ?>
            </div>

            <!-- Tombol Next -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= BASEURL . '/events/' . ($currentPage + 1) ?>" class="pagination-btn">→</a>
            <?php else: ?>
                <span class="pagination-btn disabled">→</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="back-button">
        <a href="<?= BASEURL ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</section>

