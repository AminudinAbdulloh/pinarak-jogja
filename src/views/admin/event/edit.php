<div class="content-section" id="edit-event">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Event</h1>
        <p>Perbarui event yang sudah ada</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Event</h3>
            <a href="<?= BASEURL . '/admin/event' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Event
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/event/edit_event' ?>" enctype="multipart/form-data" id="editEventForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                
                <!-- Image Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="event_image">
                            <i class="fas fa-image"></i> Gambar Utama
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview <?php echo $event['image'] ? 'has-image' : ''; ?>" id="imagePreview" onclick="document.getElementById('event_image').click()">
                                <?php if ($event['image']): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($event['image']); ?> "
                                        alt="Current Image" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>Gambar utama event</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" id="event_image" name="event_image"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px. Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> Judul Event *
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required
                        placeholder="Masukkan judul event"
                        value="<?php echo htmlspecialchars($event['title']); ?>">
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Deskripsi Event *
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="5" required
                        placeholder="Masukkan deskripsi lengkap event"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <!-- Event Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">
                            <i class="fas fa-calendar"></i> Tanggal dan Waktu Event *
                        </label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" required
                            value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_time'])); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">
                        <i class="fas fa-map-marker-alt"></i> Lokasi Event *
                    </label>
                    <input type="text" class="form-control" id="location" name="location" required
                        placeholder="Masukkan lokasi event"
                        value="<?php echo htmlspecialchars($event['location']); ?>">
                </div>

                <!-- Publishing Settings -->
                <div class="publishing-section">
                    <h4><i class="fas fa-calendar-alt"></i> Pengaturan Publikasi</h4>
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-flag"></i> Status Event
                        </label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft" <?php echo ($event['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($event['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui Event
                    </button>
                    <a href="<?= BASEURL . '/admin/event' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>