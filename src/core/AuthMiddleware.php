<?php

class AuthMiddleware {
    public static function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: ' . BASEURL . '/admin/auth');
            exit;
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    public static function getAdminId() {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function getAdminUsername() {
        return $_SESSION['admin_username'] ?? null;
    }
}