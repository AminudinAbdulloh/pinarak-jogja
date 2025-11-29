<?php 
// Pastikan ada data kontak
$contact = isset($contacts[0]) ? $contacts[0] : null;
?>

<div class="content-section" id="contacts">
    <div class="page-header">
        <h1><i class="fas fa-address-book"></i> Manajemen Kontak</h1>
        <p>Kelola informasi kontak, alamat, dan media sosial perusahaan</p>
    </div>

     <?php if (!empty($success_message)): ?>
        <div class="notification notification-success" id="successNotification">
            <i class="fas fa-check-circle"></i> 
            <span><?= htmlspecialchars($success_message) ?></span>
            <span class="notification-close" onclick="closeNotification(this)">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="notification notification-error" id="errorNotification">
            <i class="fas fa-exclamation-circle"></i> 
            <span><?= htmlspecialchars($error_message) ?></span>
            <span class="notification-close" onclick="closeNotification(this)">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>

    <div class="content-area">
        <div class="content-header">
            <h3><i class="fas fa-eye"></i> Informasi Kontak Perusahaan</h3>
        </div>

        <div class="content-body">
            <!-- Company Information Display -->
            <div class="company-info-display">
                <!-- Main Contact Information -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-building"></i> Informasi Kontak Utama</h4>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-building"></i> Nama Perusahaan
                            </div>
                            <div class="info-value"><?= htmlspecialchars($contact['company_name']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone"></i> Nomor Telepon
                            </div>
                            <div class="info-value">
                                <a href="tel:<?= htmlspecialchars($contact['phone_number']) ?>" class="contact-link">
                                    <?= htmlspecialchars($contact['phone_number']) ?>
                                </a>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i> Email
                            </div>
                            <div class="info-value">
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="contact-link">
                                    <?= htmlspecialchars($contact['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-map-marker-alt"></i> Alamat Perusahaan</h4>
                    </div>
                    <div class="info-grid">
                        <div class="info-item full-width">
                            <div class="info-label">
                                <i class="fas fa-home"></i> Alamat Lengkap
                            </div>
                            <div class="info-value">
                                <?= nl2br(htmlspecialchars($contact['address'])) ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-city"></i> Kota
                            </div>
                            <div class="info-value"><?= htmlspecialchars($contact['city']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-mail-bulk"></i> Kode Pos
                            </div>
                            <div class="info-value"><?= htmlspecialchars($contact['postal_code']) ?></div>
                        </div>
                    </div>

                    <!-- Google Maps -->
                    <?php if (!empty($contact['gmaps_embed_url'])): ?>
                    <div class="maps-container">
                        <h5><i class="fas fa-map"></i> Lokasi di Peta</h5>
                        <div class="maps-frame">
                            <iframe src="<?= htmlspecialchars($contact['gmaps_embed_url']) ?>"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Working Hours -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-clock"></i> Jam Kerja</h4>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-week"></i> Hari Kerja
                            </div>
                            <div class="info-value"><?= htmlspecialchars($contact['working_days']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> Jam Kerja
                            </div>
                            <div class="info-value"><?= htmlspecialchars($contact['working_time']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-share-alt"></i> Media Sosial</h4>
                    </div>
                    <div class="social-media-grid">
                        <?php if (!empty($contact['youtube'])): ?>
                        <a href="<?= htmlspecialchars($contact['youtube']) ?>" target="_blank" class="social-link youtube">
                            <i class="fab fa-youtube"></i>
                            <span>YouTube</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['instagram'])): ?>
                        <a href="<?= htmlspecialchars($contact['instagram']) ?>" target="_blank" class="social-link instagram">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['tiktok'])): ?>
                        <a href="<?= htmlspecialchars($contact['tiktok']) ?>" target="_blank" class="social-link tiktok">
                            <i class="fab fa-tiktok"></i>
                            <span>TikTok</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['facebook'])): ?>
                        <a href="<?= htmlspecialchars($contact['facebook']) ?>" target="_blank" class="social-link facebook">
                            <i class="fab fa-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-buttons">
                        <a href="<?= BASEURL . '/admin/contact/edit/' . $contact['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Informasi Kontak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>