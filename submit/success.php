<?php
/**
 * STEP 3: Submission Success Page
 * Shown after contributor successfully submits their project.
 */
require_once __DIR__ . '/../config.php';

$id = intval($_GET['id'] ?? 0);
$page_title = 'Submission Received';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-green-50 border border-green-200 rounded-xl p-8">
        <div class="text-6xl mb-4">✓</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Thank You!</h1>
        <p class="text-gray-600 mb-6">Your project has been submitted successfully. Our team will review it and publish it to the code library soon.</p>
        <p class="text-sm text-gray-500 mb-8">Submission ID: #<?php echo $id; ?></p>
        <div class="flex gap-4 justify-center">
            <a href="<?php echo BASE_URL; ?>/" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700">Back to Home</a>
            <a href="<?php echo getCodesUrl(); ?>" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300">Browse Projects</a>
            <a href="<?php echo getHomeUrl(); ?>" class="text-purple-600 hover:text-purple-700 font-semibold">← Home</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
