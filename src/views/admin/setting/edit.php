<div class="content-section" id="settings-edit">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Pengaturan Website</h1>
        <p>Perbarui logo, banner, dan pengaturan umum website</p>
    </div>

    <div class="content-area">
        <form id="settingsForm" method="POST" action="<?= BASEURL . '/admin/setting/edit_setting' ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($settings['id']) ?>">

            <!-- Logo Section -->
            <div class="content-header">
                <h3><i class="fas fa-image"></i> Logo Website</h3>
            </div>
            <div class="content-body">
                <div class="form-row-dual">
                    <!-- Logo Pinarak -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-building"></i>
                            Logo Pinarak
                        </label>
                        <div class="image-section">
                            <div class="image-upload-area">
                                <input type="file" id="logo_pinarak" name="logo_pinarak" accept="image/*" style="display: none;">
                                <div class="photo-preview <?= !empty($settings['logo_pinarak']) ? 'has-image' : '' ?>" 
                                     onclick="document.getElementById('logo_pinarak').click()">
                                    <?php if (!empty($settings['logo_pinarak'])): ?>
                                        <img src="<?= BASEURL . '/' . htmlspecialchars($settings['logo_pinarak']) ?>" 
                                             alt="Logo Pinarak" id="preview_pinarak">
                                    <?php else: ?>
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Klik untuk upload</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <small class="form-text">Format: JPG, PNG (Maks. 2MB)</small>
                    </div>

                    <!-- Logo Dinas Pariwisata Kota Yogyakarta -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-landmark"></i>
                            Logo Dinas Pariwisata Kota Yogyakarta
                        </label>
                        <div class="image-section">
                            <div class="image-upload-area">
                                <input type="file" id="logo_dinpar" name="logo_dinpar" accept="image/*" style="display: none;">
                                <div class="photo-preview <?= !empty($settings['logo_dinpar']) ? 'has-image' : '' ?>" 
                                     onclick="document.getElementById('logo_dinpar').click()">
                                    <?php if (!empty($settings['logo_dinpar'])): ?>
                                        <img src="<?= BASEURL . '/' . htmlspecialchars($settings['logo_dinpar']) ?>" 
                                             alt="Logo Dinpar" id="preview_dinpar">
                                    <?php else: ?>
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Klik untuk upload</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <small class="form-text">Format: JPG, PNG (Maks. 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- Banner Section -->
            <div class="content-header" style="margin-top: 30px;">
                <h3><i class="fas fa-images"></i> Banner Website</h3>
            </div>
            <div class="content-body">
                <!-- Existing Banners -->
                <?php if (!empty($settings['banner']) && is_array($settings['banner'])): ?>
                <div class="existing-banners">
                    <label><i class="fas fa-image"></i> Banner yang Ada</label>
                    <div class="banner-list">
                        <?php foreach ($settings['banner'] as $index => $banner): ?>
                        <div class="banner-item-edit">
                            <img src="<?= BASEURL . '/' . htmlspecialchars($banner) ?>" alt="Banner <?= $index + 1 ?>">
                            <div class="banner-overlay">
                                <span class="banner-number"><?= $index + 1 ?></span>
                                <label class="delete-checkbox">
                                    <input type="checkbox" name="delete_banners[]" value="<?= htmlspecialchars($banner) ?>">
                                    <span>Hapus</span>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <small class="form-text">Centang banner yang ingin dihapus</small>
                </div>
                <?php endif; ?>

                <!-- Upload New Banners -->
                <div class="form-group" style="margin-top: 20px;">
                    <label>
                        <i class="fas fa-plus-circle"></i>
                        Tambah Banner Baru
                    </label>
                    <div class="image-section">
                        <div class="image-upload-area">
                            <input type="file" id="banners" name="banners[]" accept="image/*" multiple style="display: none;">
                            <div class="image-preview" onclick="document.getElementById('banners').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Klik untuk upload banner</p>
                                <small>Bisa pilih beberapa file sekaligus</small>
                            </div>
                        </div>
                        <div id="banner-previews" class="banner-previews"></div>
                    </div>
                    <small class="form-text">Format: JPG, PNG (Maks. 5MB per file) - Bisa upload multiple</small>
                </div>
            </div>

            <!-- Copyright Section -->
            <div class="content-header" style="margin-top: 30px;">
                <h3><i class="fas fa-copyright"></i> Copyright</h3>
            </div>
            <div class="content-body">
                <div class="form-group">
                    <label for="copyright">
                        <i class="fas fa-text-width"></i>
                        Teks Copyright
                    </label>
                    <input type="text" class="form-control" id="copyright" name="copyright" 
                           value="<?= htmlspecialchars($settings['copyright'] ?? '© 2025 Pinarak Jogja') ?>"
                           placeholder="Contoh: © 2025 Pinarak Jogja" required>
                    <small class="form-text">Teks copyright yang ditampilkan di footer website</small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn btn-info" onclick="previewSettings()">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <a href="<?= BASEURL . '/admin/setting' ?>" class="btn btn-danger">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="modal">
    <div class="modal-content" style="max-width: 1000px;">
        <span class="close">&times;</span>
        <h2><i class="fas fa-eye"></i> Preview Pengaturan</h2>
        <div id="previewContent">
            <!-- Preview content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Preview Logo Pinarak
document.getElementById('logo_pinarak').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('#preview_pinarak');
            const container = preview ? preview.parentElement : document.querySelector('.photo-preview');
            
            if (preview) {
                preview.src = e.target.result;
            } else {
                container.innerHTML = `<img src="${e.target.result}" alt="Logo Pinarak" id="preview_pinarak">`;
                container.classList.add('has-image');
            }
        }
        reader.readAsDataURL(file);
    }
});

// Preview Logo Dinpar
document.getElementById('logo_dinpar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('#preview_dinpar');
            const container = preview ? preview.parentElement : document.querySelectorAll('.photo-preview')[1];
            
            if (preview) {
                preview.src = e.target.result;
            } else {
                container.innerHTML = `<img src="${e.target.result}" alt="Logo Dinpar" id="preview_dinpar">`;
                container.classList.add('has-image');
            }
        }
        reader.readAsDataURL(file);
    }
});

// Preview Multiple Banners
document.getElementById('banners').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('banner-previews');
    previewContainer.innerHTML = '';
    
    if (files.length > 0) {
        previewContainer.style.display = 'grid';
        
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const bannerDiv = document.createElement('div');
                bannerDiv.className = 'banner-preview-item';
                bannerDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Banner Baru ${index + 1}">
                    <div class="banner-preview-overlay">
                        <span class="badge badge-success">Baru ${index + 1}</span>
                    </div>
                `;
                previewContainer.appendChild(bannerDiv);
            }
            reader.readAsDataURL(file);
        });
    }
});

// Preview Settings
function previewSettings() {
    const formData = new FormData(document.getElementById('settingsForm'));
    
    // Get logo previews
    const logoPinarakSrc = document.querySelector('#preview_pinarak')?.src || '<?= BASEURL ?>/uploads/default-logo.png';
    const logoDinparSrc = document.querySelector('#preview_dinpar')?.src || '<?= BASEURL ?>/uploads/default-logo.png';
    
    // Get existing banners
    let bannersHTML = '';
    const existingBanners = document.querySelectorAll('.banner-item-edit');
    const deletedBanners = Array.from(document.querySelectorAll('input[name="delete_banners[]"]:checked')).map(cb => cb.value);
    
    existingBanners.forEach((banner, index) => {
        const img = banner.querySelector('img');
        const bannerPath = img.src;
        const isDeleted = deletedBanners.some(deleted => bannerPath.includes(deleted));
        
        if (!isDeleted) {
            bannersHTML += `
                <div class="preview-banner-item">
                    <img src="${bannerPath}" alt="Banner ${index + 1}">
                    <span class="preview-badge">Banner ${index + 1}</span>
                </div>
            `;
        }
    });
    
    // Get new banners
    const newBannerPreviews = document.querySelectorAll('.banner-preview-item img');
    newBannerPreviews.forEach((img, index) => {
        bannersHTML += `
            <div class="preview-banner-item new">
                <img src="${img.src}" alt="Banner Baru ${index + 1}">
                <span class="preview-badge badge-success">Baru ${index + 1}</span>
            </div>
        `;
    });
    
    const previewHTML = `
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <!-- Logo Preview -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d5016; margin-bottom: 15px;"><i class="fas fa-image"></i> Logo Website</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="text-align: center; background: white; padding: 20px; border-radius: 8px;">
                        <h4 style="color: #4a7c22; margin-bottom: 10px;">Logo Pinarak</h4>
                        <img src="${logoPinarakSrc}" alt="Logo Pinarak" style="max-width: 200px; max-height: 150px; object-fit: contain;">
                    </div>
                    <div style="text-align: center; background: white; padding: 20px; border-radius: 8px;">
                        <h4 style="color: #4a7c22; margin-bottom: 10px;">Logo Dinpar</h4>
                        <img src="${logoDinparSrc}" alt="Logo Dinpar" style="max-width: 200px; max-height: 150px; object-fit: contain;">
                    </div>
                </div>
            </div>
            
            <!-- Banner Preview -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d5016; margin-bottom: 15px;"><i class="fas fa-images"></i> Banner Website</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                    ${bannersHTML || '<p style="text-align: center; color: #999; grid-column: 1/-1;">Tidak ada banner</p>'}
                </div>
            </div>
            
            <!-- Copyright Preview -->
            <div>
                <h3 style="color: #2d5016; margin-bottom: 15px;"><i class="fas fa-copyright"></i> Copyright</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #4a7c22;">
                    <p style="margin: 0; font-size: 16px; color: #333;">${formData.get('copyright')}</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    openModal('previewModal');
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Close modal when clicking X
document.querySelectorAll('.close').forEach(closeBtn => {
    closeBtn.onclick = function() {
        this.closest('.modal').style.display = 'none';
    }
});

// Form validation
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const copyright = document.getElementById('copyright').value.trim();
    
    if (!copyright) {
        e.preventDefault();
        alert('Teks copyright harus diisi!');
        return false;
    }
    
    // Validasi ukuran file
    const logoPinarak = document.getElementById('logo_pinarak').files[0];
    const logoDinpar = document.getElementById('logo_dinpar').files[0];
    const banners = document.getElementById('banners').files;
    
    if (logoPinarak && logoPinarak.size > 2 * 1024 * 1024) {
        e.preventDefault();
        alert('Ukuran Logo Pinarak terlalu besar! Maksimal 2MB');
        return false;
    }
    
    if (logoDinpar && logoDinpar.size > 2 * 1024 * 1024) {
        e.preventDefault();
        alert('Ukuran Logo Dinpar terlalu besar! Maksimal 2MB');
        return false;
    }
    
    for (let i = 0; i < banners.length; i++) {
        if (banners[i].size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('Ukuran banner terlalu besar! Maksimal 5MB per file');
            return false;
        }
    }
});
</script>

<style>
.existing-banners {
    margin-bottom: 20px;
}

.existing-banners label {
    display: block;
    margin-bottom: 15px;
    color: #2d5016;
    font-weight: 600;
}

.banner-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 10px;
}

.banner-item-edit {
    position: relative;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.banner-item-edit:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.banner-item-edit img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-item-edit:hover .banner-overlay {
    opacity: 1;
}

.banner-number {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(74, 124, 34, 0.9);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.delete-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    background: rgba(220, 53, 69, 0.9);
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}

.delete-checkbox:hover {
    background: rgba(220, 53, 69, 1);
}

.delete-checkbox input[type="checkbox"] {
    cursor: pointer;
}

.banner-previews {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.banner-preview-item {
    position: relative;
    border: 2px solid #28a745;
    border-radius: 10px;
    overflow: hidden;
}

.banner-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.banner-preview-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

.preview-banner-item {
    position: relative;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

.preview-banner-item.new {
    border-color: #28a745;
}

.preview-banner-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.preview-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(74, 124, 34, 0.9);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.preview-badge.badge-success {
    background: rgba(40, 167, 69, 0.9);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 3% auto;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 1000px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 20px;
}

.close:hover,
.close:focus {
    color: #000;
}

@media (max-width: 768px) {
    .banner-list,
    .banner-previews {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        margin: 5% auto;
        width: 95%;
        padding: 20px;
    }
}
</style>