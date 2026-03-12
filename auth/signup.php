<?php
$page_title = 'Sign Up';
require_once __DIR__ . '/user-auth.php';

if (isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = userSignup(
        $_POST['name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? ''
    );
    
    if (isset($result['success'])) {
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    $error = $result['error'] ?? 'Registration failed.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
            <p class="text-gray-500 mt-1">Join <?php echo SITE_NAME; ?></p>
        </div>
        
        <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (min 6 characters)</label>
                <input type="password" id="password" name="password" required minlength="6"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                Sign Up
            </button>
        </form>
        
        <p class="mt-6 text-center text-gray-600 text-sm">
            Already have an account? <a href="<?php echo BASE_URL; ?>/login" class="text-purple-600 font-semibold hover:underline">Log In</a>
        </p>
    </div>
</body>
</html>
