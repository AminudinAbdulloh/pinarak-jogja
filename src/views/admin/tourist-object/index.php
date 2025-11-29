<div class="content-section" id="tourist-objects">
    <div class="page-header">
        <h1><i class="fas fa-map-location-dot"></i> Manajemen Objek Wisata</h1>
        <p>Kelola semua objek wisata dan destinasi</p>
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
            <h3>Daftar Objek Wisata</h3>
            <a href="<?= BASEURL . '/admin/tourist-object/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Objek Wisata
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" action="<?= BASEURL . '/admin/tourist-object/search/redirect' ?>" class="mb-3" id="searchForm">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text"
                        name="q"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari objek wisata berdasarkan nama, kategori, atau lokasi..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASEURL . '/admin/tourist-object' ?>" class="btn btn-outline-secondary btn-sm">
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
                        <?= $total_objects ?> objek wisata ditemukan
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-database"></i> Total Objek Wisata: <strong><?= $total_objects ?></strong>
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
                            <th>Gambar</th>
                            <th>Nama Objek Wisata</th>
                            <th>Kategori</th>
                            <th>Alamat</th>
                            <th>Google Maps</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($touristObjects)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-map-location-dot fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?= !empty($search) ? 'Tidak ada objek wisata yang sesuai dengan pencarian' : 'Belum ada objek wisata yang tersedia' ?>
                                    </h5>
                                    <?php if (!empty($search)): ?>
                                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan objek wisata baru</p>
                                        <a href="<?= BASEURL . '/admin/tourist-object/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Objek Wisata
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($touristObjects as $object): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($object['image'])): ?>
                                            <img src="<?= BASEURL . '/' . $object['image']; ?>"
                                                alt="<?php echo htmlspecialchars($object['article']); ?>"
                                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/60x40?text=No+Image"
                                                alt="No Image"
                                                style="border-radius: 5px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($object['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <?php echo htmlspecialchars($object['category']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                        <?php echo htmlspecialchars(strlen($object['address']) > 40 ? substr($object['address'], 0, 40) . '...' : $object['address']); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($object['google_map_link'])): ?>
                                            <a href="<?= htmlspecialchars($object['google_map_link']) ?>" target="_blank" class="btn btn-sm btn-warning">
                                                <i class="fas fa-map"></i> Lihat Peta
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASEURL . '/admin/tourist-object/edit/' . urlencode($object['id']) ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Objek Wisata">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="<?= BASEURL . '/admin/tourist-object/delete_tourist_object' ?>" 
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus objek wisata \'<?= htmlspecialchars($object['article']) ?>\'?')">
                                                <input type="hidden" name="id" value="<?= $object['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Objek Wisata">
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
                    <nav aria-label="Tourist object pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/tourist-object/search/' . urlencode($search) . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/tourist-object' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
                                        : '#';
                                }
                                ?>
                                <a class="page-link" href="<?= $prevUrl ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);

                            if ($start_page > 1): ?>
                                <li class="page-item">
                                    <?php 
                                    $firstPageUrl = !empty($search) 
                                        ? BASEURL . '/admin/tourist-object/search/' . urlencode($search)
                                        : BASEURL . '/admin/tourist-object';
                                    ?>
                                    <a class="page-link" href="<?= $firstPageUrl ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif;
                            endif;

                            for ($i = $start_page; $i <= $end_page; $i++): 
                                if (!empty($search)) {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/tourist-object/search/' . urlencode($search)
                                        : BASEURL . '/admin/tourist-object/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/tourist-object'
                                        : BASEURL . '/admin/tourist-object/page/' . $i;
                                }
                                ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $pageUrl ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor;

                            if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <?php 
                                    $lastPageUrl = !empty($search) 
                                        ? BASEURL . '/admin/tourist-object/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/tourist-object/page/' . $total_pages;
                                    ?>
                                    <a class="page-link" href="<?= $lastPageUrl ?>">
                                        <?= $total_pages ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/tourist-object/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/tourist-object/page/' . ($current_page + 1)
                                        : '#';
                                }
                                ?>
                                <a class="page-link" href="<?= $nextUrl ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Menampilkan <?= count($touristObjects) ?> dari <?= $total_objects ?> objek wisata
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const searchInput = document.getElementById('searchInput');
    const searchValue = searchInput.value.trim();
    
    if (searchValue) {
        const safeSearch = searchValue.replace(/\s+/g, '+');
        window.location.href = '<?= BASEURL ?>/admin/tourist-object/search/' + encodeURIComponent(safeSearch);
    } else {
        window.location.href = '<?= BASEURL ?>/admin/tourist-object';
    }
});
</script>