<div class="content-section" id="profiles">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Manajemen Profile</h1>
        <p>Kelola profile tim dan link YouTube</p>
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

    <!-- YouTube Link Section -->
    <div class="content-area" style="margin-bottom: 30px;">
        <div class="content-header">
            <h3>Link YouTube Aktif</h3>
            <a href="youtube/add/">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Link YouTube
                </button>
            </a>
        </div>
        <div class="content-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Video</th>
                            <th>Link YouTube</th>
                            <th>Publisher</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($youtube_links)): ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada video yang tersedia</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($youtube_links as $yt_link): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($yt_link['title']); ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $url = htmlspecialchars($yt_link['url']);
                                        $truncated_url = strlen($url) > 50 ? substr($url, 0, 50) . '...' : $url;
                                        echo $truncated_url;
                                        ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        <?php echo htmlspecialchars($yt_link['publisher_name'] ?: 'Tidak diketahui'); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="youtube/edit?id=<?php echo urlencode($yt_link['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Link Youtube">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-delete-yt"
                                                data-yt-id="<?php echo htmlspecialchars($yt_link['id']); ?>"
                                                data-yt-title="<?php echo htmlspecialchars($yt_link['title']); ?>"
                                                title="Hapus Link Youtube">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
            <a href="add/">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Profile
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search Form -->
            <form method="GET" class="mb-3">
                <div class="search-box">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari profile berdasarkan nama atau jabatan..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                    <input type="hidden" name="page" value="1">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="/pinarak-jogja-main/admin/profiles/" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Info hasil pencarian -->
            <?php if (!empty($search)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Menampilkan hasil pencarian untuk "<strong><?= htmlspecialchars($search) ?></strong>":
                    <?= $total_records ?? 0 ?> profile ditemukan
                </div>
            <?php endif; ?>

            <!-- Pagination Info -->
            <?php if (($total_records ?? 0) > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                        Menampilkan <?= ($offset ?? 0) + 1 ?> - <?= min(($offset ?? 0) + ($items_per_page ?? 10), $total_records ?? 0) ?>
                        dari <?= $total_records ?? 0 ?> profile
                    </small>
                    <small class="text-muted">
                        Halaman <?= $current_page ?? 1 ?> dari <?= $total_pages ?? 1 ?>
                    </small>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Publisher</th>
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
                                        <a href="add/" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Profile
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($profiles as $profile): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($profile['pass_photo']) && file_exists('../../' . $profile['pass_photo'])): ?>
                                            <img src="../../<?php echo htmlspecialchars($profile['pass_photo']); ?>"
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
                                        <small class="text-muted">Order: <?php echo $profile['display_order'] ?? 0; ?></small>
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
                                            <a href="edit?id=<?php echo urlencode($profile['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Profile">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-delete-profile"
                                                data-profile-id="<?php echo htmlspecialchars($profile['id']); ?>"
                                                data-profile-name="<?php echo htmlspecialchars($profile['full_name']); ?>"
                                                title="Hapus Profile">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (($total_pages ?? 1) > 1): ?>
                <nav aria-label="Profile pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Button -->
                        <?php if (($current_page ?? 1) > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= ($current_page ?? 1) - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </span>
                            </li>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $current_page = $current_page ?? 1;
                        $total_pages = $total_pages ?? 1;
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        // Show first page if not in range
                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Main page range -->
                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Show last page if not in range -->
                        <?php if ($end_page < $total_pages): ?>
                            <?php if ($end_page < $total_pages - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $total_pages ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                    <?= $total_pages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    Next <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Jump to page -->
                <?php if ($total_pages > 10): ?>
                    <div class="text-center mt-3">
                        <form method="GET" class="d-inline-flex align-items-center">
                            <?php if (!empty($search)): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                            <label class="mb-0 me-2">Lompat ke halaman:</label>
                            <input type="number"
                                name="page"
                                min="1"
                                max="<?= $total_pages ?>"
                                value="<?= $current_page ?>"
                                class="form-control form-control-sm mx-2"
                                style="width: 80px;">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Go</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Debug Information (remove in production) -->
            <?php if (isset($_GET['debug'])): ?>
                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
                    <h5>Debug Information:</h5>
                    <p><strong>Total Profiles:</strong> <?php echo count($profiles ?? []); ?></p>
                    <p><strong>Search Term:</strong> <?php echo htmlspecialchars($search ?? ''); ?></p>
                    <p><strong>Current Page:</strong> <?php echo $current_page ?? 1; ?></p>
                    <p><strong>Total Pages:</strong> <?php echo $total_pages ?? 1; ?></p>
                    <p><strong>Total Records:</strong> <?php echo $total_records ?? 0; ?></p>
                    <p><strong>Last Query Error:</strong> <?php echo $error_message ?? 'None'; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>