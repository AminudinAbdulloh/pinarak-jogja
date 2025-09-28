<div class="content-section" id="events">
    <div class="page-header">
        <h1><i class="fas fa-calendar-alt"></i> Manajemen Event</h1>
        <p>Kelola semua event dan acara</p>
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
            <h3>Daftar Event</h3>
            <a href="<?= BASEURL . '/admin/event/add' ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Event
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
                        placeholder="Cari event berdasarkan judul, deskripsi, atau lokasi..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                    <input type="hidden" name="page" value="1">
                </div>
                <div class="mt-2">
                    <?php if (!empty($search)): ?>
                        <a href="/pinarak-jogja-main/admin/events/" class="btn btn-outline-secondary btn-sm">
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
                    <?= count($events) ?> event ditemukan
                </div>
            <?php endif; ?>

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
                                        <a href="/pinarak-jogja-main/admin/events/add" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Event
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($event['image']) && file_exists('../../' . $event['image'])): ?>
                                            <img src="../../<?php echo htmlspecialchars($event['image']); ?>"
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
                                        // Simple text truncation if truncateText function doesn't exist
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
                                        // Simple date formatting if formatDate function doesn't exist
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
                                            <a href="/pinarak-jogja-main/admin/events/edit?id=<?php echo urlencode($event['id']); ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-delete-event"
                                                data-event-id="<?php echo htmlspecialchars($event['id']); ?>"
                                                data-event-title="<?php echo htmlspecialchars($event['title']); ?>"
                                                title="Hapus Event">
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
</div>