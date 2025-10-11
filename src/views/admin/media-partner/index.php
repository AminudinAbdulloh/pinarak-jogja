<div class="content-section" id="media-partners">
    <div class="page-header">
        <h1><i class="fas fa-handshake"></i> Media Partner</h1>
        <p>Kelola media partner dan sponsor</p>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="notification notification-success" id="successNotification">
            <i class="fas fa-check-circle"></i> 
            <span><?= htmlspecialchars($success_message) ?></span>
            <span class="notification-close" onclick="closeNotification(this)">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="notification notification-error" id="errorNotification">
            <i class="fas fa-exclamation-circle"></i> 
            <span><?= htmlspecialchars($error_message) ?></span>
            <span class="notification-close" onclick="closeNotification(this)">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>

    <div class="content-area">
        <div class="content-header">
            <h3>Daftar Media Partner</h3>
            <a href="<?= BASEURL . '/admin/media-partner/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Media Partner
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" action="<?= BASEURL . '/admin/media-partner/search/redirect' ?>" class="mb-3" id="searchForm">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text"
                        name="q"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari media partner berdasarkan nama"
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASEURL . '/admin/media-partner' ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Info hasil pencarian dan total data -->
            <div class="mb-3">
                <?php if (!empty($search)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Menampilkan hasil pencarian untuk "<strong><?= htmlspecialchars($search) ?></strong>":
                        <?= $total_media_partners ?> media partner ditemukan
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-database"></i> Total Media Partner: <strong><?= $total_media_partners ?></strong>
                        <?php if ($total_pages > 0): ?>
                            | Halaman <strong><?= $current_page ?></strong> dari <strong><?= $total_pages ?></strong>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nama</th>
                            <th>Website</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($media_partners)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?php if (!empty($search ?? '')): ?>
                                            Tidak ada media partner yang sesuai dengan kriteria pencarian
                                        <?php else: ?>
                                            Belum ada media partner yang tersedia
                                        <?php endif; ?>
                                    </h5>
                                    <?php if (!empty($search ?? '')): ?>
                                        <p class="text-muted">Coba gunakan kriteria pencarian yang berbeda</p>
                                        <a href="/pinarak-jogja-main/admin/media-partners/" class="btn btn-outline-primary">
                                            <i class="fas fa-times"></i> Reset Filter
                                        </a>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan media partner baru</p>
                                        <a href="<?= BASEURL . '/admin/media-partner/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Media Partner
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($media_partners as $partner): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($partner['logo'])): ?>
                                            <img src="<?= BASEURL . '/' . htmlspecialchars($partner['logo']); ?>"
                                                alt="<?php echo htmlspecialchars($partner['name']); ?>"
                                                style="width: 80px; height: 40px; object-fit: contain; border-radius: 5px; border: 1px solid #ddd;">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/80x40?text=No+Logo"
                                                alt="No Logo"
                                                style="border-radius: 5px; border: 1px solid #ddd;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($partner['name']); ?></strong>
                                    </td>
                                    <td>
                                        <?php if (!empty($partner['website'])): ?>
                                            <a href="<?php echo htmlspecialchars($partner['website']); ?>"
                                                target="_blank"
                                                class="text-primary"
                                                title="Kunjungi website">
                                                <i class="fas fa-external-link-alt"></i>
                                                <?php
                                                $domain = parse_url($partner['website'], PHP_URL_HOST);
                                                echo htmlspecialchars($domain ?: $partner['website']);
                                                ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        <?php echo htmlspecialchars($partner['author_name'] ?: 'Tidak diketahui'); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/pinarak-jogja-main/admin/media-partners/edit?id=<?php echo urlencode($partner['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Media Partner">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="<?= BASEURL . '/admin/media-partner/delete_media_partner' ?>" 
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus media partner \'<?= htmlspecialchars($partner['name']) ?>\'?')">
                                                <input type="hidden" name="id" value="<?= $partner['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Media Partner">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="Media Partner pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol Previous -->
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/media-partner/search/' . urlencode($search) . ($current_page > 2 ? '/page' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/media-partner' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
                                        : '#';
                                }
                                ?>
                                <a class="page-link" 
                                   href="<?= $prevUrl ?>"
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php
                            // Logika untuk menampilkan nomor halaman
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);

                            // Tampilkan halaman pertama jika tidak termasuk dalam range
                            if ($start_page > 1): ?>
                                <li class="page-item">
                                    <?php 
                                    $firstPageUrl = !empty($search) 
                                        ? BASEURL . '/admin/media-partner/search/' . urlencode($search)
                                        : BASEURL . '/admin/media-partner';
                                    ?>
                                    <a class="page-link" href="<?= $firstPageUrl ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif;
                            endif;

                            // Tampilkan range halaman
                            for ($i = $start_page; $i <= $end_page; $i++): 
                                if (!empty($search)) {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/media-partner/search/' . urlencode($search)
                                        : BASEURL . '/admin/media-partner/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/media-partner'
                                        : BASEURL . '/admin/media-partner/page/' . $i;
                                }
                                ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $pageUrl ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor;

                            // Tampilkan halaman terakhir jika tidak termasuk dalam range
                            if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <?php 
                                    $lastPageUrl = !empty($search) 
                                        ? BASEURL . '/admin/media-partner/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/media-partner/page/' . $total_pages;
                                    ?>
                                    <a class="page-link" href="<?= $lastPageUrl ?>">
                                        <?= $total_pages ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Tombol Next -->
                            <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/media-partner/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/media-partner/page/' . ($current_page + 1)
                                        : '#';
                                }
                                ?>
                                <a class="page-link" 
                                   href="<?= $nextUrl ?>"
                                   aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Info halaman -->
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Menampilkan <?= count($media_partners) ?> dari <?= $total_media_partners ?> media partner
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Handle search form submission dengan encoding yang aman
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const searchInput = document.getElementById('searchInput');
        const searchValue = searchInput.value.trim();
        
        if (searchValue) {
            // Ganti spasi dengan tanda plus atau dash
            const safeSearch = searchValue.replace(/\s+/g, '+');
            
            // Redirect ke route dengan search parameter
            window.location.href = '<?= BASEURL ?>/admin/media-partner/search/' + encodeURIComponent(safeSearch);
        } else {
            // Jika kosong, redirect ke index
            window.location.href = '<?= BASEURL ?>/admin/media-partner';
        }
    });
</script>