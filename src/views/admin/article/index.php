<div class="content-section" id="articles">
    <div class="page-header">
        <h1><i class="fas fa-newspaper"></i> Manajemen Artikel</h1>
        <p>Kelola semua artikel dan konten</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Daftar Artikel</h3>
            <a href="<?= BASEURL . '/admin/article/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Artikel
                </button>
            </a>
        </div>

        <!-- Notifikasi Success -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="notification notification-success" id="successNotification">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Notifikasi Error -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="notification notification-error" id="errorNotification">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

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
                                <td colspan="5" style="text-align: center;">Tidak ada artikel ditemukan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td>
                                        <?php if ($article['image']): ?>
                                            <img src="../../<?php echo htmlspecialchars($article['image']); ?>"
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
                                            <a href="/pinarak-jogja-main/admin/articles/edit?id=<?php echo urlencode($article['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Artikel">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-delete-article"
                                                data-article-id="<?php echo htmlspecialchars($article['id']); ?>"
                                                data-article-title="<?php echo htmlspecialchars($article['title']); ?>"
                                                title="Hapus Artikel">
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

            <!-- Pagination (jika diperlukan) -->
            <?php if (isset($data['pagination']) && $data['pagination']['total_pages'] > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if ($data['pagination']['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASEURL ?>/admin/articles?page=<?= $data['pagination']['current_page'] - 1 ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                <li class="page-item <?= ($i == $data['pagination']['current_page']) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASEURL ?>/admin/articles?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASEURL ?>/admin/articles?page=<?= $data['pagination']['current_page'] + 1 ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>