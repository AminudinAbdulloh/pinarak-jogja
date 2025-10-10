<div class="content-section" id="add-youtube-link">
    <div class="page-header">
        <h1><i class="fas fa-youtube"></i> Tambah Link YouTube</h1>
        <p>Tambahkan link embed video YouTube untuk ditampilkan di website</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Link YouTube</h3>
            <a href="<?= BASEURL . '/admin/profile/' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Profile
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/profile/youtube_link/added_youtube_link' ?>" id="addYoutubeLinkForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="video_title">
                            <i class="fas fa-heading"></i> Judul Video *
                        </label>
                        <input type="text" class="form-control" id="video_title" name="title" required
                            value="<?= htmlspecialchars($video_title ?? '') ?>"
                            placeholder="Masukkan judul video YouTube">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="youtube_url">
                            <i class="fas fa-link"></i> URL YouTube Embed *
                        </label>
                        <input type="url" class="form-control" id="youtube_url" name="url" required
                            value="<?= htmlspecialchars($youtube_url ?? '') ?>"
                            placeholder="https://www.youtube.com/embed/VIDEO_ID"
                            onchange="validateYouTubeEmbedURL(this)">
                        <small class="form-text">
                            Format yang didukung:
                            <br>• https://www.youtube.com/embed/VIDEO_ID
                            <br>• Contoh: https://www.youtube.com/embed/dQw4w9WgXcQ
                        </small>
                        <div id="videoPreview" class="video-preview"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Link YouTube
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/profile/' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>