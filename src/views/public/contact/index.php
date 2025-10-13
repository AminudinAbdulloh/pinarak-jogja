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
                    <h3>Address</h3>
                    <p><?php echo htmlspecialchars($contact['address']) ?><br><?php echo htmlspecialchars($contact['postal_code']) ?></p>
                </div>
            </div>

            <div class="info-item-contact">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h3>Call Us</h3>
                    <p><?php echo htmlspecialchars($contact['phone_number']) ?></p>
                </div>
            </div>

            <div class="info-item-contact">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email Us</h3>
                    <p><?php echo htmlspecialchars($contact['email']) ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h2>Kirim Pesan</h2>

            <?php if (isset($success_message)): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul style="margin: 5px 0 0 20px; padding: 0;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Your Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required
                        value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                        placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label for="email">Your Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        placeholder="Masukkan email Anda">
                </div>

                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" required
                        value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                        placeholder="Masukkan subject pesan">
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" required
                        placeholder="Tulis pesan Anda di sini..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>