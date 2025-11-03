<div class="content-section" id="users">
    <div class="page-header">
        <h1><i class="fas fa-users-cog"></i> Kelola Admin</h1>
        <p>Kelola semua pengguna administrator</p>
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
            <h3>Daftar Admin</h3>
            <a href="<?= BASEURL . '/admin/users/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Admin
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" action="<?= BASEURL . '/admin/users/search/redirect' ?>" class="mb-3" id="searchForm">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text" name="q" id="searchInput" class="form-control"
                        placeholder="Cari username, email, atau nama lengkap..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASEURL . '/admin/users' ?>" class="btn btn-outline-secondary btn-sm">
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
                        <?= $total_users ?> admin ditemukan
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-database"></i> Total Admin: <strong><?= $total_users ?></strong>
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?= !empty($search) ? 'Tidak ada admin yang sesuai dengan pencarian' : 'Belum ada admin yang terdaftar' ?>
                                    </h5>
                                    <?php if (!empty($search)): ?>
                                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan admin baru</p>
                                        <a href="<?= BASEURL . '/admin/users/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Tambah Admin
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        <strong><?= htmlspecialchars($u['username']) ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted"></i>
                                        <?= htmlspecialchars($u['email']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($u['full_name']) ?></td>
                                    <td>
                                        <?php
                                        if ($u['status'] === 'active') {
                                            echo '<span class="badge badge-success">Active</span>';
                                        } else {
                                            echo '<span class="badge badge-secondary">Inactive</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($u['last_login'])) {
                                            if (function_exists('formatDate')) {
                                                echo formatDate($u['last_login']);
                                            } else {
                                                echo date('d M Y H:i', strtotime($u['last_login']));
                                            }
                                        } else {
                                            echo '<span class="text-muted">-</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (function_exists('formatDate')) {
                                            echo formatDate($u['created_at']);
                                        } else {
                                            echo date('d M Y H:i', strtotime($u['created_at']));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASEURL . '/admin/users/edit/' . urlencode($u['id']) ?>"
                                                class="btn btn-warning btn-sm" title="Edit Admin">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ((int) $u['id'] !== (int) AuthMiddleware::getAdminId()): ?>
                                                <form method="POST" action="<?= BASEURL . '/admin/users/delete_user' ?>"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus admin \'<?= htmlspecialchars($u['username']) ?>\'?')">
                                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Admin">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" title="Tidak dapat menghapus diri sendiri"
                                                    disabled>
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h1><?php htmlspecialchars($total_pages) ?></h1>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="User pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol Previous -->
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/users/search/' . urlencode($search) . ($current_page > 2 ? '/page' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/users' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
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
                                        ? BASEURL . '/admin/users/search/' . urlencode($search)
                                        : BASEURL . '/admin/users';
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
                                        ? BASEURL . '/admin/users/search/' . urlencode($search)
                                        : BASEURL . '/admin/users/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/users'
                                        : BASEURL . '/admin/users/page/' . $i;
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
                                        ? BASEURL . '/admin/users/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/users/page/' . $total_pages;
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
                                        ? BASEURL . '/admin/users/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/users/page/' . ($current_page + 1)
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
                            Menampilkan <?= count($users) ?> dari <?= $total_users ?> user
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Handle search form submission dengan encoding yang aman
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const searchInput = document.getElementById('searchInput');
        const searchValue = searchInput.value.trim();

        if (searchValue) {
            // Ganti spasi dengan tanda plus
            const safeSearch = searchValue.replace(/\s+/g, '+');

            // Redirect ke route dengan search parameter
            window.location.href = '<?= BASEURL ?>/admin/users/search/' + encodeURIComponent(safeSearch);
        } else {
            // Jika kosong, redirect ke index
            window.location.href = '<?= BASEURL ?>/admin/users';
        }
    });
</script>