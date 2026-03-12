<?php
require_once __DIR__ . '/user-auth.php';
userLogout();
header('Location: ' . BASE_URL . '/login');
exit;
