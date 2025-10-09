<div class="content-section" id="add-event">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Event Baru</h1>
        <p>Buat event atau acara baru untuk dipublikasikan</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Event</h3>
            <a href="<?= BASEURL . '/admin/event' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Event
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/event/add_event' ?>" enctype="multipart/form-data" id="addEventForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Judul Event *
                        </label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="Masukkan judul event"
                            value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Deskripsi Event *
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="5" required
                            placeholder="Masukkan deskripsi lengkap event"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">
                            <i class="fas fa-calendar"></i> Tanggal dan Waktu Event *
                        </label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" required
                            value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">
                            <i class="fas fa-map-marker-alt"></i> Lokasi Event *
                        </label>
                        <input type="text" class="form-control" id="location" name="location" required
                            placeholder="Masukkan lokasi event"
                            value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="image-section">
                        <div class="form-group">
                            <label for="event_image">
                                <i class="fas fa-image"></i> Gambar Utama *
                            </label>
                            <div class="image-upload-area">
                                <div class="image-preview" id="imagePreview" onclick="document.getElementById('event_image').click()">
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>Gambar utama event</small>
                                </div>
                                <input type="file" class="form-control" id="event_image" name="event_image"
                                    accept="image/*" onchange="previewImage(this)" style="display: none;">
                            </div>
                            <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px.</small>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-toggle-on"></i> Status Event
                        </label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Event
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm('addEventForm', '<i class=\'fas fa-image\'></i><p>Klik untuk upload gambar</p><small>Gambar utama event</small>')">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/event' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>