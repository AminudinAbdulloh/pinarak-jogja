<div class="contact-container">
    <h1>CONTACT</h1>

    <!-- Map Section -->
    <div class="map-section">
        <iframe
            src="<?= $contact['gmaps_embed_url'] ?>"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <!-- Contact Content -->
    <div class="contact-content">
        <!-- Contact Information -->
        <div class="contact-info">
            <h2>Informasi Kontak</h2>

            <div class="info-item-contact">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Alamat</h3>
                    <p><?php echo htmlspecialchars($contact['address']) ?><br><?php echo htmlspecialchars($contact['postal_code']) ?></p>
                </div>
            </div>

            <div class="info-item-contact">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h3>Hubungi Kami</h3>
                    <p><?php echo htmlspecialchars($contact['phone_number']) ?></p>
                </div>
            </div>

            <div class="info-item-contact">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email Kami</h3>
                    <p><?php echo htmlspecialchars($contact['email']) ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h2>Kirim Pesan</h2>

            <form id="contactForm" method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required
                        placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required
                        placeholder="Masukkan email Anda">
                </div>

                <div class="form-group">
                    <label for="subject">Subjek <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" required
                        placeholder="Masukkan subject pesan">
                </div>

                <div class="form-group">
                    <label for="message">Pesan <span class="required">*</span></label>
                    <textarea id="message" name="message" required
                        placeholder="Tulis pesan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    // Compose email body
    const emailBody = `Nama: ${name}%0D%0A` +
                     `Email: ${email}%0D%0A%0D%0A` +
                     `Pesan:%0D%0A${message}`;
    
    // Create mailto link
    const mailtoLink = `mailto:<?= htmlspecialchars($contact['email']) ?>?subject=${encodeURIComponent(subject)}&body=${emailBody}`;
    
    // Open default email client
    window.location.href = mailtoLink;
    
    // Show success message
    alert('Email client Anda akan terbuka. Silakan kirim email dari aplikasi email Anda.');
    
    // Optional: Reset form after a delay
    setTimeout(() => {
        this.reset();
    }, 1000);
});
</script>

<style>
.required {
    color: #dc3545;
}
</style>