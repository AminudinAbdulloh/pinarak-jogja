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
        $router->get('/admin/home', ['AdminDashboardController', 'index']);

        $router->get('/admin/event', ['AdminEventController', 'index']);
        $router->get('/admin/event/index', ['AdminEventController', 'index']);
        $router->get('/admin/event/add', ['AdminEventController', 'add']);
        $router->post('/admin/event/add', ['AdminEventController', 'add']);

        $router->get('/admin/profile', ['AdminProfileController', 'index']);

        $router->get('/admin/media-partner', ['AdminMediaPartnerController', 'index']);

        $router->get('/admin/contact', ['AdminContactController', 'index']);

        $router->get('/admin/setting', ['AdminSettingController', 'index']);

        $router->get('/admin/article', ['AdminArticleController', 'index']);
        $router->get('/admin/article/index', ['AdminArticleController', 'index']);
        $router->get('/admin/article/add', ['AdminArticleController', 'add']);
        $router->post('/admin/article/add', ['AdminArticleController', 'add']);
        $router->put('/admin/article/edit/:id', ['AdminArticleController', 'edit']);
        $router->delete('/admin/article/delete/:id', ['AdminArticleController', 'delete']);

        $router->run();
    }
}