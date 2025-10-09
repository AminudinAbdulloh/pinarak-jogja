<div class="content-section" id="media-partners">
    <div class="page-header">
        <h1><i class="fas fa-handshake"></i> Media Partner</h1>
        <p>Kelola media partner dan sponsor</p>
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

    <div class="content-area">
        <div class="content-header">
            <h3>Daftar Media Partner</h3>
            <a href="/pinarak-jogja-main/admin/media-partners/add">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Media Partner
                </button>
            </a>
        </div>
        <div class="content-body">
            <!-- Search and Filter Form -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-box">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <input type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari media partner berdasarkan nama, deskripsi, atau website..."
                                value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="type" class="form-control">
                            <option value="">Semua Tipe Partnership</option>
                            <?php if (!empty($partnership_types)): ?>
                                <?php foreach ($partnership_types as $type): ?>
                                    <option value="<?= htmlspecialchars($type['partnership_type']) ?>"
                                        <?= ($partnership_type ?? '') === $type['partnership_type'] ? 'selected' : '' ?>>
                                        <?= isset($type['partnership_type']) ? ucfirst(str_replace('_', ' ', $type['partnership_type'])) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <?php if (!empty($search ?? '') || !empty($partnership_type ?? '')): ?>
                            <a href="/pinarak-jogja-main/admin/media-partners/" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        <?php endif; ?>
                        <input type="hidden" name="page" value="1">
                    </div>
                </div>
            </form>

            <!-- Info hasil pencarian -->
            <?php if (!empty($search ?? '') || !empty($partnership_type ?? '')): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <?php if (!empty($search ?? '') && !empty($partnership_type ?? '')): ?>
                        Menampilkan hasil pencarian untuk "<strong><?= htmlspecialchars($search) ?></strong>"
                        dengan tipe "<strong><?= ucfirst(str_replace('_', ' ', $partnership_type)) ?></strong>":
                    <?php elseif (!empty($search ?? '')): ?>
                        Menampilkan hasil pencarian untuk "<strong><?= htmlspecialchars($search) ?></strong>":
                    <?php else: ?>
                        Menampilkan data dengan tipe "<strong><?= ucfirst(str_replace('_', ' ', $partnership_type)) ?></strong>":
                    <?php endif; ?>
                    <?= $total_records ?? 0 ?> media partner ditemukan
                </div>
            <?php endif; ?>

            <!-- Pagination Info -->
            <?php if (($total_records ?? 0) > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                        Menampilkan <?= ($offset ?? 0) + 1 ?> - <?= min(($offset ?? 0) + ($items_per_page ?? 10), $total_records ?? 0) ?>
                        dari <?= $total_records ?? 0 ?> media partner
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
                            <th>Logo</th>
                            <th>Nama</th>
                            <th>Tipe Partnership</th>
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
                                        <?php if (!empty($search ?? '') || !empty($partnership_type ?? '')): ?>
                                            Tidak ada media partner yang sesuai dengan kriteria pencarian
                                        <?php else: ?>
                                            Belum ada media partner yang tersedia
                                        <?php endif; ?>
                                    </h5>
                                    <?php if (!empty($search ?? '') || !empty($partnership_type ?? '')): ?>
                                        <p class="text-muted">Coba gunakan kriteria pencarian yang berbeda</p>
                                        <a href="/pinarak-jogja-main/admin/media-partners/" class="btn btn-outline-primary">
                                            <i class="fas fa-times"></i> Reset Filter
                                        </a>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan media partner baru</p>
                                        <a href="/pinarak-jogja-main/admin/media-partners/add" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Media Partner
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($media_partners as $partner): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($partner['logo']) && file_exists('../../' . $partner['logo'])): ?>
                                            <img src="../../<?php echo htmlspecialchars($partner['logo']); ?>"
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
                                        <?php if (!empty($partner['description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?php 
                                                $description = $partner['description'];
                                                echo htmlspecialchars(strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description);
                                                ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <?= ucfirst(str_replace('_', ' ', $partner['partnership_type'])) ?>
                                        </span>
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
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-delete-partner"
                                                data-partner-id="<?php echo htmlspecialchars($partner['id']); ?>"
                                                data-partner-name="<?php echo htmlspecialchars($partner['name']); ?>"
                                                title="Hapus Media Partner">
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
                <nav aria-label="Media partner pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Button -->
                        <?php if (($current_page ?? 1) > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= ($current_page ?? 1) - 1 ?><?= !empty($search ?? '') ? '&search=' . urlencode($search) : '' ?><?= !empty($partnership_type ?? '') ? '&type=' . urlencode($partnership_type) : '' ?>">
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
                        $start_page = max(1, ($current_page ?? 1) - 2);
                        $end_page = min(($total_pages ?? 1), ($current_page ?? 1) + 2);

                        // Show first page if not in range
                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1<?= !empty($search ?? '') ? '&search=' . urlencode($search) : '' ?><?= !empty($partnership_type ?? '') ? '&type=' . urlencode($partnership_type) : '' ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Main page range -->
                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $i == ($current_page ?? 1) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= !empty($search ?? '') ? '&search=' . urlencode($search) : '' ?><?= !empty($partnership_type ?? '') ? '&type=' . urlencode($partnership_type) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Show last page if not in range -->
                        <?php if ($end_page < ($total_pages ?? 1)): ?>
                            <?php if ($end_page < ($total_pages ?? 1) - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $total_pages ?? 1 ?><?= !empty($search ?? '') ? '&search=' . urlencode($search) : '' ?><?= !empty($partnership_type ?? '') ? '&type=' . urlencode($partnership_type) : '' ?>">
                                    <?= $total_pages ?? 1 ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if (($current_page ?? 1) < ($total_pages ?? 1)): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= ($current_page ?? 1) + 1 ?><?= !empty($search ?? '') ? '&search=' . urlencode($search) : '' ?><?= !empty($partnership_type ?? '') ? '&type=' . urlencode($partnership_type) : '' ?>">
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
                <?php if (($total_pages ?? 1) > 10): ?>
                    <div class="text-center mt-3">
                        <form method="GET" class="d-inline-flex align-items-center">
                            <?php if (!empty($search ?? '')): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                            <?php if (!empty($partnership_type ?? '')): ?>
                                <input type="hidden" name="type" value="<?= htmlspecialchars($partnership_type) ?>">
                            <?php endif; ?>
                            <label class="mb-0 me-2">Lompat ke halaman:</label>
                            <input type="number"
                                name="page"
                                min="1"
                                max="<?= $total_pages ?? 1 ?>"
                                value="<?= $current_page ?? 1 ?>"
                                class="form-control form-control-sm mx-2"
                                style="width: 80px;">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Go</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>