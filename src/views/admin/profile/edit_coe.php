<div class="content-section" id="edit-coe">
    <div class="page-header">
        <h1><i class="fas fa-calendar"></i> Edit Calendar of Event</h1>
        <p>Edit gambar calendar of event yang sudah ada</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Calendar of Event</h3>
            <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Profile
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/profile/coe/edited_coe' ?>" id="editCoeForm" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($coe['id']) ?>">

                <div class="form-row">
                    <div class="image-section">
                        <div class="form-group">
                            <label for="coe_image">
                                <i class="fas fa-image"></i> Gambar *
                            </label>
                            <div class="image-upload-area">
                                <div class="image-preview" id="imagePreview" onclick="document.getElementById('coe_image').click()">
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>Gambar COE</small>
                                </div>
                                <input type="file" class="form-control" id="coe_image" name="coe_image"
                                    accept="image/*" onchange="previewImage(this)" style="display: none;">
                            </div>
                            <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px.</small>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Calendar of Event
                    </button>
                    <a href="<?= BASEURL . '/admin/profile/' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>