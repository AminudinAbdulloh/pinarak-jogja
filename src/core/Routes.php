<?php

class Routes {
    public function run() {
        $router = new App();
        $router->setDefaultController('DefaultApp');
        $router->setDefaultMethod('index');

        // Public
        $router->get('/articles', ['ArticleController', 'index']);
        $router->get('/profiles', ['ProfileController', 'index']);
        $router->get('/informations', ['InformationController', 'index']);
        $router->get('/contacts', ['ContactController', 'index']);

        // Admin
        // 1. Auth Routes
        $router->get('/admin/auth', ['AdminAuthController', 'index']);
        $router->post('/admin/auth/login', ['AdminAuthController', 'login']);
        $router->get('/admin/auth/logout', ['AdminAuthController', 'logout']);

        // 2. Admin Home
        $router->get('/admin/home', ['AdminDashboardController', 'index']);

        // 3. Admin Event
        $router->get('/admin/event', ['AdminEventController', 'index']);
        $router->get('/admin/event/index', ['AdminEventController', 'index']);
        // Add Event
        $router->get('/admin/event/add', ['AdminEventController', 'add']);
        $router->post('/admin/event/add_event', ['AdminEventController', 'add_event']);
        // Edit Event
        $router->get('/admin/event/edit', ['AdminEventController', 'edit']);
        $router->get('/admin/event/edit/:id', ['AdminEventController', 'edit']);
        $router->post('/admin/event/edit_event', ['AdminEventController', 'edit_event']);
        // Delete Event
        $router->post('/admin/event/delete_event', ['AdminEventController', 'delete_event']);
        // Event Pagination
        $router->get('/admin/event/page/:page', ['AdminEventController', 'index']);
        // Event Search
        $router->get('/admin/event/search/:search', ['AdminEventController', 'search']);
        $router->get('/admin/event/search/:search/page/:page', ['AdminEventController', 'search']);

        // 4. Admin Profile
        $router->get('/admin/profile', ['AdminProfileController', 'index']);
        $router->get('/admin/profile/index', ['AdminProfileController', 'index']);
        // Profile Pagination
        $router->get('/admin/profile/page/:page', ['AdminProfileController', 'index']);
        // Profile Search
        $router->get('/admin/profile/search/:search', ['AdminProfileController', 'search']);
        $router->get('/admin/profile/search/:search/page/:page', ['AdminProfileController', 'search']);
        // Add Profile
        $router->get('/admin/profile/add', ['AdminProfileController', 'add']);
        $router->post('/admin/profile/add_profile', ['AdminProfileController', 'add_profile']);
        // Edit Profile
        $router->get('/admin/profile/edit', ['AdminProfileController', 'edit']);
        $router->get('/admin/profile/edit/:id', ['AdminProfileController', 'edit']);
        $router->post('/admin/profile/edit_profile', ['AdminProfileController', 'edit_profile']);
        // Delete Profile
        $router->post('/admin/profile/delete_profile', ['AdminProfileController', 'delete_profile']);
        // Add Youtube Link
        $router->get('/admin/profile/youtube_link/add', ['AdminProfileController', 'add_youtube_link']);
        $router->post('/admin/profile/youtube_link/added_youtube_link', ['AdminProfileController', 'added_youtube_link']);
        // Edit Youtube Link
        $router->get('/admin/profile/youtube_link/edit', ['AdminProfileController', 'edit_youtube_link']);
        $router->get('/admin/profile/youtube_link/edit/:id', ['AdminProfileController', 'edit_youtube_link']);  
        $router->post('/admin/profile/youtube_link/edited_youtube_link', ['AdminProfileController', 'edited_youtube_link']);
        // Delete Youtube Link
        $router->post('/admin/profile/youtube_link/delete', ['AdminProfileController', 'delete_youtube_link']);

        // 5. Admin Information
        $router->get('/admin/media-partner', ['AdminMediaPartnerController', 'index']);

        // 6. Admin Contact
        $router->get('/admin/contact', ['AdminContactController', 'index']);

        // 7. Admin Setting
        $router->get('/admin/setting', ['AdminSettingController', 'index']);

        // 8. Admin Article
        $router->get('/admin/article', ['AdminArticleController', 'index']);
        $router->get('/admin/article/index', ['AdminArticleController', 'index']);
        $router->get('/admin/article/add', ['AdminArticleController', 'add']);
        $router->post('/admin/article/add_article', ['AdminArticleController', 'add']);

        $router->run();
    }
}