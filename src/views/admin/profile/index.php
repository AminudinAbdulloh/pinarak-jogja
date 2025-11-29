<div class="content-section" id="profiles">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Manajemen Profile</h1>
        <p>Kelola profile tim dan Calendar of Event</p>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="notification notification-success" id="successNotification">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="notification notification-error" id="errorNotification">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Calendar of Event Section -->
    <div class="content-area" style="margin-bottom: 30px;">
        <div class="content-header">
            <h3>Calendar of Event</h3>
        </div>
        <div class="content-body">
            <div class="table-responsive">
                <table class="table" id="coeTableWrapper">
                    <thead>
                        <tr>
                            <th style="width: 50% !important;">Preview</th>
                            <th style="width: 50% !important;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="coeTable">
                        <?php if (empty($coe_list)): ?>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <i class="fas fa-calendar fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada Calendar of Event yang tersedia</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($coe_list as $coe): ?>
                                <tr>
                                    <td style="width: 50% !important;">
                                        <?php if (!empty($coe['image'])): ?>
                                            <img src="<?= BASEURL . '/' . htmlspecialchars($coe['image']) ?>" 
                                                alt="Calendar of Event"
                                                style="max-width: 100%; height: auto; object-fit: contain;">
                                        <?php else: ?>
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 50% !important;">
                                        <div class="btn-group">
                                            <a href="<?= BASEURL . '/admin/profile/coe/edit/' . urlencode($coe['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Calendar of Event">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Profile Section dengan Search dan Paginasi -->
    <div class="content-area">
        <div class="content-header">
            <h3>Daftar Profile Tim</h3>
            <a href="<?= BASEURL . '/admin/profile/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Profile
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" action="<?= BASEURL . '/admin/profile/search/redirect' ?>" class="mb-3" id="searchForm">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text"
                        name="search"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari profile berdasarkan nama atau jabatan..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASEURL . '/admin/profile' ?>" class="btn btn-outline-secondary btn-sm">
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
                        <?= $total_profiles ?> profile ditemukan
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-database"></i> Total Profile: <strong><?= $total_profiles ?></strong>
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
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="profileTable">
                        <?php if (empty($profiles)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?= !empty($search) ? 'Tidak ada profile yang sesuai dengan pencarian' : 'Belum ada profile yang tersedia' ?>
                                    </h5>
                                    <?php if (!empty($search)): ?>
                                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan profile baru</p>
                                        <a href="<?= BASEURL . '/admin/profile/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Profile
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($profiles as $profile): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($profile['pass_photo'])): ?>
                                            <img src="<?= BASEURL . '/' . htmlspecialchars($profile['pass_photo']); ?>"
                                                alt="<?php echo htmlspecialchars($profile['full_name']); ?>"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                        <?php else: ?>
                                            <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($profile['full_name']); ?></strong>
                                        <br>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($profile['position']); ?>
                                    </td>
                                    <td>
                                        <?php if ($profile['status'] === 'active'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        <?php echo htmlspecialchars($profile['publisher_name'] ?: 'Tidak diketahui'); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASEURL . '/admin/profile/edit/' . urlencode($profile['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Profile">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="<?= BASEURL . '/admin/profile/delete_profile' ?>" 
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus profile \'<?= htmlspecialchars($profile['full_name']) ?>\'?')">
                                                <input type="hidden" name="id" value="<?= $profile['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Profile">
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
                    <nav aria-label="Profile pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol Previous -->
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/profile/search/' . urlencode($search) . ($current_page > 2 ? '/page' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/profile' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
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
                                        ? BASEURL . '/admin/profile/search/' . urlencode($search)
                                        : BASEURL . '/admin/profile';
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
                                        ? BASEURL . '/admin/profile/search/' . urlencode($search)
                                        : BASEURL . '/admin/profile/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/profile'
                                        : BASEURL . '/admin/profile/page/' . $i;
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
                                        ? BASEURL . '/admin/profile/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/profile/page/' . $total_pages;
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
                                        ? BASEURL . '/admin/profile/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/profile/page/' . ($current_page + 1)
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
                            Menampilkan <?= count($profiles) ?> dari <?= $total_profiles ?> profile
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
        window.location.href = '<?= BASEURL ?>/admin/profile/search/' + encodeURIComponent(safeSearch);
    } else {
        // Jika kosong, redirect ke index
        window.location.href = '<?= BASEURL ?>/admin/profile';
    }
});
</script>