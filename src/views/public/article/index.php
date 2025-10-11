<section class="berita-section">
    <h1>BERITA TERKINI</h1>
    <div class="berita-container">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <article class="berita-card">
                    <img src="<?= BASEURL . '/' . $article['image'] ?>"
                        alt="<?= htmlspecialchars($article['title']) ?>">
                    <div class="berita-content">
                        <span class="tanggal"><?php echo formatDate($article['created_at']); ?></span>
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>

                        <?php if (!empty($article['excerpt'])): ?>
                            <p><?php echo truncateText($article['excerpt'], 150); ?></p>
                        <?php else: ?>
                            <p><?php echo truncateText(strip_tags($article['content']), 150); ?></p>
                        <?php endif; ?>

                        <a href="">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles">
                <p>Belum ada artikel yang dipublikasikan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>