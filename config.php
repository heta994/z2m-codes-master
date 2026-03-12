<?php
// Site Configuration
define('SITE_NAME', 'Z2M Codes');
define('SITE_DESCRIPTION', 'Your Arduino & Basic Programming Code Repository');
// Auto-detect base URL for both local and online use
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// When serving from /admin/*, /submit/*, or /projects/*, use site root to avoid duplicate paths in links
if ($path === '/admin' || preg_match('#/admin$#', $path) || $path === '/submit' || preg_match('#/submit$#', $path) || preg_match('#/projects$#', $path)) {
    $path = dirname($path);
}
// Normalize path for local and production
if ($path === '/' || $path === '\\' || $path === '.' || empty($path)) {
    $path = '';
} else {
    // Remove leading/trailing slashes and add single leading slash
    $path = '/' . trim($path, '/\\');
}
// Build BASE_URL - ensure it works for PHP built-in server and Apache
$baseUrl = rtrim($protocol . '://' . $host . $path, '/\\');
if (empty($baseUrl)) {
    $baseUrl = $protocol . '://' . $host;
}
define('BASE_URL', $baseUrl);

// Code Categories
$categories = [
    'arduino-basics' => [
        'name' => 'Arduino Basics',
        'icon' => '🔌',
        'description' => 'Fundamental Arduino programming concepts'
    ],
    'sensors' => [
        'name' => 'Sensors',
        'icon' => '📡',
        'description' => 'Working with various sensor modules'
    ],
    'motors' => [
        'name' => 'Motors & Servos',
        'icon' => '⚙️',
        'description' => 'Control motors and servo mechanisms'
    ],
    'leds' => [
        'name' => 'LEDs & Display',
        'icon' => '💡',
        'description' => 'LED patterns and display modules'
    ],
    'iot' => [
        'name' => 'IoT Projects',
        'icon' => '🌐',
        'description' => 'Internet of Things applications'
    ],
    'communication' => [
        'name' => 'Communication',
        'icon' => '📱',
        'description' => 'Serial, I2C, SPI, Bluetooth, WiFi'
    ],
    'projects' => [
        'name' => 'All Projects',
        'icon' => '🚀',
        'description' => 'Explore our complete library of Arduino and electronics projects'
    ]
];

// Difficulty Levels
$difficulty_levels = [
    'beginner' => ['name' => 'Beginner', 'color' => 'green'],
    'intermediate' => ['name' => 'Intermediate', 'color' => 'yellow'],
    'advanced' => ['name' => 'Advanced', 'color' => 'red']
];

// Admin credentials (change these in production!)
define('ADMIN_EMAIL', 'admin@z2m.com');
define('ADMIN_PASSWORD', 'Admin123');

// Z2M Part numbers for admin part selection dropdown
$z2m_parts = [
    '' => '-- Select Part (optional) --',
    'EMA-00004-A' => 'LED Matrix (EMA-00004-A)',
    'EDR-00002-20K0' => 'Potentiometer (EDR-00002-20K0)',
    'EMS-00014-A' => 'Touch Detector (EMS-00014-A)',
    'EMS-00007-A' => 'Digital Temperature (EMS-00007-A)',
    'EMS-00017-A' => 'LDR (EMS-00017-A)',
    'EMC-00005-A' => 'RFID (EMC-00005-A)',
    'EMS-00005-A' => 'Ultrasonic/HC-SR04 (EMS-00005-A)',
    'EDT-00007-A' => 'Flex Sensor (EDT-00007-A)',
    'EMA-00001-A' => 'LCD I2C (EMA-00001-A)',
    'EMS-00024-A' => 'Rain Sensor (EMS-00024-A)',
    'EMS-017-A' => 'Water Flow Sensor (EMS-017-A)',
    'EDT-00006-A' => 'Force Sensitive Resistor (EDT-00006-A)',
    'EMA-00008-A' => 'Relay Module (EMA-00008-A)',
    'EMS-00018-A' => 'Water Level Sensor (EMS-00018-A)',
    'EMS-00010-A' => 'Gas Sensor (EMS-00010-A)',
    'EMA-00007-A' => 'TM1637 4-Digit Display (EMA-00007-A)',
    'EMC-00006-A' => 'GSM/GPRS Module (EMC-00006-A)',
    'EMS-00008-A' => 'Metal Touch Sensor (EMS-00008-A)',
    'EMC-00001-A' => 'RF Transmitter/Receiver (EMC-00001-A)',
    'EMS-00003-B' => 'Accelerometer (EMS-00003-B)',
    'EMS-00009-A' => 'Heart Pulse Sensor (EMS-00009-A)',
    'EMA-00010-B' => 'Motor Driver (EMA-00010-B)',
    'EDT-00001-A' => 'Smoke/Gas Sensor (EDT-00001-A)',
    'EDS-00004-A' => 'Analog Temperature (EDS-00004-A)',
    'EMS-00002-A' => 'DHT11 Humidity (EMS-00002-A)',
    'EMA-00003-B' => 'NeoPixel (EMA-00003-B)',
    'EMS-00019-A' => 'Soil Moisture (EMS-00019-A)',
    'EMC-00008-A' => 'IR Remote (EMC-00008-A)',
    'EMC-00003-A' => 'NodeMCU (EMC-00003-A)',
    'EMS-00004-B' => 'Sound Detector (EMS-00004-B)',
    'EMS-00013-A' => 'IR Sensor (EMS-00013-A)',
    'EMA-00006-A' => 'Passive Buzzer (EMA-00006-A)',
    'EMC-00004-A' => 'Bluetooth Module (EMC-00004-A)',
    'EMS-00020-A' => 'Joystick (EMS-00020-A)',
    'EMS-00022-A' => 'Piezoelectric Sensor (EMS-00022-A)',
    'EMS-00004-A' => 'Sound Sensor (EMS-00004-A)',
    'EMS-016-A' => 'Capacitive Keypad (EMS-016-A)',
    'EDD-00001-A' => 'Laser Diode (EDD-00001-A)',
    'EMS-00006-A' => 'PIR Sensor (EMS-00006-A)',
    'EMS-00021-A' => 'Tilt Sensor (EMS-00021-A)',
    'EDM-00009-A' => 'Momentary Switch (EDM-00009-A)',
    'MMD-00002-A' => 'Servo Motor (MMD-00002-A)',
    'EDD-00004-A' => '7-Segment Display (EDD-00004-A)',
    'EMS-00012-A' => 'Color Sensor (EMS-00012-A)',
];

// Common components for admin Select Components
$component_options = [
    'Arduino Uno',
    'Arduino Nano',
    'NodeMCU/ESP8266',
    'ESP32',
    'LED',
    '220Ω Resistor',
    '10kΩ Resistor',
    '330Ω Resistor',
    'Breadboard',
    'Jumper Wires',
    'USB Cable',
    'Micro USB Cable',
    'DHT11 Sensor',
    'DHT22 Sensor',
    'Ultrasonic Sensor (HC-SR04)',
    'PIR Sensor',
    'LDR Sensor',
    'Servo Motor (SG90)',
    'Stepper Motor',
    'Motor Driver (L293D/L298N)',
    'Relay Module',
    'LCD I2C Display',
    '7-Segment Display',
    'NeoPixel LED Strip',
    'Potentiometer',
    'Push Button',
    'Buzzer',
    'RFID Module (MFRC522)',
    'Bluetooth Module (HC-05)',
    'GSM Module',
    'GPS Module',
    'SD Card Module',
    'Real-Time Clock (RTC)',
    'External Power Supply',
];

// Path to admin-added codes (JSON) - used when USE_MYSQL is false
define('CODES_ADDED_FILE', __DIR__ . '/data/codes_added.json');

// Path to pending submissions - used when USE_MYSQL is false
define('SUBMISSIONS_FILE', __DIR__ . '/data/submissions_pending.json');

// MySQL Database - set USE_MYSQL to true to use database (false = use JSON files)
define('USE_MYSQL', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'z2m_codes');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Get PDO connection (lazy)
function getDb() {
    static $pdo = null;
    if ($pdo === null && USE_MYSQL) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log('DB connection failed: ' . $e->getMessage());
            return null;
        }
    }
    return $pdo;
}

// Function to get pending submissions
function getPendingSubmissions() {
    $db = getDb();
    if ($db) {
        $stmt = $db->query("SELECT * FROM submissions_pending WHERE status = 'pending' ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id' => (int)$r['id'],
                'title' => $r['title'],
                'description' => $r['description'],
                'category' => $r['category'],
                'difficulty' => $r['difficulty'],
                'tags' => json_decode($r['tags'] ?? '[]', true) ?: [],
                'components' => json_decode($r['components'] ?? '[]', true) ?: [],
                'z2m_part' => $r['z2m_part'],
                'code' => $r['code'],
                'image' => $r['image'],
                'contributor_name' => $r['contributor_name'],
                'contributor_email' => $r['contributor_email'],
                'source' => $r['source'] ?? 'contributor',
                'submitted_at' => $r['submitted_at'],
            ];
        }
        return $out;
    }
    if (!file_exists(SUBMISSIONS_FILE)) return [];
    $json = file_get_contents(SUBMISSIONS_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

// Function to save pending submissions (file mode only)
function saveSubmissions($submissions) {
    if (getDb()) return true; // DB mode: no bulk save
    $dir = dirname(SUBMISSIONS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $result = file_put_contents(SUBMISSIONS_FILE, json_encode($submissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $result !== false;
}

// Function to add a new submission (returns new ID)
function addSubmission($data) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("INSERT INTO submissions_pending (title, description, category, difficulty, tags, components, z2m_part, code, image, contributor_name, contributor_email, source, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $tags = json_encode($data['tags'] ?? []);
        $components = json_encode($data['components'] ?? []);
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['category'],
            $data['difficulty'] ?? 'beginner',
            $tags,
            $components,
            $data['z2m_part'] ?? '',
            $data['code'],
            $data['image'] ?? '',
            $data['contributor_name'] ?? '',
            $data['contributor_email'] ?? '',
            $data['source'] ?? 'contributor',
        ]);
        return (int)$db->lastInsertId();
    }
    $submissions = getPendingSubmissions();
    $id = 1;
    foreach ($submissions as $s) {
        if (isset($s['id']) && $s['id'] >= $id) $id = $s['id'] + 1;
    }
    $data['id'] = $id;
    $data['status'] = 'pending';
    $data['submitted_at'] = date('Y-m-d H:i:s');
    $data['source'] = $data['source'] ?? 'contributor';
    $submissions[] = $data;
    saveSubmissions($submissions);
    return $id;
}

// Function to get submission by ID
function getSubmissionById($id) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("SELECT * FROM submissions_pending WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) return null;
        return [
            'id' => (int)$r['id'],
            'title' => $r['title'],
            'description' => $r['description'],
            'category' => $r['category'],
            'difficulty' => $r['difficulty'],
            'tags' => json_decode($r['tags'] ?? '[]', true) ?: [],
            'components' => json_decode($r['components'] ?? '[]', true) ?: [],
            'z2m_part' => $r['z2m_part'],
            'code' => $r['code'],
            'image' => $r['image'],
            'contributor_name' => $r['contributor_name'],
            'contributor_email' => $r['contributor_email'],
            'source' => $r['source'] ?? 'contributor',
            'submitted_at' => $r['submitted_at'],
        ];
    }
    $submissions = getPendingSubmissions();
    foreach ($submissions as $s) {
        if ($s['id'] == $id) return $s;
    }
    return null;
}

// Function to approve submission (move to codes, remove from pending)
function approveSubmission($id) {
    $submission = getSubmissionById($id);
    if (!$submission) return false;
    // Admin-added items are already in Codes - just remove from pending
    if (($submission['source'] ?? '') === 'admin') {
        return rejectSubmission($id);
    }
    $db = getDb();
    if ($db) {
        try {
            $db->beginTransaction();
            $newId = getNextAdminCodeId();
            $stmt = $db->prepare("INSERT INTO codes (id, title, description, category, difficulty, tags, components, z2m_part, code, author, date, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $newId,
                $submission['title'],
                $submission['description'],
                $submission['category'],
                $submission['difficulty'] ?? 'beginner',
                json_encode($submission['tags'] ?? []),
                json_encode($submission['components'] ?? []),
                $submission['z2m_part'] ?? '',
                $submission['code'],
                $submission['contributor_name'] ?? 'Contributor',
                date('Y-m-d'),
                $submission['image'] ?? '',
            ]);
            $db->prepare("DELETE FROM submissions_pending WHERE id = ?")->execute([$id]);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
    $codes = getAdminCodes();
    $newCode = [
        'id' => getNextAdminCodeId(),
        'title' => $submission['title'],
        'description' => $submission['description'],
        'category' => $submission['category'],
        'difficulty' => $submission['difficulty'] ?? 'beginner',
        'tags' => $submission['tags'] ?? [],
        'components' => $submission['components'] ?? [],
        'z2m_part' => $submission['z2m_part'] ?? '',
        'code' => $submission['code'],
        'author' => $submission['contributor_name'] ?? 'Contributor',
        'date' => date('Y-m-d'),
        'image' => $submission['image'] ?? ''
    ];
    $codes[] = $newCode;
    if (!saveAdminCodes($codes)) return false;
    $submissions = getPendingSubmissions();
    $submissions = array_values(array_filter($submissions, function($s) use ($id) { return $s['id'] != $id; }));
    return saveSubmissions($submissions);
}

// Function to reject submission (remove from pending)
function rejectSubmission($id) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("DELETE FROM submissions_pending WHERE id = ?");
        return $stmt->execute([$id]);
    }
    $submissions = getPendingSubmissions();
    $submissions = array_values(array_filter($submissions, function($s) use ($id) { return $s['id'] != $id; }));
    return saveSubmissions($submissions);
}

// Function to get admin-added codes (from MySQL or JSON file)
function getAdminCodes() {
    $out = [];
    $db = getDb();
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM codes ORDER BY id ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $out[] = [
                    'id' => (int)$r['id'],
                    'title' => $r['title'],
                    'description' => $r['description'],
                    'category' => $r['category'],
                    'difficulty' => $r['difficulty'],
                    'tags' => json_decode($r['tags'] ?? '[]', true) ?: [],
                    'components' => json_decode($r['components'] ?? '[]', true) ?: [],
                    'z2m_part' => $r['z2m_part'],
                    'code' => $r['code'],
                    'author' => $r['author'],
                    'date' => $r['date'],
                    'image' => $r['image'],
                ];
            }
        } catch (Exception $e) {
            // DB error - fall through to file
        }
    }
    // Also load from JSON (fallback or when DB empty - merges legacy data)
    if (file_exists(CODES_ADDED_FILE)) {
        $json = file_get_contents(CODES_ADDED_FILE);
        $fileCodes = json_decode($json, true);
        if (is_array($fileCodes)) {
            $existingIds = array_column($out, 'id');
            foreach ($fileCodes as $c) {
                if (!in_array($c['id'] ?? 0, $existingIds)) {
                    $out[] = $c;
                    $existingIds[] = $c['id'] ?? 0;
                }
            }
        }
    }
    usort($out, function($a, $b) { return ($a['id'] ?? 0) - ($b['id'] ?? 0); });
    return $out;
}

// Function to save admin-added codes (bulk - for file mode)
function saveAdminCodes($codes) {
    $db = getDb();
    if ($db) return true; // DB uses insert/update/delete
    $dir = dirname(CODES_ADDED_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $result = file_put_contents(CODES_ADDED_FILE, json_encode($codes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $result !== false;
}

// Insert new admin code (add.php)
function insertAdminCode($code) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("INSERT INTO codes (id, title, description, category, difficulty, tags, components, z2m_part, code, author, date, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $code['id'],
            $code['title'],
            $code['description'],
            $code['category'],
            $code['difficulty'] ?? 'beginner',
            json_encode($code['tags'] ?? []),
            json_encode($code['components'] ?? []),
            $code['z2m_part'] ?? '',
            $code['code'],
            $code['author'] ?? 'Zero2Maker',
            $code['date'] ?? date('Y-m-d'),
            $code['image'] ?? '',
        ]);
    }
    $codes = getAdminCodes();
    $codes[] = $code;
    return saveAdminCodes($codes);
}

// Update admin code (edit.php)
function updateAdminCode($id, $code) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("UPDATE codes SET title=?, description=?, category=?, difficulty=?, tags=?, components=?, z2m_part=?, code=?, author=?, date=?, image=? WHERE id=?");
        return $stmt->execute([
            $code['title'],
            $code['description'],
            $code['category'],
            $code['difficulty'] ?? 'beginner',
            json_encode($code['tags'] ?? []),
            json_encode($code['components'] ?? []),
            $code['z2m_part'] ?? '',
            $code['code'],
            $code['author'] ?? 'Zero2Maker',
            $code['date'] ?? date('Y-m-d'),
            $code['image'] ?? '',
            $id,
        ]);
    }
    $codes = getAdminCodes();
    foreach ($codes as $i => $c) {
        if ($c['id'] == $id) {
            $codes[$i] = array_merge($c, $code);
            $codes[$i]['id'] = $id;
            return saveAdminCodes($codes);
        }
    }
    return false;
}

// Delete admin code (delete.php)
function deleteAdminCode($id) {
    $db = getDb();
    if ($db) {
        $stmt = $db->prepare("DELETE FROM codes WHERE id = ?");
        return $stmt->execute([$id]);
    }
    $codes = array_values(array_filter(getAdminCodes(), function($c) use ($id) { return $c['id'] != $id; }));
    return saveAdminCodes($codes);
}

// Function to get next ID for admin-added codes (start from 10000 to avoid conflict)
function getNextAdminCodeId() {
    $db = getDb();
    if ($db) {
        $r = $db->query("SELECT COALESCE(MAX(id), 9999) as mx FROM codes")->fetch(PDO::FETCH_ASSOC);
        return (int)$r['mx'] + 1;
    }
    $codes = getAdminCodes();
    $max = 9999;
    foreach ($codes as $c) {
        if (isset($c['id']) && $c['id'] > $max) $max = $c['id'];
    }
    return $max + 1;
}

// Function to get all codes (merged: built-in PHP + admin-added from DB/JSON)
function getAllCodes() {
    $dataFile = __DIR__ . '/data/codes.php';
    $builtIn = [];
    if (file_exists($dataFile)) {
        $phpCodes = include $dataFile;
        $builtIn = is_array($phpCodes) ? $phpCodes : [];
    }
    $adminCodes = getAdminCodes();
    return array_merge($builtIn, $adminCodes);
}

// Function to get code by ID
function getCodeById($id) {
    $codes = getAllCodes();
    foreach ($codes as $code) {
        if ($code['id'] == $id) {
            return $code;
        }
    }
    return null;
}

// Function to get next code within the same category (by ID order)
function getNextCode($currentId, $category) {
    $codes = getAllCodes();
    $found = false;
    foreach ($codes as $code) {
        if ($code['category'] !== $category) {
            continue;
        }
        if ($found) {
            return $code;
        }
        if ($code['id'] == $currentId) {
            $found = true;
        }
    }
    return null;
}

// Function to get previous code within the same category (by ID order)
function getPrevCode($currentId, $category) {
    $codes = getAllCodes();
    $prev = null;
    foreach ($codes as $code) {
        if ($code['category'] !== $category) {
            continue;
        }
        if ($code['id'] == $currentId) {
            return $prev;
        }
        $prev = $code;
    }
    return null;
}

// Function to create URL-friendly slug from title
function createSlug($text) {
    // Convert to lowercase
    $text = strtolower($text);
    // Replace non-alphanumeric characters with hyphens
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Remove leading/trailing hyphens
    $text = trim($text, '-');
    return $text;
}

// Function to generate clean code URL (codes/category/category/slug)
function getCodeUrl($code) {
    $id = $code['id'] ?? 0;
    return BASE_URL . '/projects/view.php?id=' . (int)$id;
}

// Function to generate category URL (clean URL)
function getCategoryUrl($categoryKey) {
    return BASE_URL . '/projects?category=' . urlencode($categoryKey);
}

// Function to generate difficulty URL (clean URL)
function getDifficultyUrl($difficultyKey) {
    return BASE_URL . '/projects?difficulty=' . urlencode($difficultyKey);
}

// Function to generate search URL (clean URL)
function getSearchUrl($searchQuery) {
    return BASE_URL . '/projects?search=' . urlencode($searchQuery);
}

// Function to generate codes page URL (clean URL)
function getCodesUrl() {
    return BASE_URL . '/projects';
}

// Function to generate home URL
function getHomeUrl() {
    return rtrim(BASE_URL, '/') . '/';
}

// Function to filter codes
function filterCodes($category = null, $difficulty = null, $search = null) {
    $codes = getAllCodes();
    $filtered = [];
    
    foreach ($codes as $code) {
        $match = true;
        
        if ($category && $code['category'] != $category) {
            $match = false;
        }
        
        if ($difficulty && $code['difficulty'] != $difficulty) {
            $match = false;
        }
        
        if ($search) {
            $searchLower = strtolower($search);
            $titleMatch = strpos(strtolower($code['title'] ?? ''), $searchLower) !== false;
            $descMatch = strpos(strtolower($code['description'] ?? ''), $searchLower) !== false;
            $tagsMatch = false;
            foreach ($code['tags'] ?? [] as $tag) {
                if (strpos(strtolower($tag), $searchLower) !== false) {
                    $tagsMatch = true;
                    break;
                }
            }
            if (!$titleMatch && !$descMatch && !$tagsMatch) {
                $match = false;
            }
        }
        
        if ($match) {
            $filtered[] = $code;
        }
    }
    
    return $filtered;
}

// Function to get Z2M Part Number based on code title/components or stored part
function getZ2MPartNumber($code) {
    if (isset($code['z2m_part']) && !empty($code['z2m_part'])) {
        return $code['z2m_part'];
    }
    $title = strtolower($code['title']);
    $components = isset($code['components']) ? $code['components'] : [];
    
    // Component mapping based on title and components
    $mapping = [
        // Sensors
        'color sensor' => 'EMS-00012-A',
        'led matrix' => 'EMA-00004-A',
        'potentiometer' => 'EDR-00002-20K0',
        'touch detector' => 'EMS-00014-A',
        'digital temperature' => 'EMS-00007-A, EDS-00003-A',
        'light dependent resistor' => 'EMS-00017-A',
        'ldr' => 'EMS-00017-A',
        'rfid' => 'EMC-00005-A',
        'ultrasonic' => 'EMS-00005-A',
        'hc-sr04' => 'EMS-00005-A',
        'flex sensor' => 'EDT-00007-A',
        'lcd i2c' => 'EMA-00001-A',
        'rain sensor' => 'EMS-00024-A',
        'waterflow sensor' => 'EMS-017-A',
        'water flow' => 'EMS-017-A',
        'force sensitive resistance' => 'EDT-00006-A',
        'fsr' => 'EDT-00006-A',
        'membrane keypad' => 'EMS-00015-B, EMS-00015-C',
        'relay module' => 'EMA-00008-A',
        'water level sensor' => 'EMS-00018-A',
        'gas sensor' => 'EMS-00010-A to G, EDT-00001-A to G',
        '4 digit display 7 segment' => 'EMA-00007-A',
        'tm1637' => 'EMA-00007-A',
        'gprs gsm module' => 'EMC-00006-A',
        'gsm' => 'EMC-00006-A',
        'metal touch sensor' => 'EMS-00008-A',
        'rf transmitter' => 'EMC-00001-A',
        'rf receiver' => 'EMC-00001-A',
        'accelerometer' => 'EMS-00003-B',
        'heart pulse rate sensor' => 'EMS-00009-A',
        'motor driver' => 'EMA-00010-B',
        'smoke sensor' => 'EDT-00001-A to G',
        'analog temperature sensor' => 'EDS-00004-A',
        'humidity sensor' => 'EDT-00005-A',
        'dht11' => 'EDT-00005-A',
        'neopixel' => 'EMA-00003-B to E',
        'soil sensor' => 'EMS-00019-A',
        'soil moisture' => 'EMS-00019-A',
        'ir remote' => 'EMC-00008-A/B / EDS-00005-A',
        'nodemcu' => 'EMC-00003-A',
        'sound detector' => 'EMS-00004-B',
        'ir sensor' => 'EMS-00013-A',
        'infrared detector' => 'EMS-00013-A',
        'infrared sensor' => 'EMS-00013-A',
        'passive buzzer' => 'EMA-00006-A / EDT-00008-A',
        'sound recorder' => 'EMA-00009-A',
        'bluetooth module' => 'EMC-00004-A',
        'joystick' => 'EMS-00020-A',
        'piezoelectric sensor' => 'EMS-00022-A / EDT-00003-A',
        'sound sensor' => 'EMS-00004-A / EMS-00004-C',
        'capacitive keypad' => 'EMS-016-A',
        'laser diode' => 'EDD-00001-A',
        'pir sensor' => 'EMS-00006-A',
        'tilt sensor' => 'EMS-00021-A',
        'momentary switch' => 'EDM-00009-A',
        'momentary switch led toggle' => 'EDM-00009-A',
        'switch led toggle' => 'EDM-00009-A',
        'servo motor' => 'MMD-00002-A, MMD-00002-B',
        'servo' => 'MMD-00002-A, MMD-00002-B',
        '7 segment 1 digit' => 'EDD-00004-A, EDD-00004-B',
        'seven segment 1digit display' => 'EDD-00004-A, EDD-00004-B',
        'seven segment 1digit' => 'EDD-00004-A, EDD-00004-B',
        'seven segment' => 'EDD-00004-A, EDD-00004-B',
        '7 segment 1 digit display device' => 'EDD-00004-A, EDD-00004-B',
        '7 segment display' => 'EDD-00004-A, EDD-00004-B',
        '1 digit display' => 'EDD-00004-A, EDD-00004-B',
        '1digit display' => 'EDD-00004-A, EDD-00004-B',
        '1 digit display device' => 'EDD-00004-A, EDD-00004-B',
        'force sensitive resistor' => 'EDT-00006-A',
    ];
    
    // Check title for matches
    foreach ($mapping as $keyword => $partNumber) {
        if (strpos($title, $keyword) !== false) {
            return $partNumber;
        }
    }
    
    // Check components array for matches
    if (!empty($components)) {
        foreach ($components as $component) {
            $componentLower = strtolower($component);
            foreach ($mapping as $keyword => $partNumber) {
                if (strpos($componentLower, $keyword) !== false) {
                    return $partNumber;
                }
            }
        }
    }
    
    // Default return null if no match
    return null;
}

// Placeholder for missing circuit diagrams (dynamic with project title)
define('PLACEHOLDER_IMAGE', 'assets/images/placeholder-circuit.svg');

// Function to automatically get image path based on code title/components
// Returns path if file exists, else placeholder when code has image ref, else empty
function getCodeImagePath($code) {
    $title = strtolower($code['title'] ?? '');
    $components = isset($code['components']) ? $code['components'] : [];
    $basePath = __DIR__ . '/';
    
    // If image already set in code, check if file exists
    if (isset($code['image']) && !empty($code['image'])) {
        $path = $code['image'];
        return file_exists($basePath . $path) ? $path : PLACEHOLDER_IMAGE;
    }
    
    // Image mapping based on titles/components
    $imageMapping = [
        'accelerometer' => 'assets/images/Accelerometer_Readings.png',
        'analog temperature' => 'assets/images/analog temp module cirucuit.png',
        'analog temp module' => 'assets/images/analog temp module cirucuit.png',
        'analog temp breadboard' => 'assets/images/analog temp bread boardcirucuit.png',
        'temperature sensor' => 'assets/images/analog temp module cirucuit.png',
        'bluetooth' => 'assets/images/Bluetooth_Module_Control.png',
        'capacitive keypad' => 'assets/images/Capacitive_Keypad.png',
        'color sensor' => 'assets/images/color sensor.png',
        'digital temperature' => 'assets/images/digital temp module.png',
        'digital temp module' => 'assets/images/digital temp module.png',
        'digital temp breadboard' => 'assets/images/digital temp breadboard circuit.png',
        'digital temp bread board' => 'assets/images/digital temp breadboard circuit.png',
        'flex sensor' => 'assets/images/flex sensor.png',
        'force sensitive' => 'assets/images/Force_Sensitive_Resistor.png',
        'fsr' => 'assets/images/Force_Sensitive_Resistor.png',
        'gas sensor' => 'assets/images/gas sensor.png',
        'gps module' => 'assets/images/GPS_Module_Interface.png',
        'gsm' => 'assets/images/GSM_GPRS_AT_Commands.png',
        'gprs' => 'assets/images/GSM_GPRS_AT_Commands.png',
        'heart pulse' => 'assets/images/Heart_Pulse_Rate_Sensor.png',
        'humidity' => 'assets/images/humidity with resistor.png',
        'humidity with resistor' => 'assets/images/humidity with resistor.png',
        'humidity without resistor' => 'assets/images/humidity without resistor.png',
        'dht11' => 'assets/images/humidity with resistor.png',
        'dht22' => 'assets/images/humidity without resistor.png',
        'ir remote' => 'assets/images/IR_Remote_Receiver.png',
        'ir sensor' => 'assets/images/ir sensor.png',
        'infrared sensor' => 'assets/images/ir sensor.png',
        'joystick' => 'assets/images/joystick.png',
        'laser diode' => 'assets/images/Laser_Diode.png',
        'ldr' => 'assets/images/ldr module circuit.png',
        'ldr module' => 'assets/images/ldr module circuit.png',
        'ldr breadboard' => 'assets/images/ldr breadboard circuit.png',
        'ldr bread board' => 'assets/images/ldr breadboard circuit.png',
        'light dependent resistor' => 'assets/images/ldr module circuit.png',
        'light dependent resistor module' => 'assets/images/ldr module circuit.png',
        'light dependent resistor breadboard' => 'assets/images/ldr breadboard circuit.png',
        'lcd i2c' => 'assets/images/Liquid Crystal Display.png',
        'lcd' => 'assets/images/Liquid Crystal Display.png',
        'liquid crystal display' => 'assets/images/Liquid Crystal Display.png',
        'led matrix' => 'assets/images/LED_Matrix.png',
        'membrane keypad' => 'assets/images/Membrane_Keypad.png',
        'metal touch' => 'assets/images/Metal_Touch_Sensor.png',
        'momentary switch' => 'assets/images/Momentary_Switch_LED_Toggle.png',
        'momentary switch led toggle' => 'assets/images/Momentary_Switch_LED_Toggle.png',
        'switch led toggle' => 'assets/images/Momentary_Switch_LED_Toggle.png',
        'led toggle' => 'assets/images/Momentary_Switch_LED_Toggle.png',
        'nodemcu' => 'assets/images/NodeMCU_LDR_ThingSpeak.png',
        'motor driver' => 'assets/images/Motor_Driver_Control.png',
        'neopixel' => 'assets/images/NeoPixel_LED_Strip.png',
        'passive buzzer' => 'assets/images/Passive_Buzzer.png',
        'passive buzzer1' => 'assets/images/Passive_Buzzer1.png',
        'buzzer' => 'assets/images/Passive_Buzzer.png',
        'piezoelectric' => 'assets/images/Piezoelectric_Sensor.png',
        'pir sensor' => 'assets/images/PIR_Sensor.png',
        'potentiometer' => 'assets/images/Potentiometer.png',
        'rain sensor' => 'assets/images/rain sensor.png',
        'relay' => 'assets/images/Relay_Module.png',
        'rf transmitter' => 'assets/images/RF_Transmitter.png',
        'rf receiver' => 'assets/images/RF_Receiver.png',
        'rfid' => 'assets/images/RFID.png',
        'servo motor' => 'assets/images/servo motor.png',
        'servo' => 'assets/images/servo motor.png',
        'smoke sensor' => 'assets/images/smoke sensor.png',
        'soil' => 'assets/images/Soil_Moisture_Sensor.png',
        'sound detector' => 'assets/images/sound detector.png',
        'sound recorder' => 'assets/images/Sound_Recorder.png',
        'sound sensor' => 'assets/images/sound sensor.png',
        'tilt sensor' => 'assets/images/Tilt_Sensor.png',
        'touch detector' => 'assets/images/touch detector.png',
        'ultrasonic' => 'assets/images/ultrasonic sensor.png',
        'ultrasonic sensor' => 'assets/images/ultrasonic sensor.png',
        'hc-sr04' => 'assets/images/ultrasonic sensor.png',
        'water level' => 'assets/images/water level.png',
        'water flow' => 'assets/images/Water_Flow_Sensor.png',
        'waterflow sensor' => 'assets/images/Water_Flow_Sensor.png',
        '7 segment' => 'assets/images/Seven_Segment_1Digit_Display.png',
        'seven segment' => 'assets/images/Seven_Segment_1Digit_Display.png',
        'seven segment 1digit display' => 'assets/images/Seven_Segment_1Digit_Display.png',
        'seven segment 1digit' => 'assets/images/Seven_Segment_1Digit_Display.png',
        '7 segment 1 digit' => 'assets/images/Seven_Segment_1Digit_Display.png',
        '7 segment 1 digit display' => 'assets/images/Seven_Segment_1Digit_Display.png',
        'segment 1digit' => 'assets/images/Seven_Segment_1Digit_Display.png',
        'segment 1 digit' => 'assets/images/Seven_Segment_1Digit_Display.png',
        '4 digit' => 'assets/images/4 digit 7 segment.png',
        '4 digit 7 segment' => 'assets/images/4 digit 7 segment.png',
        'tm1637' => 'assets/images/4 digit 7 segment.png',
        'tm1637 4digit display' => 'assets/images/4 digit 7 segment.png',
        '4digit display' => 'assets/images/4 digit 7 segment.png',
        'water level sensor' => 'assets/images/water level.png',
        'smoke gas sensor' => 'assets/images/smoke sensor.png',
        'gas sensor module' => 'assets/images/gas sensor.png',
        'soil moisture sensor' => 'assets/images/Soil_Moisture_Sensor.png',
        'wifi web server' => 'assets/images/NodeMCU_LDR_ThingSpeak.png',
        'esp8266' => 'assets/images/NodeMCU_LDR_ThingSpeak.png',
        'nodemcu ldr thingspeak' => 'assets/images/NodeMCU_LDR_ThingSpeak.png',
        'gps module interface' => 'assets/images/GPS_Module_Interface.png',
        'gsm gprs at commands' => 'assets/images/GSM_GPRS_AT_Commands.png',
        'humidity sensor dht11' => 'assets/images/humidity with resistor.png',
        'ultrasonic led distance indicator' => 'assets/images/ultrasonic sensor.png',
        'rfid radiofrequencyidentification' => 'assets/images/RFID.png',
        'button debouncing' => 'assets/images/Momentary_Switch_LED_Toggle.png',
        'blink led' => 'assets/images/blink led.jpg',
    ];
    
    // Check title for matches
    foreach ($imageMapping as $keyword => $imagePath) {
        if (strpos($title, $keyword) !== false) {
            return file_exists($basePath . $imagePath) ? $imagePath : PLACEHOLDER_IMAGE;
        }
    }
    
    // Check components array for matches
    if (!empty($components)) {
        foreach ($components as $component) {
            $componentLower = strtolower($component);
            foreach ($imageMapping as $keyword => $imagePath) {
                if (strpos($componentLower, $keyword) !== false) {
                    return file_exists($basePath . $imagePath) ? $imagePath : PLACEHOLDER_IMAGE;
                }
            }
        }
    }
    
    // Show placeholder for projects with no image (so all projects have diagram section)
    return PLACEHOLDER_IMAGE;
}

