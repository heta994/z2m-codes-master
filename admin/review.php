<?php
/**
 * STEP 5b: Admin - Review Single Submission
 * View full details and Approve or Reject.
 */
require_once __DIR__ . '/auth.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);
$submission = getSubmissionById($id);

if (!$submission) {
    header('Location: ' . BASE_URL . '/admin/submissions.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'approve') {
        if (approveSubmission($id)) {
            header('Location: ' . BASE_URL . '/admin/submissions.php?approved=1');
            exit;
        }
        $error = 'Failed to approve.';
    } elseif ($action === 'reject') {
        if (rejectSubmission($id)) {
            header('Location: ' . BASE_URL . '/admin/submissions.php?rejected=1');
            exit;
        }
        $error = 'Failed to reject.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review #<?php echo $id; ?> - <?php echo SITE_NAME; ?> Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="<?php echo BASE_URL; ?>/assets/images/z2m.svg" alt="Zero2Maker" class="h-8 w-auto" />
                    <h1 class="text-xl font-bold text-gray-900">Review Submission #<?php echo $id; ?></h1>
                </div>
                <div class="flex items-center gap-4">
                    <?php include __DIR__ . '/includes/notification-button.php'; ?>
                    <a href="<?php echo BASE_URL; ?>/admin/submissions.php" class="text-gray-600 hover:text-purple-600">← Back to list</a>
                    <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($error)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($submission['title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($submission['description'])); ?></p>
                    <div class="flex gap-2 text-sm">
                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded"><?php echo $categories[$submission['category']]['name'] ?? $submission['category']; ?></span>
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded"><?php echo $difficulty_levels[$submission['difficulty']]['name'] ?? $submission['difficulty']; ?></span>
                        <?php 
                        $z2mParts = array_filter(array_map('trim', explode(',', $submission['z2m_part'] ?? '')));
                        foreach ($z2mParts as $zp): ?>
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded"><?php echo htmlspecialchars($zp); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-3">Code</h3>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code><?php echo htmlspecialchars($submission['code']); ?></code></pre>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-3">Circuit Diagram</h3>
                    <?php if (!empty($submission['image'])): ?>
                    <img src="<?php echo BASE_URL . '/' . $submission['image']; ?>" alt="Circuit diagram" class="w-full rounded-lg border">
                    <?php else: ?>
                    <p class="text-gray-500">No diagram</p>
                    <?php endif; ?>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-3">Components</h3>
                    <?php if (!empty($submission['components'])): ?>
                    <ul class="list-disc list-inside text-sm text-gray-600">
                        <?php foreach ($submission['components'] as $c): ?>
                        <li><?php echo htmlspecialchars($c); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-gray-500 text-sm">None selected</p>
                    <?php endif; ?>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-3">Contributor</h3>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($submission['contributor_name'] ?? 'Anonymous'); ?></p>
                    <?php if (!empty($submission['contributor_email'])): ?>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($submission['contributor_email']); ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-400 mt-2"><?php echo htmlspecialchars($submission['submitted_at'] ?? ''); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-4">Actions</h3>
                    <form method="POST" class="space-y-3">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">✓ Approve & Publish</button>
                    </form>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700" onclick="return confirm('Reject this submission?');">✗ Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>hljs.highlightAll();</script>
</body>
</html>
