function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML =
                '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 200px; object-fit: cover;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}