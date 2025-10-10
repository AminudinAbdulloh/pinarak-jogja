<div class="content-section" id="events">
    <div class="page-header">
        <h1><i class="fas fa-calendar-alt"></i> Manajemen Event</h1>
        <p>Kelola semua event dan acara</p>
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
            <h3>Daftar Event</h3>
            <a href="<?= BASEURL . '/admin/event/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Event
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" action="<?= BASEURL . '/admin/event/search/redirect' ?>" class="mb-3" id="searchForm">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text"
                        name="q"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari event berdasarkan judul, deskripsi, atau lokasi..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASEURL . '/admin/event' ?>" class="btn btn-outline-secondary btn-sm">
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
                        <?= $total_events ?> event ditemukan
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-database"></i> Total Event: <strong><?= $total_events ?></strong>
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
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Tanggal & Waktu</th>
                            <th>Lokasi</th>
                            <th>Publisher</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?= !empty($search) ? 'Tidak ada event yang sesuai dengan pencarian' : 'Belum ada event yang tersedia' ?>
                                    </h5>
                                    <?php if (!empty($search)): ?>
                                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan event baru</p>
                                        <a href="<?= BASEURL . '/admin/event/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Event
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($event['image'])): ?>
                                            <img src="<?= BASEURL . '/' . $event['image']; ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/60x40?text=No+Image"
                                                alt="No Image"
                                                style="border-radius: 5px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                                    </td>
                                    <td>
                                        <?php 
                                        $description = $event['description'];
                                        if (function_exists('truncateText')) {
                                            echo htmlspecialchars(truncateText($description, 50));
                                        } else {
                                            echo htmlspecialchars(strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (function_exists('formatDate')) {
                                            echo formatDate($event['start_time']);
                                        } else {
                                            echo date('d M Y H:i', strtotime($event['start_time']));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                        <?php echo htmlspecialchars($event['location'] ?: 'Tidak disebutkan'); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        <?php echo htmlspecialchars($event['publisher_name'] ?: 'Tidak diketahui'); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($event['status'] === 'draft') {
                                            echo '<span class="badge badge-warning">Draft</span>';
                                        } else {
                                            echo '<span class="badge badge-success">Published</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASEURL . '/admin/event/edit/' . urlencode($event['id']) ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="<?= BASEURL . '/admin/event/delete_event' ?>" 
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus event \'<?= htmlspecialchars($event['title']) ?>\'?')">
                                                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Event">
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
                    <nav aria-label="Event pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol Previous -->
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/event/search/' . urlencode($search) . ($current_page > 2 ? '/page' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/event' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
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
                                        ? BASEURL . '/admin/event/search/' . urlencode($search)
                                        : BASEURL . '/admin/event';
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
                                        ? BASEURL . '/admin/event/search/' . urlencode($search)
                                        : BASEURL . '/admin/event/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/event'
                                        : BASEURL . '/admin/event/page/' . $i;
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
                                        ? BASEURL . '/admin/event/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/event/page/' . $total_pages;
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
                                        ? BASEURL . '/admin/event/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/event/page/' . ($current_page + 1)
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
                            Menampilkan <?= count($events) ?> dari <?= $total_events ?> event
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
        window.location.href = '<?= BASEURL ?>/admin/event/search/' + encodeURIComponent(safeSearch);
    } else {
        // Jika kosong, redirect ke index
        window.location.href = '<?= BASEURL ?>/admin/event';
    }
});
</script>