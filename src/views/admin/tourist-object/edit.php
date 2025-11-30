<div class="content-section" id="edit-tourist-object">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Objek Wisata</h1>
        <p>Perbarui informasi destinasi wisata</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Objek Wisata</h3>
            <a href="<?= BASEURL . '/admin/tourist-object' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/tourist-object/edit_tourist_object' ?>" enctype="multipart/form-data" id="editTouristObjectForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($touristObject['id']); ?>">
                
                <!-- Image Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="object_image">
                            <i class="fas fa-image"></i> Gambar Objek Wisata
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview <?php echo $touristObject['image'] ? 'has-image' : ''; ?>" id="imagePreview" onclick="document.getElementById('object_image').click()">
                                <?php if ($touristObject['image']): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($touristObject['image']); ?>"
                                        alt="Current Image" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>Foto destinasi wisata</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" id="object_image" name="object_image"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px. Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> Nama Objek Wisata *
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required 
                        placeholder="Masukkan nama destinasi wisata"
                        value="<?php echo htmlspecialchars($touristObject['title']); ?>">
                </div>

                <!-- Category Selection -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-tag"></i> Kategori *
                        </label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Nature" <?php echo ($touristObject['category'] == 'Nature') ? 'selected' : ''; ?>>🌲 Alam</option>
                            <option value="Culture" <?php echo ($touristObject['category'] == 'Culture') ? 'selected' : ''; ?>>🎭 Budaya</option>
                            <option value="Culinary" <?php echo ($touristObject['category'] == 'Culinary') ? 'selected' : ''; ?>>🍜 Kuliner</option>
                            <option value="Religious" <?php echo ($touristObject['category'] == 'Religious') ? 'selected' : ''; ?>>🛕 Religi</option>
                            <option value="Adventure" <?php echo ($touristObject['category'] == 'Adventure') ? 'selected' : ''; ?>>⛰️ Petualangan</option>
                            <option value="Historical" <?php echo ($touristObject['category'] == 'Historical') ? 'selected' : ''; ?>>🏛️ Sejarah</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-map-marker-alt"></i> Alamat Lengkap *
                    </label>
                    <input type="text" class="form-control" id="address" name="address" required 
                        placeholder="Masukkan alamat lengkap objek wisata"
                        value="<?php echo htmlspecialchars($touristObject['address']); ?>">
                </div>

                <!-- Google Maps Link -->
                <div class="form-group">
                    <label for="google_map_link">
                        <i class="fas fa-map"></i> Link Google Maps *
                    </label>
                    <input type="url" class="form-control" id="google_map_link" name="google_map_link" required 
                        placeholder="https://maps.google.com/..."
                        value="<?php echo htmlspecialchars($touristObject['google_map_link']); ?>">
                    <small class="form-text">Salin tautan embed dari Google Maps untuk menampilkan lokasi di peta.</small>
                </div>

                <!-- Article Content -->
                <div class="content-section-form">
                    <h4><i class="fas fa-edit"></i> Konten Artikel</h4>
                    <div class="form-group">
                        <label for="content">
                            <i class="fas fa-file-alt"></i> Isi Artikel *
                        </label>
                        <div class="editor-toolbar">
                            <button type="button" class="editor-btn" data-command="bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" class="editor-btn" data-command="italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" class="editor-btn" data-command="underline">
                                <i class="fas fa-underline"></i>
                            </button>
                            <div class="editor-separator"></div>
                            <button type="button" class="editor-btn" data-command="insertUnorderedList">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" class="editor-btn" data-command="insertOrderedList">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div class="editor-separator"></div>
                            <button type="button" class="editor-btn" data-command="createLink">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        <div class="editor-content" id="content" contenteditable="true"
                            placeholder="Tulis konten artikel di sini..."><?php echo $informations['article']; ?></div>
                        <textarea name="article" id="contentHidden" style="display: none;"><?php echo htmlspecialchars($touristObject['article']); ?></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui Objek Wisata
                    </button>
                    <a href="<?= BASEURL . '/admin/tourist-object' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>