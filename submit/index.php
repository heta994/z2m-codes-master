<?php
/**
 * STEP 2: Public Submission Form
 * Contributors can submit code projects here (no login required).
 * Submissions go to pending queue for admin review.
 */
require_once __DIR__ . '/../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $codeSource = $_POST['code_source'] ?? 'paste';
    $category = $_POST['category'] ?? '';
    $difficulty = $_POST['difficulty'] ?? 'beginner';
    $z2mPart = trim($_POST['z2m_part'] ?? '');
    $contributorName = trim($_POST['contributor_name'] ?? '');
    $contributorEmail = trim($_POST['contributor_email'] ?? '');
    
    if ($codeSource === 'upload' && !empty($_FILES['ino_file']['name']) && $_FILES['ino_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['ino_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'ino') {
            $code = trim(file_get_contents($_FILES['ino_file']['tmp_name']));
        }
    }
    
    $components = isset($_POST['components']) && is_array($_POST['components']) 
        ? array_values(array_filter($_POST['components'])) 
        : [];
    
    $imagePath = '';
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = 'sub_' . preg_replace('/[^a-z0-9_-]/i', '_', $title) . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $imagePath = 'assets/images/' . $filename;
            }
        }
    }
    
    if (empty($title)) {
        $error = 'Title is required.';
    } elseif (empty($code)) {
        $error = $codeSource === 'upload' ? 'Please upload a valid .ino file.' : 'Code is required.';
    } elseif (!isset($categories[$category]) || $category === 'projects') {
        $error = 'Please select a valid category.';
    } elseif (empty($imagePath)) {
        $error = 'Circuit diagram is required. Please upload an image.';
    } else {
        $submission = [
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'difficulty' => $difficulty,
            'tags' => [],
            'components' => $components,
            'z2m_part' => $z2mPart,
            'code' => $code,
            'image' => $imagePath,
            'contributor_name' => $contributorName,
            'contributor_email' => $contributorEmail
        ];
        $id = addSubmission($submission);
        header('Location: ' . BASE_URL . '/submit/success.php?id=' . $id);
        exit;
    }
}

$page_title = 'Submit Your Project';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Submit Your Project</h1>
        <p class="text-xl text-purple-100">Share your Arduino/electronics code with the community. Your submission will be reviewed by our team before publishing.</p>
    </div>
</div>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            
            <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                    <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea id="description" name="description" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                            <option value="">Select category</option>
                            <?php foreach ($categories as $key => $cat): ?>
                            <?php if ($key === 'projects') continue; ?>
                            <option value="<?php echo $key; ?>" <?php echo ($_POST['category'] ?? '') === $key ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                        <select id="difficulty" name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                            <?php foreach ($difficulty_levels as $key => $diff): ?>
                            <option value="<?php echo $key; ?>" <?php echo ($_POST['difficulty'] ?? 'beginner') === $key ? 'selected' : ''; ?>><?php echo $diff['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="z2m_part" class="block text-sm font-medium text-gray-700 mb-1">Z2M Part (optional)</label>
                    <select id="z2m_part" name="z2m_part" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                        <?php foreach ($z2m_parts as $partNum => $partLabel): ?>
                        <option value="<?php echo htmlspecialchars($partNum); ?>"><?php echo htmlspecialchars($partLabel); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                    <div class="flex gap-4 mb-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="code_source" value="paste" checked class="mr-2" onchange="toggleCodeInput()"> Paste code
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="code_source" value="upload" class="mr-2" onchange="toggleCodeInput()"> Upload .ino file
                        </label>
                    </div>
                    <div id="code_paste_section">
                        <textarea id="code" name="code" rows="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 font-mono text-sm"><?php echo htmlspecialchars($_POST['code'] ?? ''); ?></textarea>
                    </div>
                    <div id="code_upload_section" class="hidden">
                        <input type="file" id="ino_file" name="ino_file" accept=".ino" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Circuit Diagram *</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Components</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <?php foreach ($component_options as $comp): ?>
                        <label class="inline-flex items-center cursor-pointer hover:bg-gray-100 p-1 rounded">
                            <input type="checkbox" name="components[]" value="<?php echo htmlspecialchars($comp); ?>" class="mr-2 rounded">
                            <span class="text-sm"><?php echo htmlspecialchars($comp); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contributor_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name (optional)</label>
                        <input type="text" id="contributor_name" name="contributor_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                               value="<?php echo htmlspecialchars($_POST['contributor_name'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="contributor_email" class="block text-sm font-medium text-gray-700 mb-1">Your Email (optional)</label>
                        <input type="email" id="contributor_email" name="contributor_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                               value="<?php echo htmlspecialchars($_POST['contributor_email'] ?? ''); ?>">
                    </div>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition">Submit for Review</button>
            </form>
        </div>
    </main>
    <script>
    function toggleCodeInput() {
        var paste = document.getElementById('code_paste_section');
        var upload = document.getElementById('code_upload_section');
        var codeTa = document.getElementById('code');
        var inoFile = document.getElementById('ino_file');
        if (document.querySelector('input[name="code_source"]:checked').value === 'paste') {
            paste.classList.remove('hidden'); upload.classList.add('hidden');
            codeTa.required = true; inoFile.required = false;
        } else {
            paste.classList.add('hidden'); upload.classList.remove('hidden');
            codeTa.required = false; inoFile.required = true;
        }
    }
    toggleCodeInput();
    </script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
