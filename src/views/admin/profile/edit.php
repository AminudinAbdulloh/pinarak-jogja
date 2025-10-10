<div class="content-section" id="edit-profile">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Profile Tim</h1>
        <p>Perbarui informasi anggota tim dalam profile perusahaan</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Profile Tim</h3>
            <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Profile
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/profile/edit_profile' ?>" enctype="multipart/form-data" id="editProfileForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($profile['id']); ?>">
                <!-- Photo Upload Section -->
                <div class="photo-section">
                    <div class="form-group">
                        <label for="pass_photo">
                            <i class="fas fa-camera"></i> Foto Profile
                        </label>
                        <div class="photo-upload-area">
                            <div class="image-preview <?= !empty($profile['pass_photo']) ? 'has-image' : '' ?>" id="imagePreview" onclick="document.getElementById('pass_photo').click()">
                                <?php if (!empty($profile['pass_photo'])): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($profile['pass_photo']) ?>" alt="Current Profile Photo">
                                <?php else: ?>
                                    <i class="fas fa-user-circle"></i>
                                    <p>Klik untuk upload foto</p>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" id="pass_photo" name="pass_photo"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Disarankan ukuran 300x300px. Kosongkan jika tidak ingin mengganti foto.</small>
                    </div>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Nama Lengkap *
                        </label>
                        <input type="text" class="form-control" id="name" name="full_name" required
                            placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($profile['full_name']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="position">
                            <i class="fas fa-briefcase"></i> Jabatan *
                        </label>
                        <input type="text" class="form-control" id="position" name="position" required
                            placeholder="Contoh: CEO, CTO, Manager, dll" value="<?= htmlspecialchars($profile['position']) ?>">
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
                                placeholder="https://linkedin.com/in/username" value="<?= htmlspecialchars($profile['linkedin']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="instagram">
                                <i class="fab fa-instagram"></i> Instagram
                            </label>
                            <input type="url" class="form-control" id="instagram" name="instagram" onchange="socialMediaUrlValidation()"
                                placeholder="https://instagram.com/username" value="<?= htmlspecialchars($profile['instagram']) ?>">
                        </div>
                    </div>
                    <div class="form-row-dual">
                        <div class="form-group">
                            <label for="facebook">
                                <i class="fab fa-facebook"></i> Facebook
                            </label>
                            <input type="url" class="form-control" id="facebook" name="facebook" onchange="socialMediaUrlValidation()"
                                placeholder="https://facebook.com/username" value="<?= htmlspecialchars($profile['facebook']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="tiktok">
                                <i class="fab fa-tiktok"></i> TikTok
                            </label>
                            <input type="url" class="form-control" id="tiktok" name="tiktok" onchange="socialMediaUrlValidation()"
                                placeholder="https://tiktok.com/@username" value="<?= htmlspecialchars($profile['tiktok']) ?>">
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
                                <option value="active" <?= $profile['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= $profile['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui Profile
                    </button>
                    <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>