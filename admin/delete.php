<?php
require_once __DIR__ . '/auth.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);
deleteAdminCode($id);

header('Location: ' . BASE_URL . '/admin/?deleted=1');
exit;
