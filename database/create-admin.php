<?php
/**
 * Run this once to create admin user in database
 * Usage: php create-admin.php
 * Or visit: http://localhost:8080/database/create-admin.php (if in project root)
 */
$email = 'admin@z2m.com';
$password = 'Admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Admin credentials:\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Hash: $hash\n\n";

// Try to connect and insert
$host = 'localhost';
$db = 'z2m_codes';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) DEFAULT 'Admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $pdo->prepare("INSERT INTO admin_users (email, password, name) VALUES (?, ?, 'Admin') ON DUPLICATE KEY UPDATE password = VALUES(password)");
    $stmt->execute([$email, $hash]);
    echo "Admin user created/updated successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Copy this SQL and run in phpMyAdmin:\n\n";
    echo "INSERT INTO admin_users (email, password, name) VALUES ('$email', '$hash', 'Admin');\n";
}
