<?php
require_once __DIR__ . '/auth.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $codeSource = $_POST['code_source'] ?? 'paste';
    $category = $_POST['category'] ?? '';
    $difficulty = $_POST['difficulty'] ?? 'beginner';
    $z2mPart = trim($_POST['z2m_part'] ?? '');
    $author = trim($_POST['author'] ?? 'Z2M Codes');
    
    // If .ino file uploaded, read its contents
    if ($codeSource === 'upload' && !empty($_FILES['ino_file']['name']) && $_FILES['ino_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['ino_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'ino') {
            $code = trim(file_get_contents($_FILES['ino_file']['tmp_name']));
        }
    }
    
    if (empty($title)) {
        $error = 'Title is required.';
    } elseif (empty($code)) {
        $error = $codeSource === 'upload' 
            ? 'Please upload a valid .ino file, or switch to paste and enter code manually.' 
            : 'Code is required. Paste your code or upload an .ino file.';
    } elseif (!isset($categories[$category]) || $category === 'projects') {
        $error = 'Please select a valid category.';
    } else {
        $components = isset($_POST['components']) && is_array($_POST['components']) 
            ? array_values(array_filter($_POST['components'])) 
            : [];
        if (!empty($_POST['components_custom'])) {
            $custom = array_map('trim', array_filter(explode(',', $_POST['components_custom'])));
            $components = array_values(array_unique(array_merge($components, $custom)));
        }
        
        $imagePath = '';
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../assets/images/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = preg_replace('/[^a-z0-9_-]/i', '_', $title) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'assets/images/' . $filename;
                }
            }
        }
        if (empty($imagePath)) {
            $error = 'Circuit diagram is required. Please upload an image (JPG, PNG, GIF or WebP).';
        } else {
            $newCode = [
                'id' => getNextAdminCodeId(),
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'difficulty' => $difficulty,
                'tags' => [],
                'components' => $components,
                'z2m_part' => $z2mPart,
                'code' => $code,
                'author' => $author,
                'date' => date('Y-m-d'),
                'image' => $imagePath
            ];
            
            if (insertAdminCode($newCode)) {
                // Also add to Pending Submissions so it appears in both Codes and Pending list
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
                    'contributor_name' => $author,
                    'contributor_email' => '',
                    'source' => 'admin', // Already in Codes - just for visibility in Pending list
                ];
                addSubmission($submission);
                header('Location: ' . BASE_URL . '/admin/?success=added');
                exit;
            } else {
                $error = 'Failed to save project. Check file permissions.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - <?php echo SITE_NAME; ?> Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="<?php echo BASE_URL; ?>/assets/images/z2m.svg" alt="Zero2Maker" class="h-8 w-auto" />
                    <h1 class="text-xl font-bold text-gray-900">Add New Project</h1>
                </div>
                <div class="flex items-center gap-4">
                    <?php include __DIR__ . '/includes/notification-button.php'; ?>
                    <a href="<?php echo BASE_URL; ?>/admin/" class="text-gray-600 hover:text-purple-600">← Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-lg shadow p-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                <input type="text" id="title" name="title" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="description" name="description" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="category" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $key => $cat): ?>
                        <?php if ($key === 'projects') continue; ?>
                        <option value="<?php echo $key; ?>" <?php echo ($_POST['category'] ?? '') === $key ? 'selected' : ''; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                    <select id="difficulty" name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                        <?php foreach ($difficulty_levels as $key => $diff): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($_POST['difficulty'] ?? 'beginner') === $key ? 'selected' : ''; ?>>
                            <?php echo $diff['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="z2m_part" class="block text-sm font-medium text-gray-700 mb-1">Z2M Part Selection</label>
                <select id="z2m_part" name="z2m_part" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                    <?php foreach ($z2m_parts as $partNum => $partLabel): ?>
                    <option value="<?php echo htmlspecialchars($partNum); ?>" <?php echo ($_POST['z2m_part'] ?? '') === $partNum ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($partLabel); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Select the Z2M part number for this project</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Arduino/Code *</label>
                <div class="flex gap-4 mb-3">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="code_source" value="paste" id="code_source_paste" 
                               <?php echo ($_POST['code_source'] ?? 'paste') === 'paste' ? 'checked' : ''; ?>
                               class="mr-2" onchange="toggleCodeInput()">
                        <span>Paste code directly</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="code_source" value="upload" id="code_source_upload"
                               <?php echo ($_POST['code_source'] ?? '') === 'upload' ? 'checked' : ''; ?>
                               class="mr-2" onchange="toggleCodeInput()">
                        <span>Upload .ino file</span>
                    </label>
                </div>
                <div id="code_paste_section" class="code-input-section">
                    <textarea id="code" name="code" rows="20"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 font-mono text-sm"><?php echo htmlspecialchars($_POST['code'] ?? ''); ?></textarea>
                </div>
                <div id="code_upload_section" class="code-input-section hidden">
                    <input type="file" id="ino_file" name="ino_file" accept=".ino"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Upload your Arduino IDE .ino file. The code will be extracted automatically.</p>
                </div>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Circuit Diagram *</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF or WebP. Required – upload wiring/schematic diagram.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Components</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <?php foreach ($component_options as $comp): ?>
                    <label class="inline-flex items-center cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="components[]" value="<?php echo htmlspecialchars($comp); ?>"
                               class="mr-2 rounded border-gray-300 text-purple-600 focus:ring-purple-600"
                               <?php echo in_array($comp, $_POST['components'] ?? []) ? 'checked' : ''; ?>>
                        <span class="text-sm"><?php echo htmlspecialchars($comp); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div class="mt-2">
                    <input type="text" name="components_custom" placeholder="Add custom component (comma-separated)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm"
                           value="<?php echo htmlspecialchars($_POST['components_custom'] ?? ''); ?>">
                </div>
                <p class="text-xs text-gray-500 mt-1">Select from list or add custom components</p>
            </div>

            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                <input type="text" id="author" name="author"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                       value="<?php echo htmlspecialchars($_POST['author'] ?? 'Z2M Codes'); ?>">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700">
                    Add Project
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </main>
    <script>
    function toggleCodeInput() {
        var pasteSection = document.getElementById('code_paste_section');
        var uploadSection = document.getElementById('code_upload_section');
        var codeTextarea = document.getElementById('code');
        var inoFile = document.getElementById('ino_file');
        if (document.getElementById('code_source_paste').checked) {
            pasteSection.classList.remove('hidden');
            uploadSection.classList.add('hidden');
            codeTextarea.required = true;
            inoFile.required = false;
        } else {
            pasteSection.classList.add('hidden');
            uploadSection.classList.remove('hidden');
            codeTextarea.required = false;
            inoFile.required = true;
        }
    }
    toggleCodeInput(); // Run on load
    </script>
</body>
</html>
