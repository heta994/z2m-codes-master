<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

define('USERS_FILE', __DIR__ . '/../data/users.json');

function getUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $json = file_get_contents(USERS_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveUsers($users) {
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT)) !== false;
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isUserLoggedIn()) return null;
    $users = getUsers();
    foreach ($users as $u) {
        if ($u['id'] == $_SESSION['user_id']) return $u;
    }
    return null;
}

function requireUser() {
    if (!isUserLoggedIn()) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

function userSignup($name, $email, $password) {
    $name = trim($name);
    $email = trim(strtolower($email));
    $password = trim($password);
    
    if (empty($name) || empty($email) || empty($password)) {
        return ['error' => 'All fields are required.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['error' => 'Invalid email address.'];
    }
    if (strlen($password) < 6) {
        return ['error' => 'Password must be at least 6 characters.'];
    }
    
    $users = getUsers();
    foreach ($users as $u) {
        if (strtolower($u['email']) === $email) {
            return ['error' => 'Email already registered.'];
        }
    }
    
    $id = 1;
    foreach ($users as $u) {
        if (isset($u['id']) && $u['id'] >= $id) $id = $u['id'] + 1;
    }
    
    $users[] = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    if (!saveUsers($users)) {
        return ['error' => 'Registration failed. Please try again.'];
    }
    
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    return ['success' => true];
}

function userLogin($email, $password) {
    $email = trim(strtolower($email));
    $password = trim($password);
    
    if (empty($email) || empty($password)) {
        return ['error' => 'Email and password are required.'];
    }
    
    $users = getUsers();
    foreach ($users as $u) {
        if (strtolower($u['email']) === $email && password_verify($password, $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_name'] = $u['name'];
            return ['success' => true];
        }
    }
    return ['error' => 'Invalid email or password.'];
}

function userLogout() {
    $_SESSION['user_id'] = null;
    $_SESSION['user_name'] = null;
    unset($_SESSION['user_id'], $_SESSION['user_name']);
}
