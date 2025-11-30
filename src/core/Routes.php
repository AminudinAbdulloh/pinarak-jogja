<?php

class Routes {
    public function run() {
        $router = new App();
        $router->setDefaultController('DefaultApp');
        $router->setDefaultMethod('index');

        // Public
        $router->get('/articles', ['ArticleController', 'index']);
        $router->get('/articles/:page', ['ArticleController', 'index']);
        $router->get('/articles/detail/:id', ['ArticleController', 'detail']);
        $router->get('/profiles', ['ProfileController', 'index']);
        $router->get('/informations', ['InformationController', 'index']);
        $router->get('/informations/:page', ['InformationController', 'index']);
        $router->get('/informations/detail/:id', ['InformationController', 'detail']);
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
        // Edit Calendar of Event
        $router->get('/admin/profile/coe/edit', ['AdminProfileController', 'edit_coe']);
        $router->get('/admin/profile/coe/edit/:id', ['AdminProfileController', 'edit_coe']);  
        $router->post('/admin/profile/coe/edited_coe', ['AdminProfileController', 'edited_coe']);

        // 6. Admin Contact
        $router->get('/admin/contact', ['AdminContactController', 'index']);
        $router->get('/admin/contact/index', ['AdminContactController', 'index']);
        // Edit Contact
        $router->get('/admin/contact/edit', ['AdminContactController', 'edit']);
        $router->get('/admin/contact/edit/:id', ['AdminContactController', 'edit']);
        $router->post('/admin/contact/edit_contact', ['AdminContactController', 'edit_contact']);

        // 7. Admin Setting
        $router->get('/admin/setting', ['AdminSettingController', 'index']);
        $router->get('/admin/setting/index', ['AdminSettingController', 'index']);
        // Edit Setting
        $router->get('/admin/setting/edit', ['AdminSettingController', 'edit']);
        $router->get('/admin/setting/edit/:id', ['AdminSettingController', 'edit']);
        $router->post('/admin/setting/edit_setting', ['AdminSettingController', 'edit_setting']);

        // 8. Admin Article
        $router->get('/admin/article', ['AdminArticleController', 'index']);
        $router->get('/admin/article/index', ['AdminArticleController', 'index']);
        // Article Pagination
        $router->get('/admin/article/page/:page', ['AdminArticleController', 'index']);
        // Article Search
        $router->get('/admin/article/search/:search', ['AdminArticleController', 'search']);
        $router->get('/admin/article/search/:search/page/:page', ['AdminArticleController', 'search']);
        // Add Article
        $router->get('/admin/article/add', ['AdminArticleController', 'add']);
        $router->post('/admin/article/add_article', ['AdminArticleController', 'add_article']);
        // Edit Article
        $router->get('/admin/article/edit', ['AdminArticleController', 'edit']);
        $router->get('/admin/article/edit/:id', ['AdminArticleController', 'edit']);
        $router->post('/admin/article/edit_article', ['AdminArticleController', 'edit_article']);
        // Delete Article
        $router->post('/admin/article/delete_article', ['AdminArticleController', 'delete_article']);

        // 9. Admin Users
        $router->get('/admin/users', ['AdminUserController', 'index']);
        $router->get('/admin/users/index', ['AdminUserController', 'index']);
        // User Pagination
        $router->get('/admin/users/page/:page', ['AdminUserController', 'index']);
        // User Search
        $router->get('/admin/users/search/:search', ['AdminUserController', 'search']);
        $router->get('/admin/users/search/:search/page/:page', ['AdminUserController', 'search']);
        // Add User
        $router->get('/admin/users/add', ['AdminUserController', 'add']);
        $router->post('/admin/users/add_user', ['AdminUserController', 'add_user']);
        // Edit User
        $router->get('/admin/users/edit', ['AdminUserController', 'edit']);
        $router->get('/admin/users/edit/:id', ['AdminUserController', 'edit']);
        $router->post('/admin/users/edit_user', ['AdminUserController', 'edit_user']);
        // Reset Password
        $router->post('/admin/users/reset_password', ['AdminUserController', 'reset_password']);
        // Delete User
        $router->post('/admin/users/delete_user', ['AdminUserController', 'delete_user']);
        
        // 10. Admin Tourist Object
        $router->get('/admin/tourist-object', ['AdminTouristObjectController', 'index']);
        $router->get('/admin/tourist-object/index', ['AdminTouristObjectController', 'index']);
        // Tourist Object Pagination
        $router->get('/admin/tourist-object/page/:page', ['AdminTouristObjectController', 'index']);
        // Tourist Object Search
        $router->get('/admin/tourist-object/search/:search', ['AdminTouristObjectController', 'search']);
        $router->get('/admin/tourist-object/search/:search/page/:page', ['AdminTouristObjectController', 'search']);
        // Add Tourist Object
        $router->get('/admin/tourist-object/add', ['AdminTouristObjectController', 'add']);
        $router->post('/admin/tourist-object/add_tourist_object', ['AdminTouristObjectController', 'add_tourist_object']);
        // Edit Tourist Object
        $router->get('/admin/tourist-object/edit', ['AdminTouristObjectController', 'edit']);
        $router->get('/admin/tourist-object/edit/:id', ['AdminTouristObjectController', 'edit']);
        $router->post('/admin/tourist-object/edit_tourist_object', ['AdminTouristObjectController', 'edit_tourist_object']);
        // Delete Tourist Object
        $router->post('/admin/tourist-object/delete_tourist_object', ['AdminTouristObjectController', 'delete_tourist_object']);

        $router->run();
    }
}