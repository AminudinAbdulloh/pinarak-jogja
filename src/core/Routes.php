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
        // 1. Admin Home
        $router->get('/admin/home', ['AdminDashboardController', 'index']);

        // 2. Admin Event
        $router->get('/admin/event', ['AdminEventController', 'index']);
        $router->get('/admin/event/index', ['AdminEventController', 'index']);
        $router->get('/admin/event/add', ['AdminEventController', 'add']);
        $router->post('/admin/event/add_event', ['AdminEventController', 'add_event']);
        $router->post('/admin/event/edit_event', ['AdminEventController', 'edit_event']);

        // 3. Admin Profile
        $router->get('/admin/profile', ['AdminProfileController', 'index']);
        $router->get('/admin/profile/index', ['AdminProfileController', 'index']);
        $router->get('/admin/profile/add', ['AdminProfileController', 'add']);
        $router->get('/admin/profile/add_profile', ['AdminProfileController', 'add_profile']);
        $router->get('/admin/profile/edit/:1', ['AdminProfileController', 'edit']);
        $router->get('/admin/profile/edit/edit_profile', ['AdminProfileController', 'edit_profile']);

        // 4. Admin Information
        $router->get('/admin/media-partner', ['AdminMediaPartnerController', 'index']);

        // 5. Admin Contact
        $router->get('/admin/contact', ['AdminContactController', 'index']);

        // 6. Admin Setting
        $router->get('/admin/setting', ['AdminSettingController', 'index']);

        // 7. Admin Article
        $router->get('/admin/article', ['AdminArticleController', 'index']);
        $router->get('/admin/article/index', ['AdminArticleController', 'index']);
        $router->get('/admin/article/add', ['AdminArticleController', 'add']);
        $router->post('/admin/article/add', ['AdminArticleController', 'add']);
        $router->put('/admin/article/edit/:id', ['AdminArticleController', 'edit']);
        $router->delete('/admin/article/delete/:id', ['AdminArticleController', 'delete']);

        $router->run();
    }
}