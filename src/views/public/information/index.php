<section class="information-section">
    <h1>INFORMASI OBJEK WISATA</h1>
    <div class="information-container">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <article class="information-card">
                    <img src="<?= BASEURL . '/' . $article['image'] ?>"
                        alt="<?= htmlspecialchars($article['title']) ?>">
                    <div class="information-content">
                        <span class="tanggal"><?php echo formatDate($article['created_at']); ?></span>
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>

                        <?php if (!empty($article['article'])): ?>
                            <p><?php echo truncateText(strip_tags($article['article']), 150); ?></p>
                        <?php else: ?>
                            <p><?php echo htmlspecialchars($article['category']); ?></p>
                        <?php endif; ?>

                        <a href="<?= BASEURL . '/informations/detail/' . $article['id'] ?>">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles">
                <p>Belum ada objek wisata yang dipublikasikan.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <!-- Tombol Previous -->
            <?php if ($currentPage > 1): ?>
                <a href="<?= BASEURL . '/informations/' . ($currentPage - 1) ?>" class="pagination-btn">←</a>
            <?php else: ?>
                <span class="pagination-btn disabled">←</span>
            <?php endif; ?>

            <!-- Nomor Halaman -->
            <div class="pagination-numbers">
                <?php 
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    
                    if ($startPage > 1): ?>
                        <a href="<?= BASEURL . '/informations/1' ?>" class="pagination-link">1</a>
                        <?php if ($startPage > 2): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                    <?php endif; 
                    
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="pagination-link active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= BASEURL . '/informations/' . $i ?>" class="pagination-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; 
                    
                    if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                        <a href="<?= BASEURL . '/informations/' . $totalPages ?>" class="pagination-link"><?= $totalPages ?></a>
                    <?php endif; ?>
            </div>

            <!-- Tombol Next -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= BASEURL . '/informations/' . ($currentPage + 1) ?>" class="pagination-btn">→</a>
            <?php else: ?>
                <span class="pagination-btn disabled">→</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>