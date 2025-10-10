<div class="content-section" id="add-profile">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Tambah Profile Tim</h1>
        <p>Tambahkan anggota tim baru ke dalam profile perusahaan</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Profile Tim</h3>
            <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Profile
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/profile/add_profile' ?>" enctype="multipart/form-data" id="addProfileForm">
                <!-- Photo Upload Section -->
                <div class="photo-section">
                    <div class="form-group">
                        <label for="pass_photo">
                            <i class="fas fa-camera"></i> Foto Profile *
                        </label>
                        <div class="photo-upload-area">
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('pass_photo').click()">
                                <i class="fas fa-user-circle"></i>
                                <p>Klik untuk upload foto</p>
                            </div>
                            <input type="file" class="form-control" id="pass_photo" name="pass_photo"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Disarankan ukuran 300x300px.</small>
                    </div>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Nama Lengkap *
                        </label>
                        <input type="text" class="form-control" id="name" name="full_name" required
                            placeholder="Masukkan nama lengkap" value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="position">
                            <i class="fas fa-briefcase"></i> Jabatan *
                        </label>
                        <input type="text" class="form-control" id="position" name="position" required
                            placeholder="Contoh: CEO, CTO, Manager, dll" value="<?= isset($_POST['position']) ? htmlspecialchars($_POST['position']) : '' ?>">
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="social-media-section">
                    <h4><i class="fas fa-share-alt"></i> Media Sosial (Opsional)</h4>
                    <div class="form-row-dual">
                        <div class="form-group">
                            <label for="linkedin">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </label>
                            <input type="url" class="form-control" id="linkedin" name="linkedin" onchange="socialMediaUrlValidation()"
                                placeholder="https://linkedin.com/in/username" value="<?= isset($_POST['linkedin']) ? htmlspecialchars($_POST['linkedin']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="instagram">
                                <i class="fab fa-instagram"></i> Instagram
                            </label>
                            <input type="url" class="form-control" id="instagram" name="instagram" onchange="socialMediaUrlValidation()"
                                placeholder="https://instagram.com/username" value="<?= isset($_POST['instagram']) ? htmlspecialchars($_POST['instagram']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-row-dual">
                        <div class="form-group">
                            <label for="facebook">
                                <i class="fab fa-facebook"></i> Facebook
                            </label>
                            <input type="url" class="form-control" id="facebook" name="facebook" onchange="socialMediaUrlValidation()"
                                placeholder="https://facebook.com/username" value="<?= isset($_POST['facebook']) ? htmlspecialchars($_POST['facebook']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="tiktok">
                                <i class="fab fa-tiktok"></i> TikTok
                            </label>
                            <input type="url" class="form-control" id="tiktok" name="tiktok" onchange="socialMediaUrlValidation()"
                                placeholder="https://tiktok.com/@username" value="<?= isset($_POST['tiktok']) ? htmlspecialchars($_POST['tiktok']) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="settings-section">
                    <h4><i class="fas fa-cog"></i> Pengaturan</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?= (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Profile
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm('addProfileForm', '<i class=\'fas fa-user-circle\'></i><p>Klik untuk upload foto</p>')">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>