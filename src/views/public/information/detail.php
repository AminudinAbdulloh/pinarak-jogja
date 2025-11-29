<?php
    $url = BASEURL . '/informations/detail/' . $informations['id'];

    $encodedTitle = urlencode($informations['title']);
    $encodedUrl = urlencode($url);
?>

<section class="article-detail-section">
    <div class="article-container">
        <!-- Information Header -->
        <article class="article-main">
            <header class="article-header">
                <h1 class="article-title"><?php echo htmlspecialchars($informations['title']); ?></h1>
                
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($informations['address']); ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>Diperbarui: <?php echo htmlspecialchars(formatDate($informations['updated_at'], 'indo')); ?></span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="<?= htmlspecialchars($informations['google_map_link']); ?>" 
                       target="_blank"
                       class="btn-map">
                        <i class="fas fa-map-marker-alt"></i> Lihat di Google Maps
                    </a>
                </div>
            </header>

            <!-- Featured Image -->
            <div class="article-featured-image">
                <img src="<?= BASEURL . '/' . $informations['image'] ?>" 
                     alt="<?= htmlspecialchars($informations['title']) ?>">
            </div>

            <!-- Information Content -->
            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($informations['article'])); ?>
            </div>

            <!-- Information Footer -->
            <footer class="article-footer">
                <div class="share-section">
                    <h3>Bagikan Informasi</h3>
                    <div class="share-buttons">
                        <!-- Facebook -->
                        <a href="https://www.addtoany.com/add_to/facebook?linkurl=<?= $encodedUrl ?>&linkname=<?= $encodedTitle ?>&linknote="
                            target="_blank"
                            class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>

                        <!-- Twitter -->
                        <a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedTitle ?>"
                            target="_blank"
                            class="share-btn twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>

                        <!-- WhatsApp -->
                        <a href="<?= 'https://wa.me/?text=' . urlencode($informations['title'] . ' ' . BASEURL . '/informations/detail/' . $informations['id']) ?>" 
                            target="_blank" 
                            class="share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>

                        <button onclick="copyLink()" class="share-btn copy">
                            <i class="fas fa-link"></i> Salin Link
                        </button>
                    </div>
                </div>

                <div class="back-button">
                    <a href="<?= BASEURL . '/informations' ?>" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Informasi
                    </a>
                </div>
            </footer>
        </article>

        <!-- Related Informations (if available) -->
        <?php if (!empty($relatedInformations)): ?>
        <aside class="related-articles">
            <h2>Informasi Terkait</h2>
            <div class="related-grid">
                <?php foreach ($relatedInformations as $related): ?>
                <article class="related-card">
                    <?php if (!empty($related['image'])): ?>
                    <div class="related-image">
                        <img src="<?= BASEURL . '/' . $related['image'] ?>" 
                             alt="<?= htmlspecialchars($related['title']) ?>">
                    </div>
                    <?php endif; ?>
                    <div class="related-content">
                        <span class="related-date">
                            <i class="fas fa-map-marker-alt"></i> 
                            <?= htmlspecialchars($related['address']) ?>
                        </span>
                        <h3>
                            <a href="<?= BASEURL ?>/informations/detail/<?= $related['id'] ?>">
                                <?= htmlspecialchars($related['title']) ?>
                            </a>
                        </h3>
                        <?php if (!empty($related['article'])): ?>
                        <p><?= truncateText($related['article'], 100) ?></p>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </aside>
        <?php endif; ?>
    </div>
</section>

<style>
.article-detail-section {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  padding: 40px 20px;
  min-height: 100vh;
}

.article-container {
  max-width: 1200px;
  margin: 0 auto;
}

/* Article Main */
.article-main {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 40px;
}

/* Article Header */
.article-header {
  padding: 40px;
  border-bottom: 1px solid #e9ecef;
}

.article-title {
  font-size: 2.2em;
  color: #0e4b75;
  margin: 0 0 20px 0;
  line-height: 1.3;
}

.article-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  margin-bottom: 20px;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #666;
  font-size: 0.9em;
}

.meta-item i {
  color: #c86b85;
}

/* Action Buttons */
.action-buttons {
  margin-top: 20px;
}

.btn-map {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #c86b85, #d87a94);
  color: white;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 500;
  font-size: 0.95em;
  transition: all 0.3s ease;
  box-shadow: 0 3px 10px rgba(200, 107, 133, 0.3);
}

.btn-map:hover {
  background: linear-gradient(135deg, #d87a94, #c86b85);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(200, 107, 133, 0.4);
}

.btn-map i {
  font-size: 1.1em;
}

/* Featured Image */
.article-featured-image {
  width: 70%;
  max-height: 500px;
  overflow: hidden;
  margin: 0 auto;
  display: flex;
  justify-content: center;
}

.article-featured-image img {
  width: auto;
  max-width: 100%;
  height: auto;
  border-radius: 8px;
}

/* Article Content */
.article-content {
  padding: 40px;
  font-size: 1.05em;
  line-height: 1.8;
  color: #333;
}

.article-content p {
  margin-bottom: 1.2em;
}

/* Article Footer */
.article-footer {
  padding: 30px 40px;
  background: #f8f9fa;
  border-top: 1px solid #e9ecef;
}

.share-section {
  margin-bottom: 25px;
}

.share-section h3 {
  font-size: 1.1em;
  color: #0e4b75;
  margin-bottom: 15px;
}

.share-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.share-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border: none;
  border-radius: 25px;
  font-size: 0.9em;
  font-weight: 500;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.share-btn i {
  font-size: 1.1em;
}

.share-btn.facebook {
  background: #3b5998;
  color: white;
}

.share-btn.facebook:hover {
  background: #2d4373;
  transform: translateY(-2px);
}

.share-btn.twitter {
  background: #1da1f2;
  color: white;
}

.share-btn.twitter:hover {
  background: #0c85d0;
  transform: translateY(-2px);
}

.share-btn.whatsapp {
  background: #25d366;
  color: white;
}

.share-btn.whatsapp:hover {
  background: #1da851;
  transform: translateY(-2px);
}

.share-btn.copy {
  background: #6c757d;
  color: white;
}

.share-btn.copy:hover {
  background: #545b62;
  transform: translateY(-2px);
}

.back-button {
  text-align: center;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 30px;
  background: linear-gradient(135deg, #0e4b75, #1a5a8a);
  color: white;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-back:hover {
  background: linear-gradient(135deg, #1a5a8a, #0e4b75);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(14, 75, 117, 0.3);
}

/* Related Articles */
.related-articles {
  margin-top: 40px;
}

.related-articles h2 {
  font-size: 1.8em;
  color: #0e4b75;
  margin-bottom: 25px;
  text-align: center;
}

.related-articles h2::after {
  content: "";
  display: block;
  width: 60px;
  height: 4px;
  background-color: #c86b85;
  margin: 10px auto 0;
}

.related-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}

.related-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.related-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.related-image {
  width: 100%;
  height: 180px;
  overflow: hidden;
}

.related-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.related-card:hover .related-image img {
  transform: scale(1.1);
}

.related-content {
  padding: 20px;
}

.related-date {
  font-size: 0.85em;
  color: #777;
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 8px;
}

.related-date i {
  color: #c86b85;
}

.related-content h3 {
  font-size: 1.1em;
  margin: 0 0 10px 0;
}

.related-content h3 a {
  color: #0e4b75;
  text-decoration: none;
  transition: color 0.3s;
}

.related-content h3 a:hover {
  color: #c86b85;
}

.related-content p {
  font-size: 0.9em;
  color: #666;
  margin: 0;
  line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
  .article-detail-section {
    padding: 20px 15px;
  }

  .article-header,
  .article-content,
  .article-footer {
    padding: 25px 20px;
  }

  .article-title {
    font-size: 1.6em;
  }

  .article-meta {
    flex-direction: column;
    gap: 10px;
  }

  .share-buttons {
    flex-direction: column;
  }

  .share-btn {
    width: 100%;
    justify-content: center;
  }

  .related-grid {
    grid-template-columns: 1fr;
  }

  .action-buttons {
    text-align: center;
  }

  .btn-map {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .article-title {
    font-size: 1.4em;
  }

  .article-content {
    font-size: 1em;
  }

  .article-featured-image {
    max-height: 300px;
    width: 100%;
  }
}
</style>

<script>
function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
    }).catch(err => {
        console.error('Gagal menyalin link:', err);
    });
}
</script>