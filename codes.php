<?php
require_once __DIR__ . '/config.php';

// Get filter parameters (support both clean URLs and query strings)
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$selected_difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : null;

// Redirect /projects?category=name to /projects/name so the full category URL appears in the address bar
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$path_part = parse_url($request_uri, PHP_URL_PATH);
$path_part = $path_part ? rtrim($path_part, '/') : '';
if ($selected_category && $selected_category !== 'projects' && (preg_match('#/projects$#', $path_part) || $path_part === '')) {
    $query = $_GET;
    unset($query['category']);
    $clean_path = rtrim(BASE_URL, '/') . '/projects/' . $selected_category;
    if (!empty($query)) {
        $clean_path .= '?' . http_build_query($query);
    }
    header('Location: ' . $clean_path, true, 301);
    exit;
}

// Backward compatibility: Redirect old codes.php?category=x to /projects/category-name
if (strpos($path_part ?? '', 'codes.php') !== false && $selected_category && $selected_category !== 'projects') {
    header('Location: ' . getCategoryUrl($selected_category), true, 301);
    exit;
}

// Filter codes: when a category is selected, show only projects from that category
$filter_category = null;
if ($selected_category !== null && $selected_category !== '' && $selected_category !== 'projects') {
    $filter_category = $selected_category;
}
$filtered_codes = filterCodes($filter_category, $selected_difficulty, $search_query);

// Page title
$page_title = 'All Projects';
if ($selected_category && isset($categories[$selected_category])) {
    $page_title = $categories[$selected_category]['name'] . ($selected_category === 'projects' ? '' : ' Projects');
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <?php 
            if ($selected_category && isset($categories[$selected_category])) {
                echo $categories[$selected_category]['icon'] . ' ' . $categories[$selected_category]['name'];
            } else {
                echo 'Code Library';
            }
            ?>
        </h1>
        <p class="text-xl text-purple-100">
            <?php 
            if ($selected_category && isset($categories[$selected_category])) {
                echo $categories[$selected_category]['description'];
            } else {
                echo 'Browse our collection of ' . count(getAllCodes()) . ' Arduino and programming codes';
            }
            ?>
        </p>
    </div>
</div>

<!-- Categories Sidebar + Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Category Sidebar - Properly visible list -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-24">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 px-2">Categories</h3>
                <nav class="space-y-0.5">
                    <a href="<?php echo getCodesUrl(); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition <?php echo (!$selected_category || $selected_category === 'projects') ? 'bg-purple-50 text-purple-600 font-medium' : ''; ?>">
                        <span class="text-xl mr-3">🚀</span>
                        All Projects
                    </a>
                    <?php foreach ($categories as $cat_key => $category): ?>
                    <?php if ($cat_key === 'projects') continue; ?>
                    <a href="<?php echo getCategoryUrl($cat_key); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition <?php echo $selected_category === $cat_key ? 'bg-purple-50 text-purple-600 font-medium' : ''; ?>">
                        <span class="text-xl mr-3"><?php echo $category['icon']; ?></span>
                        <?php echo $category['name']; ?>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
<!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="<?php echo ($selected_category && $selected_category !== 'projects') ? getCategoryUrl($selected_category) : getCodesUrl(); ?>" class="space-y-4" id="filter-form">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search_query ?? ''); ?>"
                       placeholder="Search codes by title, description, or tags..." 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Category Filter -->
                <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    <option value="" <?php echo ($selected_category === null || $selected_category === '' || $selected_category === 'projects') ? 'selected' : ''; ?>>All Projects</option>
                    <?php foreach ($categories as $cat_key => $category): ?>
                        <?php if ($cat_key === 'projects') continue; ?>
                        <option value="<?php echo htmlspecialchars($cat_key); ?>" <?php echo $selected_category === $cat_key ? 'selected' : ''; ?>>
                            <?php echo $category['icon'] . ' ' . htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Difficulty Filter -->
                <select name="difficulty" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    <option value="">All Difficulty Levels</option>
                    <?php foreach ($difficulty_levels as $diff_key => $difficulty): ?>
                        <option value="<?php echo $diff_key; ?>" <?php echo $selected_difficulty == $diff_key ? 'selected' : ''; ?>>
                            <?php echo $difficulty['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Submit Button -->
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                    Apply Filters
                </button>
            </div>
            
            <!-- Clear Filters -->
            <?php if ($selected_category || $selected_difficulty || $search_query): ?>
                <div class="text-center">
                    <a href="<?php echo getCodesUrl(); ?>" class="text-purple-600 hover:text-purple-700 font-medium">
                        Clear all filters
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

<!-- Results Count -->
<div class="mt-8">
    <p class="text-gray-600">
        Showing <span class="font-semibold text-gray-900"><?php echo count($filtered_codes); ?></span> 
        project<?php echo count($filtered_codes) != 1 ? 's' : ''; ?>
    </p>
</div>

<!-- Codes Grid -->
<div class="mt-6 mb-12">
    <?php if (count($filtered_codes) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($filtered_codes as $code): 
                $difficulty_color = $difficulty_levels[$code['difficulty'] ?? 'beginner']['color'];
            ?>
                <div class="code-card bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="badge badge-<?php echo $difficulty_color; ?>">
                                    <?php echo $difficulty_levels[$code['difficulty'] ?? 'beginner']['name']; ?>
                                </span>
                                <?php if (!empty(getCodeImagePath($code))): ?>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full flex items-center" title="Includes circuit diagram">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Diagram
                                </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-2xl">
                                <?php $catKey = $code['category'] ?? ''; echo isset($categories[$catKey]) ? $categories[$catKey]['icon'] : '📄'; ?>
                            </span>
                        </div>
                        
                        <!-- Title & Description -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?php echo $code['title']; ?>
                        </h3>
                        <?php 
                        $partNumber = getZ2MPartNumber($code);
                        if ($partNumber): 
                        ?>
                        <div class="mb-3">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Z2M Part Number:</span>
                            <a href="<?php echo htmlspecialchars(getCodeUrl($code), ENT_QUOTES, 'UTF-8'); ?>" class="ml-2 text-sm font-bold text-purple-600 hover:text-purple-700 hover:underline transition cursor-pointer" title="View code">
                                <?php echo $partNumber; ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?php echo htmlspecialchars($code['description'] ?? ''); ?>
                        </p>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach (array_slice($code['tags'] ?? [], 0, 3) as $tag): ?>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    #<?php echo $tag; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($code['date'] ?? 'now')); ?>
                            </span>
                            <a href="<?php echo htmlspecialchars(getCodeUrl($code), ENT_QUOTES, 'UTF-8'); ?>" 
                               class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition relative z-10"
                               title="View code for <?php echo htmlspecialchars($code['title'] ?? 'project'); ?>"
                               rel="nofollow">
                                View Code
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: 
        $total_available = count(getAllCodes());
    ?>
        <!-- No Results -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No codes found</h3>
            <p class="text-gray-600 mb-6"><?php echo $total_available > 0 ? 'No projects match your filters.' : 'No projects in database yet.'; ?> Try adjusting your filters or search query.</p>
            <a href="<?php echo getCodesUrl(); ?>" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                View All Codes
            </a>
        </div>
    <?php endif; ?>
</div>

        </div><!-- end main content -->
    </div><!-- end flex container -->
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

