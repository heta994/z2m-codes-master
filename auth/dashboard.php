<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/user-auth.php';

if (!isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$user = getCurrentUser();
if (!$user) {
    userLogout();
    header('Location: ' . BASE_URL . '/login');
    exit;
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>!</h1>
                <p class="text-gray-500 mt-1">Your <?php echo SITE_NAME; ?> Dashboard</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/logout" class="text-red-600 hover:text-red-700 font-medium">Logout</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="<?php echo getCodesUrl(); ?>" class="block p-6 bg-purple-50 rounded-xl hover:bg-purple-100 transition">
                <div class="text-3xl mb-2">📚</div>
                <h3 class="font-semibold text-gray-900">Browse Projects</h3>
                <p class="text-sm text-gray-600 mt-1">Explore Arduino & programming codes</p>
            </a>
            <a href="<?php echo BASE_URL; ?>/submit" class="block p-6 bg-purple-50 rounded-xl hover:bg-purple-100 transition">
                <div class="text-3xl mb-2">➕</div>
                <h3 class="font-semibold text-gray-900">Submit Project</h3>
                <p class="text-sm text-gray-600 mt-1">Share your code with the community</p>
            </a>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h2 class="font-semibold text-gray-900 mb-4">Quick Links</h2>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo getHomeUrl(); ?>" class="text-purple-600 hover:underline">Home</a>
                <a href="<?php echo getCodesUrl(); ?>" class="text-purple-600 hover:underline">All Projects</a>
                <a href="<?php echo getCategoryUrl('arduino-basics'); ?>" class="text-purple-600 hover:underline">Arduino Basics</a>
                <a href="<?php echo getCategoryUrl('sensors'); ?>" class="text-purple-600 hover:underline">Sensors</a>
                <a href="<?php echo BASE_URL; ?>/submit" class="text-purple-600 hover:underline">Submit Project</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
