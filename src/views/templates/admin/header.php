<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= BASEURL.'/css/admin/style.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL.'/css/admin/form.css' ?>">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-tachometer-alt"></i> Admin Panel</h2>
                <p>Sistem Manajemen Konten</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?= BASEURL . '/admin/home' ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/event' ?>">
                        <i class="fas fa-calendar-alt"></i> Event
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/profile' ?>">
                        <i class="fas fa-users"></i> Profile
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/media-partner' ?>">
                        <i class="fas fa-handshake"></i> Media Partner
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/article' ?>">
                        <i class="fas fa-newspaper"></i> Artikel
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/contact' ?>">
                        <i class="fas fa-envelope"></i> Contact
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/setting' ?>">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a></li>
                <li><a href="<?= BASEURL . '/admin/auth/logout' ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">