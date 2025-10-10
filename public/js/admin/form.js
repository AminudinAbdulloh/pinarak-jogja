function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('imagePreview').innerHTML =
                '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 200px; object-fit: cover;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetForm(formId, imagePreviewText) {
    document.getElementById(formId).reset();
    document.getElementById('imagePreview').innerHTML =
        imagePreviewText;
}

function socialMediaUrlValidation() {
    document.getElementById('addProfileForm').addEventListener('submit', function (e) {
        const name = document.getElementById('name').value.trim();
        const position = document.getElementById('position').value.trim();

        // Validate URL formats for social media
        const linkedin = document.getElementById('linkedin').value.trim();
        const instagram = document.getElementById('instagram').value.trim();
        const facebook = document.getElementById('facebook').value.trim();

        const urlRegex = /^https?:\/\/.+/;

        if (linkedin && !urlRegex.test(linkedin)) {
            e.preventDefault();
            alert('URL LinkedIn tidak valid! Harus dimulai dengan http:// atau https://');
            return false;
        }

        if (instagram && !urlRegex.test(instagram)) {
            e.preventDefault();
            alert('URL Instagram tidak valid! Harus dimulai dengan http:// atau https://');
            return false;
        }

        if (facebook && !urlRegex.test(facebook)) {
            e.preventDefault();
            alert('URL Facebook tidak valid! Harus dimulai dengan http:// atau https://');
            return false;
        }

        return true;
    });
}

// Validate YouTube Embed URL and show preview
function validateYouTubeEmbedURL(input) {
    const url = input.value;
    const preview = document.getElementById('videoPreview');

    // YouTube Embed URL regex
    const youtubeEmbedRegex = /(?:youtube\.com\/embed\/)([^"&?\/\s]{11})/;
    const match = url.match(youtubeEmbedRegex);

    if (match && match[1]) {
        const videoId = match[1];
        input.classList.remove('error');
        input.classList.add('success');

        // Show video preview using the embed URL directly
        preview.innerHTML = `
                <div style="margin-top: 10px;">
                    <strong>Preview Video Baru:</strong>
                    <br>
                    <iframe width="400" height="225" 
                            src="${url}" 
                            frameborder="0" 
                            allowfullscreen>
                    </iframe>
                </div>
            `;

        // Auto-fill title if empty
        const titleInput = document.getElementById('video_title');
        if (!titleInput.value.trim()) {
            fetchVideoTitle(videoId);
        }
    } else if (url.trim()) {
        input.classList.remove('success');
        input.classList.add('error');
        preview.innerHTML = '<div style="color: #dc3545; font-size: 14px; margin-top: 10px;">URL YouTube Embed tidak valid. Gunakan format: https://www.youtube.com/embed/VIDEO_ID</div>';
    } else {
        input.classList.remove('success', 'error');
        preview.innerHTML = '';
    }
}