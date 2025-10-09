<div class="layar-penuh">
    <header id="home">
        <div class="slider">
            <div class="slides">
                <img src="assets/images/banners/wjnc.png" alt="Banner 1">
                <img src="assets/images/banners/yogyakarta.jpg" alt="Banner 2">
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </header>
    <section class="container">
        <h2 class="section-title">EVENT TERBARU</h2>
        <div class="main-content">
            <div class="highlight-event" id="highlight-event">
                <?php if ($highlight_event): ?>
                    <img src="<?= $highlight_event['image'] ?: 'assets/images/events/default.png' ?>"
                        alt="<?= htmlspecialchars($highlight_event['title']) ?>" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title"><?= htmlspecialchars($highlight_event['title']) ?></h3>
                        <p class="event-date" id="highlight-date" data-datetime="<?= $highlight_event['start_time'] ?>">
                            📅 <?= formatDateIndonesia($highlight_event['start_time']) ?>
                        </p>
                        <p class="event-location" id="highlight-location">
                            📍 <?= htmlspecialchars($highlight_event['location']) ?>
                        </p>
                        <p class="description" id="highlight-description">
                            <?= nl2br(htmlspecialchars(truncateText($highlight_event['description'], 200))) ?>
                        </p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                <?php else: ?>
                    <img src="assets/images/events/default.png" alt="No Event" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title">Tidak Ada Event Mendatang</h3>
                        <p class="description" id="highlight-description">Belum ada event yang dijadwalkan. Pantau terus untuk update terbaru!</p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="event-list">
                <?php if (!empty($other_events)): ?>
                    <?php foreach ($other_events as $index => $event): ?>
                        <div class="event-item clickable-event" data-event-index="<?= $index + 1 ?>">
                            <img src="<?= $event['image'] ?: 'assets/images/events/default.png' ?>"
                                alt="<?= htmlspecialchars($event['title']) ?>">
                            <div class="event-info">
                                <h4><?= htmlspecialchars($event['title']) ?></h4>
                                <small>📅 <?= formatDateIndonesia($event['start_time']) ?></small>
                                <small>📍 <?= htmlspecialchars($event['location']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="event-item">
                        <div class="event-info">
                            <h4>Tidak ada event lainnya</h4>
                            <small>Pantau terus untuk update terbaru!</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>