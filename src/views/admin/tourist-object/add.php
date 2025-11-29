<div class="content-section" id="add-tourist-object">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Objek Wisata Baru</h1>
        <p>Tambahkan destinasi wisata baru ke dalam sistem</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Objek Wisata</h3>
            <a href="<?= BASEURL . '/admin/tourist-object' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/tourist-object/add_tourist_object' ?>" enctype="multipart/form-data" id="addTouristObjectForm">
                
                <!-- Image Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="object_image">
                            <i class="fas fa-image"></i> Gambar Objek Wisata *
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('object_image').click()">
                                <i class="fas fa-image"></i>
                                <p>Klik untuk upload gambar</p>
                                <small>Foto destinasi wisata</small>
                            </div>
                            <input type="file" class="form-control" id="object_image" name="object_image"
                                accept="image/*" onchange="previewImage(this)" style="display: none;" required>
                        </div>
                        <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px.</small>
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> Nama Objek Wisata *
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required 
                        placeholder="Masukkan nama destinasi wisata"
                        value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>

                <!-- Category Selection -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-tag"></i> Kategori *
                        </label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Nature" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Nature') ? 'selected' : ''; ?>>🌲 Alam</option>
                            <option value="Culture" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Culture') ? 'selected' : ''; ?>>🎭 Budaya</option>
                            <option value="Culinary" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Culinary') ? 'selected' : ''; ?>>🍜 Kuliner</option>
                            <option value="Religious" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Religious') ? 'selected' : ''; ?>>🛕 Religi</option>
                            <option value="Adventure" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Adventure') ? 'selected' : ''; ?>>⛰️ Petualangan</option>
                            <option value="Historical" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Historical') ? 'selected' : ''; ?>>🏛️ Sejarah</option>
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
                        value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>

                <!-- Google Maps Link -->
                <div class="form-group">
                    <label for="google_map_link">
                        <i class="fas fa-map"></i> Link Google Maps *
                    </label>
                    <input type="url" class="form-control" id="google_map_link" name="google_map_link" required 
                        placeholder="https://maps.google.com/..."
                        value="<?php echo isset($_POST['google_map_link']) ? htmlspecialchars($_POST['google_map_link']) : ''; ?>">
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
                            placeholder="Tulis konten artikel di sini..."><?php echo isset($_POST['article']) ? $_POST['article'] : ''; ?></div>
                        <textarea name="article" id="contentHidden" style="display: none;"><?php echo isset($_POST['article']) ? htmlspecialchars($_POST['article']) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Objek Wisata
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm('addTouristObjectForm', '<i class=\'fas fa-image\'></i><p>Klik untuk upload gambar</p><small>Foto destinasi wisata</small>')">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/tourist-object' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addTouristObjectForm').addEventListener('submit', function(e) {
        const editorContent = document.getElementById('content').innerHTML;
        document.getElementById('contentHidden').value = editorContent;
    });
</script>