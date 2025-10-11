<div class="content-section" id="add-article">
    <div class="page-header">
        <h1><i class="fas fa-newspaper"></i> Tambah Artikel</h1>
        <p>Buat artikel baru untuk website</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Artikel</h3>
            <a href="<?= BASEURL . '/admin/article' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Artikel
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/article/edit_article' ?>" enctype="multipart/form-data" id="addArticleForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">

                <!-- Featured Image Upload Section -->
                <div class="image-section">
                    <div class="form-group">
                        <label for="featured_image">
                            <i class="fas fa-image"></i> Gambar Utama
                        </label>
                        <div class="image-upload-area">
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('featured_image').click()">
                                <?php if ($article['image']): ?>
                                    <img src="<?= BASEURL . '/' . htmlspecialchars($article['image']); ?> "
                                        alt="Current Image" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>Gambar utama artikel</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" id="featured_image" name="featured_image"
                                accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </div>
                        <small class="form-text">Format: JPG, PNG, WebP. Maksimal 5MB. Disarankan ukuran 1200x630px.</small>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> Judul Artikel *
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required
                        placeholder="Masukkan judul artikel yang menarik"
                        value="<?php echo htmlspecialchars($article['title']); ?>">
                </div>

                <div class="form-group">
                    <label for="excerpt">
                        <i class="fas fa-align-left"></i> Ringkasan Artikel
                    </label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                        placeholder="Ringkasan singkat artikel (opsional)"><?php echo htmlspecialchars($article['excerpt']); ?></textarea>
                </div>

                <!-- Content Section -->
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
                            placeholder="Tulis konten artikel di sini..."><?php echo htmlspecialchars($article['content']); ?></div>
                        <textarea name="content" id="contentHidden" style="display: none;"><?php echo htmlspecialchars($article['content']); ?></textarea>
                    </div>
                </div>

                <!-- Publishing Settings -->
                <div class="publishing-section">
                    <h4><i class="fas fa-calendar-alt"></i> Pengaturan Publikasi</h4>
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-flag"></i> Status Artikel
                        </label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : 'selected'; ?>>Draft</option>
                            <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Artikel
                    </button>
                    <a href="<?= BASEURL . '/admin/article' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addArticleForm').addEventListener('submit', function() {
    // Ambil konten dari div contenteditable
    const editorContent = document.getElementById('content').innerHTML;
    
    // Simpan ke textarea hidden
    document.getElementById('contentHidden').value = editorContent;
});
</script>