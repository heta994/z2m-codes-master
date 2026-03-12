<?php
/**
 * STEP 5a: Admin - Pending Submissions List
 * Shows all submissions waiting for review.
 */
require_once __DIR__ . '/auth.php';
requireAdmin();

$submissions = getPendingSubmissions();
$approved = isset($_GET['approved']);
$rejected = isset($_GET['rejected']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions - <?php echo SITE_NAME; ?> Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900">Pending Submissions</h1>
                <div class="flex gap-4">
                    <a href="<?php echo BASE_URL; ?>/admin/" class="text-gray-600 hover:text-purple-600">← Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($approved): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">Submission approved and published.</div>
        <?php elseif ($rejected): ?>
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg">Submission rejected.</div>
        <?php endif; ?>
        <?php if (empty($submissions)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
            <p class="text-lg font-medium mb-2">No pending submissions.</p>
            <p class="text-sm mb-4">Contributor submissions from the <a href="<?php echo BASE_URL; ?>/submit" target="_blank" class="text-purple-600 hover:underline">Submit Code</a> page will appear here for review. You can approve (publish to site) or reject each submission.</p>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contributor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($submissions as $s): 
                        $isAdmin = ($s['source'] ?? '') === 'admin';
                    ?>
                    <tr>
                        <td class="px-6 py-4 text-gray-600">#<?php echo $s['id']; ?></td>
                        <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($s['title']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?php echo $categories[$s['category']]['name'] ?? $s['category']; ?></td>
                        <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($s['contributor_name'] ?? '-'); ?></td>
                        <td class="px-6 py-4"><span class="text-xs px-2 py-1 rounded <?php echo ($s['source'] ?? '') === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'; ?>"><?php echo ($s['source'] ?? 'contributor') === 'admin' ? 'Admin' : 'Contributor'; ?></span></td>
                        <td class="px-6 py-4 text-gray-600 text-sm"><?php echo htmlspecialchars($s['submitted_at'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($isAdmin): ?>
                            <span class="text-green-600 font-medium">Published</span>
                            <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/admin/review.php?id=<?php echo $s['id']; ?>" class="text-purple-600 hover:underline font-medium">Review →</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
