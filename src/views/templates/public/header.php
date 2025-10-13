<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/style.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/home.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/profile.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/information.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/article.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . '/css/main/contact.css' ?>">
</head>

<body>
    <nav>
        <div class="header">
            <div class="language-selector">
                <div class="flag-icon flag-indonesia" id="flag-display"></div>
                <select class="language-select" id="languageSelect" onchange="changeLanguage()">
                    <option value="id" data-flag="indonesia">ID - Indonesia</option>
                    <option value="en" data-flag="english">EN - English</option>
                </select>
            </div>
        </div>
        <div class="layar-dalam">
            <div class="logo">
                <a href="<?= BASEURL ?>"><img src="<?= BASEURL . '/' . $setting['logo_pinarak']?>" /></a>
            </div>
            <div class="menu">
                <a href="#" Class="tombol-menu">
                    <span class="garis"></span>
                    <span class="garis"></span>
                    <span class="garis"></span>
                </a>
                <ul>
                    <li>
                        <a id="beranda-link"
                            href="<?= BASEURL ?>"
                            <?php //$currentDir == 'pinarak-jogja-main' || $currentDir == '' ? 'style="font-weight: bold; text-decoration: underline; text-decoration-color: #ffff; text-underline-offset: 5px; color: #f5cf41;"' : '' ?>>
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a id="profile-link"
                            href="<?= BASEURL . '/profiles' ?>"
                            <?php //$currentDir == 'profiles' ? 'style="font-weight: bold; text-decoration: underline; text-decoration-color: #ffff; text-underline-offset: 5px; color: #f5cf41;"' : '' ?>>
                            Profil
                        </a>
                    </li>

                    <li>
                        <a id="information-link"
                            href="<?= BASEURL . '/informations' ?>"
                            <?php //$currentDir == 'information' ? 'style="font-weight: bold; text-decoration: underline; text-decoration-color: #ffff; text-underline-offset: 5px; color: #f5cf41;"' : '' ?>>
                            Informasi
                        </a>
                    </li>

                    <li>
                        <a id="berita-link"
                            href="<?= BASEURL . '/articles' ?>"
                            <?php //$currentDir == 'articles' ? 'style="font-weight: bold; text-decoration: underline; text-decoration-color: #ffff; text-underline-offset: 5px; color: #f5cf41;"' : '' ?>>
                            Berita
                        </a>
                    </li>

                    <li>
                        <a id="contact-link"
                            href="<?= BASEURL . '/contacts' ?>"
                            <?php //$currentDir == 'contact' ? 'style="font-weight: bold; text-decoration: underline; text-decoration-color: #ffff; text-underline-offset: 5px; color: #f5cf41;"' : '' ?>>
                            Kontak
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>