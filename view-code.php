<?php
require_once __DIR__ . '/config.php';

// Get code ID or slug from URL (backward compatible with query strings)
$code_id = 0;
$code = null;

// Try to get ID from clean URL or query string
if (isset($_GET['id'])) {
    $code_id = intval($_GET['id']);
    $code = getCodeById($code_id);
}
// Try to get by slug (if using pretty URL /projects/category/slug)
elseif (isset($_GET['slug']) && trim((string)$_GET['slug']) !== '') {
    $slug = createSlug(trim((string)$_GET['slug']));
    $category = isset($_GET['category']) ? trim((string)$_GET['category']) : null;
    // Support id-based fallback slug: project-123
    if (preg_match('/^project-(\d+)$/', $slug, $m)) {
        $byId = getCodeById((int)$m[1]);
        if ($byId && ($category === null || $category === '' || ($byId['category'] ?? '') === $category)) {
            $code = $byId;
        }
    }
    if (!$code) {
        $all_codes = getAllCodes();
        foreach ($all_codes as $c) {
            $cSlug = createSlug($c['title'] ?? '');
            if ($cSlug !== '' && $cSlug === $slug) {
                if ($category !== null && $category !== '' && ($c['category'] ?? '') !== $category) {
                    continue;
                }
                $code = $c;
                break;
            }
        }
    }
}

// Redirect if code not found
if (!$code) {
    header('Location: ' . getCodesUrl());
    exit;
}

// Redirect old ?id= URLs to pretty URL /projects/category/project-name
if (isset($_GET['id']) && !isset($_GET['slug'])) {
    $prettyUrl = getCodeUrl($code);
    header('Location: ' . $prettyUrl, true, 301);
    exit;
}

$page_title = $code['title'];
$difficulty_color = $difficulty_levels[$code['difficulty'] ?? 'beginner']['color'];
$codeCategory = $code['category'] ?? 'projects';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="<?php echo getHomeUrl(); ?>" class="text-gray-500 hover:text-purple-600">Home</a>
                </li>
                <li>
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="<?php echo getCategoryUrl($codeCategory); ?>" class="text-gray-500 hover:text-purple-600">
                        <?php echo isset($categories[$codeCategory]) ? $categories[$codeCategory]['name'] : 'Projects'; ?>
                    </a>
                </li>
                <li>
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-700 font-medium"><?php echo $code['title']; ?></span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Code Details -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="badge badge-<?php echo $difficulty_color; ?>">
                        <?php echo $difficulty_levels[$code['difficulty']]['name']; ?>
                    </span>
                    <span class="text-sm text-gray-500">
                        Updated: <?php echo date('M d, Y', strtotime($code['date'] ?? 'now')); ?>
                    </span>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php echo $code['title']; ?>
                </h1>
                
                <?php 
                $partNumbers = getZ2MPartNumbers($code);
                if (!empty($partNumbers)): 
                ?>
                <div class="mb-4">
                    <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Z2M Part<?php echo count($partNumbers) > 1 ? 's' : ''; ?>:</span>
                    <span class="ml-2 text-lg font-bold text-purple-600"><?php echo htmlspecialchars(implode(', ', $partNumbers)); ?></span>
                </div>
                <?php endif; ?>
                
                <p class="text-xl text-gray-600 mb-6">
                    <?php echo $code['description']; ?>
                </p>
                
                <!-- Tags -->
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($code['tags'] ?? [] as $tag): ?>
                        <span class="text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                            #<?php echo $tag; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Circuit Diagram Image (only show when project has a real diagram, not placeholder) -->
            <?php 
            $imagePath = getCodeImagePath($code);
            $isPlaceholder = ($imagePath === PLACEHOLDER_IMAGE);
            if (!empty($imagePath) && !$isPlaceholder): 
                $imgSrc = BASE_URL . '/' . $imagePath;
            ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gray-800 px-6 py-4">
                    <span class="text-white font-semibold">Circuit Diagram</span>
                </div>
                <div class="p-4 flex justify-center bg-gray-50">
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                         alt="<?php echo htmlspecialchars($code['title']); ?> Circuit Diagram" 
                         class="max-w-full h-auto rounded-lg shadow-sm border border-gray-200">
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Code Block -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gray-800 px-6 py-4 flex items-center justify-between">
                    <span class="text-white font-semibold">Arduino Code</span>
                    <button type="button" id="copy-code-btn" onclick="copyCode(this)" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span id="copy-code-label">Copy Code</span>
                    </button>
                </div>
                <pre class="overflow-x-auto p-4 text-sm"><code id="code-block" class="language-arduino block"><?php echo htmlspecialchars($code['code'] ?? ''); ?></code></pre>
            </div>
            
            <!-- How to Use -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded-r-lg">
                <h3 class="text-lg font-bold text-blue-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    How to Use
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-900">
                    <li>Connect the required components as per the circuit diagram</li>
                    <li>Open Arduino IDE and create a new sketch</li>
                    <li>Copy and paste the code above</li>
                    <li>Select your Arduino board and COM port from Tools menu</li>
                    <li>Click the Upload button to upload the code to your Arduino</li>
                    <li>Open Serial Monitor (if applicable) to see the output</li>
                </ol>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Components Required -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6 sticky top-20">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Components Required
                </h3>
                <ul class="space-y-2">
                    <?php foreach ($code['components'] ?? [] as $component): ?>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700"><?php echo $component; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <hr class="my-6">
                
                <!-- Info -->
                <div class="space-y-3">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="font-medium">Category:</span>
                        <span class="ml-2"><?php echo isset($categories[$codeCategory]) ? $categories[$codeCategory]['name'] : 'Projects'; ?></span>
                    </div>
                    
                </div>
                
                <hr class="my-6">
                
                <!-- Actions -->
                <div class="space-y-3">
                    <a href="<?php echo getCategoryUrl($codeCategory); ?>" 
                       class="block text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold transition">
                        More from this Category
                    </a>
                    <?php 
                    $prevCode = getPrevCode($code['id'], $codeCategory);
                    $nextCode = getNextCode($code['id'], $codeCategory);
                    if ($prevCode || $nextCode): ?>
                    <div class="flex gap-3">
                    <?php if ($prevCode): ?>
                    <a href="<?php echo getCodeUrl($prevCode); ?>" 
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-semibold transition">
                        ← Previous Project
                    </a>
                    <?php endif; ?>
                    <?php if ($nextCode): ?>
                    <a href="<?php echo getCodeUrl($nextCode); ?>" 
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-semibold transition">
                        Next Project →
                    </a>
                    <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCode(buttonEl) {
    var codeBlock = document.getElementById('code-block');
    var label = document.getElementById('copy-code-label');
    if (!codeBlock || !label) return;
    var text = (codeBlock.textContent || codeBlock.innerText || '').trim();
    var btn = buttonEl && buttonEl.nodeName === 'BUTTON' ? buttonEl : (buttonEl && buttonEl.closest ? buttonEl.closest('button') : null);
    
    function showDone() {
        if (label) label.textContent = 'Copied!';
        if (btn) btn.classList.add('bg-green-600');
        setTimeout(function() {
            if (label) label.textContent = 'Copy Code';
            if (btn) btn.classList.remove('bg-green-600');
        }, 2000);
    }
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(showDone).catch(function() {
            fallbackCopy();
        });
    } else {
        fallbackCopy();
    }
    
    function fallbackCopy() {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            showDone();
        } catch (e) {}
        document.body.removeChild(ta);
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

