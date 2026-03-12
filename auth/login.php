<?php
$page_title = 'Log In';
require_once __DIR__ . '/user-auth.php';

if (isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Check admin credentials first (admin can login from main page too)
    $adminEmail = defined('ADMIN_EMAIL') ? strtolower(trim(ADMIN_EMAIL)) : '';
    $adminPass = defined('ADMIN_PASSWORD') ? trim(ADMIN_PASSWORD) : '';
    if ($adminEmail && $adminPass && strtolower($email) === $adminEmail && $password === $adminPass) {
        require_once __DIR__ . '/../admin/auth.php';
        if (function_exists('adminLogin') && adminLogin($email, $password)) {
            session_write_close();
            $url = rtrim(BASE_URL ?? '', '/') . '/admin/';
            header('Location: ' . $url);
            exit;
        }
    }
    
    $result = userLogin($email, $password);
    
    if (isset($result['success'])) {
        session_write_close();
        $url = rtrim(BASE_URL ?? '', '/') . '/dashboard';
        header('Location: ' . $url);
        exit;
    }
    $error = $result['error'] ?? 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Log In</h1>
            <p class="text-gray-500 mt-1">Welcome back to <?php echo SITE_NAME; ?></p>
        </div>
        
        <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                Log In
            </button>
        </form>
        
        <p class="mt-6 text-center text-gray-600 text-sm">
            Don't have an account? <a href="<?php echo BASE_URL; ?>/signup" class="text-purple-600 font-semibold hover:underline">Sign Up</a>
        </p>
    </div>
</body>
</html>
