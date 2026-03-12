-- Z2M Codes - MySQL Database Schema
-- Run this in phpMyAdmin or MySQL CLI to create the database

CREATE DATABASE IF NOT EXISTS z2m_codes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE z2m_codes;

-- Admin users (for admin panel login)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (email: admin@z2m.com, password: Admin123)
INSERT IGNORE INTO admin_users (id, email, password, name) VALUES 
(1, 'admin@z2m.com', '$2y$10$p3zAlLsZ13fu2iqwuJRdWO7LTtM1oUVK/qCevuMYVXJRMZh2.v1XO', 'Admin');

-- Regular users (for signup/login)
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin-added codes (replaces codes_added.json)
CREATE TABLE IF NOT EXISTS codes (
    id INT UNSIGNED NOT NULL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    difficulty VARCHAR(20) DEFAULT 'beginner',
    tags JSON,
    components JSON,
    z2m_part VARCHAR(50) DEFAULT '',
    code LONGTEXT NOT NULL,
    author VARCHAR(100) DEFAULT 'Zero2Maker',
    date DATE,
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_difficulty (difficulty)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pending contributor submissions (replaces submissions_pending.json)
CREATE TABLE IF NOT EXISTS submissions_pending (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    difficulty VARCHAR(20) DEFAULT 'beginner',
    tags JSON,
    components JSON,
    z2m_part VARCHAR(50) DEFAULT '',
    code LONGTEXT NOT NULL,
    image VARCHAR(255) DEFAULT '',
    contributor_name VARCHAR(100) DEFAULT '',
    contributor_email VARCHAR(150) DEFAULT '',
    source VARCHAR(20) DEFAULT 'contributor',
    status VARCHAR(20) DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
