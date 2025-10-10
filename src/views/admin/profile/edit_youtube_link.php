<div class="content-section" id="edit-youtube-link">
    <div class="page-header">
        <h1><i class="fas fa-youtube"></i> Edit Link YouTube</h1>
        <p>Edit link embed video YouTube yang sudah ada</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Link YouTube</h3>
            <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Profile
            </a>
        </div>

        <div class="content-body">
            <form method="POST" action="<?= BASEURL . '/admin/profile/youtube_link/edited_youtube_link' ?>" id="editYoutubeLinkForm">
                <input type="hidden" name="id" value="<?= htmlspecialchars($youtube_link['id']) ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Judul Video *
                        </label>
                        <input type="text" class="form-control" id="video_title" name="title" required
                            value="<?= htmlspecialchars($youtube_link['title']) ?>"
                            placeholder="Masukkan judul video YouTube">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="url">
                            <i class="fas fa-link"></i> URL YouTube Embed *
                        </label>
                        <input type="url" class="form-control" id="youtube_url" name="url" required
                            value="<?= htmlspecialchars($youtube_link['url']) ?>"
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
                        <i class="fas fa-save"></i> Update Link YouTube
                    </button>
                    <a href="<?= BASEURL . '/admin/profile/' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>