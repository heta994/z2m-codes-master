<?php
require_once __DIR__ . '/auth.php';
requireAdmin();

$adminCodes = getAdminCodes();
$allCodes = getAllCodes();
$pendingSubmissions = getPendingSubmissions();
$pendingCount = count($pendingSubmissions);
$success = $_GET['success'] ?? '';
$deleted = isset($_GET['deleted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Admin Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="<?php echo BASE_URL; ?>/assets/images/z2m.svg" alt="Zero2Maker" class="h-8 w-auto" />
                </div>
                <div class="flex items-center gap-4">
                    <?php include __DIR__ . '/includes/notification-button.php'; ?>
                    <a href="<?php echo BASE_URL; ?>/" class="text-gray-600 hover:text-purple-600">View Site</a>
                    <a href="<?php echo BASE_URL; ?>/admin/add.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700">
                        + Add Project
                    </a>
                    <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($success === 'added'): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            Project added successfully!
        </div>
        <?php elseif ($success === 'updated'): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            Project updated successfully!
        </div>
        <?php elseif ($deleted): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            Project deleted.
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Projects</p>
                <p class="text-3xl font-bold text-purple-600"><?php echo count($allCodes); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Admin-Added Projects</p>
                <p class="text-3xl font-bold text-green-600"><?php echo count($adminCodes); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <a href="<?php echo BASE_URL; ?>/admin/submissions.php" class="block hover:opacity-90">
                    <p class="text-gray-500 text-sm">Pending Submissions</p>
                    <p class="text-3xl font-bold <?php echo $pendingCount > 0 ? 'text-amber-600' : 'text-blue-600'; ?>"><?php echo $pendingCount; ?></p>
                    <?php if ($pendingCount > 0): ?><span class="text-sm text-amber-600">Review →</span><?php endif; ?>
                </a>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Categories</p>
                <p class="text-3xl font-bold text-blue-600"><?php echo count($categories) - 1; ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Admin-Added Projects</h2>
                <p class="text-sm text-gray-500">Projects you've added via the admin panel (editable)</p>
            </div>
            <?php if (empty($adminCodes)): ?>
            <div class="p-12 text-center text-gray-500">
                <p class="mb-4">No projects added yet.</p>
                <a href="<?php echo BASE_URL; ?>/admin/add.php" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700">
                    Add Your First Project
                </a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Difficulty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($adminCodes as $code): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <a href="<?php echo getCodeUrl($code); ?>" target="_blank" class="text-purple-600 hover:underline font-medium">
                                    <?php echo htmlspecialchars($code['title']); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?php echo $categories[$code['category']]['name'] ?? $code['category']; ?></td>
                            <td class="px-6 py-4 text-gray-600"><?php echo $difficulty_levels[$code['difficulty']]['name'] ?? $code['difficulty']; ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo BASE_URL; ?>/admin/edit.php?id=<?php echo $code['id']; ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                                <a href="<?php echo BASE_URL; ?>/admin/delete.php?id=<?php echo $code['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this project?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
