<div class="information-section">
    <h1>MEDIA PARTNER</h1>
    <div class="media-partner">
        <?php if(!empty($media_partners)): ?>
            <?php foreach($media_partners as $partner): ?>
                <div class="logo-card">
                    <img src="<?= BASEURL . '/' . htmlspecialchars($partner['logo']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada media partner yang tersedia.</p>
        <?php endif; ?>
    </div>
</div>