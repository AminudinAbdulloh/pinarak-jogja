<?php

class AdminDashboardController extends BaseController{
    private $dashboardModel;

    public function __construct() {
        // Cek autentikasi di setiap controller admin
        AuthMiddleware::checkAuth();
        $this->dashboardModel = $this->model('DashboardModel');
    }
    
    public function index() {
        // Ambil semua statistik dari database
        $statistics = $this->dashboardModel->getAllStatistics();

        $data = [
            'title' => 'Dashboard - Home',
            'stats' => $statistics
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/home/index', $data);
        $this->view('templates/admin/footer');
    }
}