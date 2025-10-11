<div class="content-section" id="add-media-partner">
    <div class="page-header">
        <h1><i class="fas fa-handshake"></i> Tambah Media Partner</h1>
        <p>Tambahkan media partner atau sponsor baru</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Media Partner</h3>
            <a href="<?= BASEURL . '/admin/media-partner' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Media Partner
            </a>
        </div>

        <div class="content-body">
            <form method="POST" enctype="multipart/form-data" id="addMediaPartnerForm">
                <!-- Logo Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="logo">
                            <i class="fas fa-image"></i> Logo Media Partner *
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('logo').click()">
                                <i class="fas fa-image"></i>
                                <p>Klik untuk upload logo</p>
                                <small>Logo media partner</small>
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
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Deskripsi singkat tentang media partner atau jenis kerjasama..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="website">
                        <i class="fas fa-globe"></i> Website
                    </label>
                    <input type="url" class="form-control" id="website" name="website"
                        placeholder="https://website-mediapartner.com"
                        value="<?php echo isset($_POST['website']) ? htmlspecialchars($_POST['website']) : ''; ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Media Partner
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm('addMediaPartnerForm', '<i class=\'fas fa-image\'></i><p>Klik untuk upload logo</p><small>Logo media partner</small>')">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/media-partner' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>