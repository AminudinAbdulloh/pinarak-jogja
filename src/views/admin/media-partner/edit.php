<div class="content-section" id="add-media-partner">
    <div class="page-header">
        <h1><i class="fas fa-handshake"></i> Edit Media Partner</h1>
        <p>Perbarui media partner yang sudah ada</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Media Partner</h3>
            <a href="<?= BASEURL . '/admin/media-partner' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Media Partner
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/media-partner/edit_media_partner' ?>" enctype="multipart/form-data" id="addMediaPartnerForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($media_partner['id']); ?>">

                <!-- Logo Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="logo">
                            <i class="fas fa-image"></i> Logo Media Partner *
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('logo').click()">
                                <?php if ($media_partner['logo']): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($media_partner['logo']); ?> "
                                        alt="Current Image" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload logo</p>
                                    <small>Logo media partner</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" id="logo" name="logo"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, WebP, SVG. Maksimal 5MB. Disarankan ukuran square (1:1).</small>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-building"></i> Nama Media Partner *
                        </label>
                        <input type="text" class="form-control" id="name" name="name" required
                            placeholder="Masukkan nama media partner"
                            value="<?php echo htmlspecialchars($media_partner['name']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Deskripsi singkat tentang media partner atau jenis kerjasama..."><?php echo htmlspecialchars($media_partner['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="website">
                        <i class="fas fa-globe"></i> Website
                    </label>
                    <input type="url" class="form-control" id="website" name="website"
                        placeholder="https://website-mediapartner.com"
                        value="<?php echo htmlspecialchars($media_partner['website']); ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Media Partner
                    </button>
                    <a href="<?= BASEURL . '/admin/media-partner' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>