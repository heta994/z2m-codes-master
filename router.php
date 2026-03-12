<?php
// Router for PHP built-in server and Apache (subdirectory support)
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH) ?: '/';

// Remove query string from path for routing
$requestPath = strtok($requestPath, '?') ?: '/';

// Strip base path when app is in subdirectory (e.g. /z2m-codes-master/z2m-codes-master/)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptDir !== '/' && $scriptDir !== '\\' && $scriptDir !== '.' && !empty($scriptDir)) {
    $basePath = rtrim(str_replace('\\', '/', $scriptDir), '/');
    if ($basePath && strpos($requestPath, $basePath) === 0) {
        $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
    }
}

// Remove leading slash
$requestPath = ltrim($requestPath, '/');

// If root or empty - show home page (site)
if (empty($requestPath) || $requestPath === '/') {
    require __DIR__ . '/index.php';
    return true;
}

// User auth routes
if ($requestPath === 'login' || $requestPath === 'login.php') {
    require __DIR__ . '/auth/login.php';
    return true;
}
if ($requestPath === 'signup' || $requestPath === 'signup.php') {
    require __DIR__ . '/auth/signup.php';
    return true;
}
if ($requestPath === 'dashboard' || $requestPath === 'dashboard.php') {
    require __DIR__ . '/auth/dashboard.php';
    return true;
}
if ($requestPath === 'logout' || $requestPath === 'logout.php') {
    require __DIR__ . '/auth/logout.php';
    return true;
}

// Admin panel routes
if ($requestPath === 'admin' || $requestPath === 'admin/' || strpos($requestPath, 'admin') === 0) {
    $adminFile = preg_replace('#^admin/?#', '', $requestPath) ?: 'index.php';
    if (substr($adminFile, -4) !== '.php') {
        $adminFile = $adminFile === '' ? 'index.php' : $adminFile . '.php';
    }
    $adminPath = __DIR__ . '/admin/' . $adminFile;
    if (file_exists($adminPath)) {
        require $adminPath;
        return true;
    }
    require __DIR__ . '/admin/index.php';
    return true;
}

// Submit (contributor submission) - form and success page
if ($requestPath === 'submit' || $requestPath === 'submit/' || strpos($requestPath, 'submit') === 0) {
    $submitFile = preg_replace('#^submit/?#', '', $requestPath) ?: 'index.php';
    if (substr($submitFile, -4) !== '.php') {
        $submitFile = $submitFile === '' ? 'index.php' : $submitFile . '.php';
    }
    $submitPath = __DIR__ . '/submit/' . $submitFile;
    if (file_exists($submitPath)) {
        require $submitPath;
        return true;
    }
    require __DIR__ . '/submit/index.php';
    return true;
}

// Clean URL routing patterns
// /codes/category/arduino-basics/blink-led (Project - must be before category-only)
if (preg_match('#^codes/category/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['category'] = $matches[1];
    $_GET['slug'] = $matches[2];
    require __DIR__ . '/view-code.php';
    return true;
}

// /codes/category/sensors or /codes/category/projects (Category listing)
if (preg_match('#^codes/category/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/codes.php';
    return true;
}

// /codes/search/keyword
if (preg_match('#^codes/search/(.+)$#', $requestPath, $matches)) {
    $_GET['search'] = urldecode($matches[1]);
    require __DIR__ . '/codes.php';
    return true;
}

// /codes/difficulty/beginner
if (preg_match('#^codes/difficulty/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['difficulty'] = $matches[1];
    require __DIR__ . '/codes.php';
    return true;
}

// /codes/sensors/ultrasonic (Project)
if (preg_match('#^codes/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['category'] = $matches[1];
    $_GET['slug'] = $matches[2];
    require __DIR__ . '/view-code.php';
    return true;
}

// /codes/home
if ($requestPath === 'codes/home' || $requestPath === 'home') {
    require __DIR__ . '/index.php';
    return true;
}

// /codes, /projects, or /codes.php (Projects listing - all projects visible)
if ($requestPath === 'codes' || $requestPath === 'projects' || $requestPath === 'all-codes' || $requestPath === 'codes.php') {
    if (empty($_GET['category'])) {
        $_GET['category'] = 'projects';
    }
    require __DIR__ . '/codes.php';
    return true;
}

// Legacy: /codes/arduino-basics/blink-led -> redirect to /codes/category/arduino-basics/blink-led
if (preg_match('#^codes/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['category'] = $matches[1];
    $_GET['slug'] = $matches[2];
    require __DIR__ . '/view-code.php';
    return true;
}

// /codes/sensors (Category - Legacy/Alternative)
if (preg_match('#^codes/([a-zA-Z0-9-]+)$#', $requestPath, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/codes.php';
    return true;
}

// If PHP file exists, serve it
if (file_exists(__DIR__ . '/' . $requestPath) && pathinfo($requestPath, PATHINFO_EXTENSION) === 'php') {
    require __DIR__ . '/' . $requestPath;
    return true;
}

// If file exists, serve it (static files)
if (file_exists(__DIR__ . '/' . $requestPath)) {
    return false; // Let PHP server handle static files
}

// Default to index.php (404 fallback)
require __DIR__ . '/index.php';
return true;
?>
