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

// If root or empty, serve index.php
if (empty($requestPath) || $requestPath === '/') {
    require __DIR__ . '/index.php';
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

// /codes
if ($requestPath === 'codes' || $requestPath === 'all-codes') {
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
