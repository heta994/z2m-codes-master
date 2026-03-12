<?php
require_once __DIR__ . '/auth.php';
adminLogout();
header('Location: ' . BASE_URL . '/admin/login.php');
exit;
