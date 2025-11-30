<footer class="main-footer">
    <div class="footer-container">
        <!-- Information Section -->
        <div class="info-section">
            <div class="info-item">
                <h3>Kantor Pusat</h3>
                <p><?php echo htmlspecialchars($contact['address']) . ' ' . htmlspecialchars($contact['postal_code']) ?></p>
            </div>
            <div class="info-item">
                <h3>Office Hours</h3>
                <p><?php echo htmlspecialchars($contact['working_time'] . ' WIB') ?><br><?php echo htmlspecialchars($contact['working_days']) ?></p>
            </div>
            <div class="info-item">
                <h3>Contact</h3>
                <p><?php echo 'Telp. ' . htmlspecialchars($contact['phone_number']) ?><br><?php echo 'Email: ' . htmlspecialchars($contact['email']) ?></p>
            </div>
        </div>

        <!-- Main Logos Section -->
        <div class="main-logos-section">
            <div class="logo-container">
                <img src="<?= BASEURL . '/' . $setting['logo_pinarak']?>" alt="Pinarak Jogja" class="main-logo">
            </div>
            <div class="divider-line"></div>
            <div class="logo-container">
                <img src="<?= BASEURL . '/' . $setting['logo_dinpar']?>" alt="Dinas Pariwisata" class="main-logo">
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="social-media-section">
            <div class="social-links">
                <?php
                // Array untuk mapping social media dengan icon dan label
                $socialMediaList = [
                    'youtube' => [
                        'icon' => BASEURL . '/img/contacts/youtube.png',
                        'label' => 'Youtube'
                    ],
                    'instagram' => [
                        'icon' => BASEURL . '/img/contacts/instagram-rounded-svgrepo-com.png',
                        'label' => 'Instagram'
                    ],
                    'tiktok' => [
                        'icon' => BASEURL . '/img/contacts/brand-tiktok-sq-svgrepo-com.png',
                        'label' => 'TikTok'
                    ],
                    'facebook' => [
                        'icon' => BASEURL . '/img/contacts/facebook-rounded-svgrepo-com.png',
                        'label' => 'Facebook'
                    ],
                    // 'linkedin' => [
                    //     'icon' => BASEURL . '/img/contacts/linkedin-rounded-svgrepo-com.png',
                    //     'label' => 'LinkedIn'
                    // ],
                    // 'twitter' => [
                    //     'icon' => BASEURL . '/img/contacts/twitter.png',
                    //     'label' => 'Twitter'
                    // ]
                ];

                // Loop untuk menampilkan hanya social media yang ada di database
                foreach ($socialMediaList as $key => $social) {
                    if (!empty($contact[$key]) && filter_var($contact[$key], FILTER_VALIDATE_URL)) {
                        echo '<a href="' . htmlspecialchars($contact[$key]) . '" target="_blank" rel="noopener noreferrer" class="social-link">';
                        echo '<img src="' . $social['icon'] . '" alt="' . $social['label'] . '">';
                        echo '</a>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright-section">
            <p><?php echo htmlspecialchars($setting['copyright']); ?></p>
        </div>
    </div>
</footer>

<script></script>
<script></script>
</body>

</html>