<div class="content-section" id="settings">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Pengaturan Website</h1>
        <p>Kelola logo, banner, dan pengaturan umum website</p>
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
            <h3><i class="fas fa-eye"></i> Pengaturan Saat Ini</h3>
        </div>

        <div class="content-body">
            <div class="settings-display">
                <!-- Logo Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-image"></i> Logo Website</h4>
                    </div>
                    <div class="logo-grid">
                        <div class="logo-item">
                            <div class="logo-label">
                                <i class="fas fa-building"></i> Logo Pinarak
                            </div>
                            <div class="logo-preview">
                                <?php if (!empty($settings['logo_pinarak'])): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($settings['logo_pinarak']) ?>" 
                                         alt="Logo Pinarak"
                                         onerror="this.src='<?= BASEURL ?>/uploads/default-logo.png'">
                                <?php else: ?>
                                    <div class="no-logo">
                                        <i class="fas fa-image"></i>
                                        <p>Belum ada logo</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="logo-item">
                            <div class="logo-label">
                                <i class="fas fa-landmark"></i> Logo Dinpar
                            </div>
                            <div class="logo-preview">
                                <?php if (!empty($settings['logo_dinpar'])): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($settings['logo_dinpar']) ?>" 
                                         alt="Logo Dinpar"
                                         onerror="this.src='<?= BASEURL ?>/uploads/default-logo.png'">
                                <?php else: ?>
                                    <div class="no-logo">
                                        <i class="fas fa-image"></i>
                                        <p>Belum ada logo</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-images"></i> Banner Website</h4>
                    </div>
                    <div class="banner-gallery">
                        <?php if (!empty($settings['banner']) && is_array($settings['banner'])): ?>
                            <?php foreach ($settings['banner'] as $index => $banner): ?>
                                <div class="banner-item">
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($banner) ?>" 
                                         alt="Banner <?= $index + 1 ?>"
                                         onerror="this.src='<?= BASEURL ?>/uploads/default-banner.jpg'">
                                    <div class="banner-number"><?= $index + 1 ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-banner">
                                <i class="fas fa-images"></i>
                                <p>Belum ada banner</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h4><i class="fas fa-copyright"></i> Copyright</h4>
                    </div>
                    <div class="copyright-display">
                        <p><?= htmlspecialchars($settings['copyright'] ?? '© 2025 Pinarak Jogja') ?></p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-buttons">
                        <a href="<?= BASEURL . '/admin/setting/edit/' . $settings['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeNotification(element) {
    const notification = element.closest('.notification');
    notification.style.animation = 'slideOut 0.3s ease-out';
    setTimeout(() => {
        notification.remove();
    }, 300);
}

// Auto close notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }, 5000);
    });
});
</script>

<style>
.settings-display {
    padding: 20px 0;
}

.info-section {
    margin-bottom: 40px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.section-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.section-header h4 {
    color: #2d5016;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.logo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.logo-item {
    text-align: center;
}

.logo-label {
    font-weight: 600;
    color: #2d5016;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.logo-preview {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-preview img {
    max-width: 100%;
    max-height: 150px;
    object-fit: contain;
}

.no-logo {
    text-align: center;
    color: #999;
}

.no-logo i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 10px;
}

.no-logo p {
    margin: 0;
    font-size: 14px;
}

.banner-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.banner-item {
    position: relative;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.banner-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.banner-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.banner-number {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(74, 124, 34, 0.9);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.no-banner {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    grid-column: 1 / -1;
}

.no-banner i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 15px;
}

.no-banner p {
    margin: 0;
    font-size: 16px;
}

.copyright-display {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #4a7c22;
    margin-top: 15px;
}

.copyright-display p {
    margin: 0;
    font-size: 16px;
    color: #333;
    text-align: center;
}

.quick-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 1001;
    animation: slideInRight 0.5s ease-out;
    max-width: 400px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.notification-success {
    background-color: #28a745;
}

.notification-error {
    background-color: #dc3545;
}

.notification-close {
    margin-left: auto;
    cursor: pointer;
    padding: 5px;
    opacity: 0.8;
    transition: opacity 0.3s;
}

.notification-close:hover {
    opacity: 1;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@media (max-width: 768px) {
    .logo-grid {
        grid-template-columns: 1fr;
    }
    
    .banner-gallery {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
    
    .notification {
        right: 10px;
        left: 10px;
        max-width: none;
    }
}
</style>