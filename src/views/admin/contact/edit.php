<div class="content-section" id="contacts">
    <div class="page-header">
        <h1><i class="fas fa-address-book"></i> Manajemen Kontak</h1>
        <p>Kelola informasi kontak, alamat, dan media sosial perusahaan</p>
    </div>

    <div class="content-area">
        <form id="contactForm" method="POST" action="<?= BASEURL . '/admin/contact/edit_contact' ?>" action="update_contact.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($contact['id']); ?>">

            <!-- Informasi Kontak Utama -->
            <div class="content-header">
                <h3><i class="fas fa-building"></i> Informasi Kontak Utama</h3>
            </div>
            <div class="content-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">
                            <i class="fas fa-building"></i>
                            Nama Perusahaan *
                        </label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required
                            value="<?php echo htmlspecialchars($contact['company_name']); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">
                            <i class="fas fa-phone"></i>
                            Nomor Telepon *
                        </label>
                        <input type="tel" class="form-control" id="phone" name="phone_number" required
                            value="<?php echo htmlspecialchars($contact['phone_number']); ?>">
                        <small class="form-text">Format: +62 xxx xxx xxxx</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="<?php echo htmlspecialchars($contact['email']); ?>">
                    </div>
                </div>
            </div>

            <!-- Alamat Perusahaan -->
            <div class="content-header" style="margin-top: 30px;">
                <h3><i class="fas fa-map-marker-alt"></i> Alamat Perusahaan</h3>
            </div>
            <div class="content-body">
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-home"></i>
                        Alamat Lengkap
                    </label>
                    <textarea class="form-control" id="address" name="address" rows="3" required
                        placeholder="Masukkan alamat lengkap perusahaan"><?php echo htmlspecialchars($contact['address']); ?></textarea>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="city">
                            <i class="fas fa-city"></i>
                            Kota
                        </label>
                        <input type="text" class="form-control" id="city" name="city" required
                            value="<?php echo htmlspecialchars($contact['city']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="postal_code">
                            <i class="fas fa-mail-bulk"></i>
                            Kode Pos
                        </label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" required
                            value="<?php echo htmlspecialchars($contact['postal_code']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gmaps_embed_url">
                        <i class="fas fa-map"></i>
                        Google Maps Embed URL
                    </label>
                    <input type="url" class="form-control" id="maps_embed" name="gmaps_embed_url" placeholder="https://www.google.com/maps/embed?pb=..."
                        value="<?php echo htmlspecialchars($contact['gmaps_embed_url']); ?>">
                    <small class="form-text">Salin URL embed dari Google Maps</small>
                </div>

                <!-- Preview Google Maps -->
                <div class="form-group">
                    <label>Preview Google Maps</label>
                    <div id="maps-preview" style="border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; height: 300px;">
                        <iframe src="<?= htmlspecialchars($contact['gmaps_embed_url']) ?>"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Jam Kerja -->
            <div class="content-header" style="margin-top: 30px;">
                <h3><i class="fas fa-clock"></i> Jam Kerja</h3>
            </div>
            <div class="content-body">
                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="working_days">
                            <i class="fas fa-calendar-week"></i>
                            Hari Kerja
                        </label>
                        <input type="text" class="form-control" id="work_days" name="working_days" required placeholder="Contoh: Senin - Jum'at"
                            value="<?php echo htmlspecialchars($contact['working_days']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="working_time">
                            <i class="fas fa-clock"></i>
                            Jam Kerja
                        </label>
                        <input type="text" class="form-control" id="work_hours" name="working_time" required placeholder="Contoh: 08:00 - 16:00 WIB"
                            value="<?php echo htmlspecialchars($contact['working_time']); ?>">
                    </div>
                </div>
            </div>

            <!-- Media Sosial -->
            <div class="content-header" style="margin-top: 30px;">
                <h3><i class="fas fa-share-alt"></i> Media Sosial</h3>
            </div>
            <div class="content-body">
                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="youtube">
                            <i class="fab fa-youtube"></i>
                            YouTube Channel
                        </label>
                        <input type="url" class="form-control" id="youtube" name="youtube" placeholder="https://youtube.com/@username"
                            value="<?php echo htmlspecialchars($contact['youtube']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="instagram">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </label>
                        <input type="url" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/username"
                            value="<?php echo htmlspecialchars($contact['instagram']); ?>">
                    </div>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="linkedin">
                            <i class="fab fa-linkedin"></i>
                            LinkedIn
                        </label>
                        <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/company/name"
                            value="<?php echo htmlspecialchars($contact['linkedin']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="tiktok">
                            <i class="fab fa-tiktok"></i>
                            TikTok
                        </label>
                        <input type="url" class="form-control" id="tiktok" name="tiktok" placeholder="https://tiktok.com/@username"
                            value="<?php echo htmlspecialchars($contact['tiktok']); ?>">
                    </div>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="facebook">
                            <i class="fab fa-facebook"></i>
                            Facebook
                        </label>
                        <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/page"
                            value="<?php echo htmlspecialchars($contact['facebook']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="twitter">
                            <i class="fab fa-twitter"></i>
                            Twitter/X
                        </label>
                        <input type="url" class="form-control" id="twitter" name="twitter" placeholder="https://x.com/username"
                            value="<?php echo htmlspecialchars($contact['twitter']); ?>">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn btn-info" onclick="previewContact()">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <a href="<?= BASEURL . '/admin/contact' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <span class="close">&times;</span>
        <h2><i class="fas fa-eye"></i> Preview Informasi Kontak</h2>
        <div id="previewContent">
            <!-- Preview content will be loaded here -->
        </div>
    </div>
</div>

<script>
    // Update Google Maps preview when URL changes
    document.getElementById('maps_embed').addEventListener('input', function() {
        const embedUrl = this.value;
        if (embedUrl && embedUrl.includes('google.com/maps/embed')) {
            document.querySelector('#maps-preview iframe').src = embedUrl;
        }
    });

    // Preview contact information
    function previewContact() {
        const formData = new FormData(document.getElementById('contactForm'));
        const data = Object.fromEntries(formData);
        
        const previewHTML = `
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h3 style="color: #2d5016; margin-bottom: 15px;">${data.company_name}</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <h4 style="color: #4a7c22; margin-bottom: 10px;"><i class="fas fa-phone"></i> Kontak</h4>
                        <p><strong>Telepon:</strong> ${data.phone}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                    </div>
                    <div>
                        <h4 style="color: #4a7c22; margin-bottom: 10px;"><i class="fas fa-clock"></i> Jam Kerja</h4>
                        <p><strong>${data.work_days}:</strong> ${data.work_hours}</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #4a7c22; margin-bottom: 10px;"><i class="fas fa-map-marker-alt"></i> Alamat</h4>
                    <p>${data.address.replace(/\n/g, '<br>')}</p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #4a7c22; margin-bottom: 10px;"><i class="fas fa-share-alt"></i> Media Sosial</h4>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        ${data.youtube ? `<a href="${data.youtube}" target="_blank" style="color: #FF0000;"><i class="fab fa-youtube"></i> YouTube</a>` : ''}
                        ${data.instagram ? `<a href="${data.instagram}" target="_blank" style="color: #E4405F;"><i class="fab fa-instagram"></i> Instagram</a>` : ''}
                        ${data.linkedin ? `<a href="${data.linkedin}" target="_blank" style="color: #0077B5;"><i class="fab fa-linkedin"></i> LinkedIn</a>` : ''}
                        ${data.tiktok ? `<a href="${data.tiktok}" target="_blank" style="color: #000000;"><i class="fab fa-tiktok"></i> TikTok</a>` : ''}
                        ${data.facebook ? `<a href="${data.facebook}" target="_blank" style="color: #1877F2;"><i class="fab fa-facebook"></i> Facebook</a>` : ''}
                        ${data.twitter ? `<a href="${data.twitter}" target="_blank" style="color: #1DA1F2;"><i class="fab fa-twitter"></i> Twitter</a>` : ''}
                    </div>
                </div>
                
                ${data.about_company ? `
                <div>
                    <h4 style="color: #4a7c22; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Tentang Kami</h4>
                    <p>${data.about_company}</p>
                </div>` : ''}
            </div>
        `;
        
        document.getElementById('previewContent').innerHTML = previewHTML;
        openModal('previewModal');
    }

    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Close modal when clicking X
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.onclick = function() {
            this.closest('.modal').style.display = 'none';
        }
    });

    // Auto-format phone numbers
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('62')) {
                value = '+' + value;
            } else if (value.startsWith('0')) {
                value = '+62' + value.substring(1);
            }
            this.value = value;
        });
    });
</script>

<style>
    /* Additional styles for contact management */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 800px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1001;
        animation: slideInRight 0.5s ease-out;
        max-width: 400px;
    }

    .notification-success {
        background-color: #28a745;
    }

    .notification-danger {
        background-color: #dc3545;
    }

    .notification-info {
        background-color: #17a2b8;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    #maps-preview {
        transition: all 0.3s ease;
    }

    #maps-preview:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
        .form-row-dual {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            margin: 10% auto;
            width: 95%;
            padding: 15px;
        }
        
        .notification {
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
</style>