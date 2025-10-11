<div class="content-section" id="articles">
    <div class="page-header">
        <h1><i class="fas fa-newspaper"></i> Manajemen Artikel</h1>
        <p>Kelola semua artikel dan konten</p>
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
            <h3>Daftar Artikel</h3>
            <a href="<?= BASEURL . '/admin/article/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Artikel
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

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Konten</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="articleTable">
                        <?php if (empty($articles)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        <?= !empty($search) ? 'Tidak ada artikel yang sesuai dengan pencarian' : 'Belum ada artikel yang tersedia' ?>
                                    </h5>
                                    <?php if (!empty($search)): ?>
                                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-muted">Mulai dengan menambahkan artikel baru</p>
                                        <a href="<?= BASEURL . '/admin/article/add' ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Artikel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td>
                                        <?php if ($article['image']): ?>
                                            <img src="<?= BASEURL . '/' . htmlspecialchars($article['image']); ?>"
                                                alt="<?php echo htmlspecialchars($article['title']); ?>"
                                                style="border-radius: 5px; width: 60px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/60x40"
                                                alt="No Image"
                                                style="border-radius: 5px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                                    <td><?php echo truncateText(strip_tags($article['content']), 50); ?></td>
                                    <td>
                                        <?php if ($article['status'] == 'published'): ?>
                                            <span class="badge badge-success">Published</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/pinarak-jogja-main/admin/articles/edit/<?php echo urlencode($article['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Artikel">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="<?= BASEURL . '/admin/article/delete_article' ?>" 
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus artikel \'<?= htmlspecialchars($article['title']) ?>\'?')">
                                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Artikel">
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
                    <nav aria-label="Artikel pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol Previous -->
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <?php 
                                if (!empty($search)) {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/article/search/' . urlencode($search) . ($current_page > 2 ? '/page' . ($current_page - 1) : '')
                                        : '#';
                                } else {
                                    $prevUrl = $current_page > 1 
                                        ? BASEURL . '/admin/article' . ($current_page > 2 ? '/page/' . ($current_page - 1) : '')
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
                                        ? BASEURL . '/admin/article/search/' . urlencode($search)
                                        : BASEURL . '/admin/article';
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
                                        ? BASEURL . '/admin/article/search/' . urlencode($search)
                                        : BASEURL . '/admin/article/search/' . urlencode($search) . '/page/' . $i;
                                } else {
                                    $pageUrl = $i == 1 
                                        ? BASEURL . '/admin/article'
                                        : BASEURL . '/admin/article/page/' . $i;
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
                                        ? BASEURL . '/admin/article/search/' . urlencode($search) . '/page/' . $total_pages
                                        : BASEURL . '/admin/article/page/' . $total_pages;
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
                                        ? BASEURL . '/admin/article/search/' . urlencode($search) . '/page/' . ($current_page + 1)
                                        : '#';
                                } else {
                                    $nextUrl = $current_page < $total_pages 
                                        ? BASEURL . '/admin/article/page/' . ($current_page + 1)
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
                            Menampilkan <?= count($articles) ?> dari <?= $total_articles ?> artkel
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>