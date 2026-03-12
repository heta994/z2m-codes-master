<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

function adminLogin($email, $password) {
    $email = trim(strtolower($email ?? ''));
    $password = trim($password ?? '');
    if (empty($email) || empty($password)) return false;
    
    // Check database first (when USE_MYSQL)
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("SELECT id, email, password FROM admin_users WHERE LOWER(email) = ? LIMIT 1");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $row['email'];
            return true;
        }
    }
    
    // Fallback: config credentials
    $adminEmail = defined('ADMIN_EMAIL') ? strtolower(trim(ADMIN_EMAIL)) : '';
    $adminPass = defined('ADMIN_PASSWORD') ? ADMIN_PASSWORD : '';
    if ($adminEmail && $adminPass && $email === $adminEmail && $password === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $email;
        return true;
    }
    return false;
}

function adminLogout() {
    $_SESSION['admin_logged_in'] = false;
    unset($_SESSION['admin_user']);
}
