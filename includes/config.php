<?php
// Absolute path to the CMS root
define('BASE_PATH', dirname(__DIR__));

// Dynamic Base URL detection (useful for local dev and moving domains)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $domainName;
// If the app is in a subfolder, you might need to append the subfolder path. Assuming root for now.
define('BASE_URL', $base_url);

// Database configuration
define('DB_PATH', BASE_PATH . '/content/database.sqlite');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to initialize tables if they don't exist
function initDB($db) {
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        setting_key TEXT PRIMARY KEY,
        setting_value TEXT
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE,
        title TEXT,
        meta_description TEXT,
        focus_keyword TEXT,
        keywords TEXT,
        featured_image TEXT,
        content TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS footer_links (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        column_name TEXT,
        link_title TEXT,
        link_url TEXT,
        sort_order INTEGER DEFAULT 0
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS tests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE,
        title TEXT,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS test_questions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        test_id INTEGER,
        question_text TEXT,
        sort_order INTEGER DEFAULT 0,
        FOREIGN KEY(test_id) REFERENCES tests(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS test_options (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        question_id INTEGER,
        option_text TEXT,
        score INTEGER DEFAULT 0,
        FOREIGN KEY(question_id) REFERENCES test_questions(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS test_results (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        test_id INTEGER,
        min_score INTEGER,
        max_score INTEGER,
        result_text TEXT,
        FOREIGN KEY(test_id) REFERENCES tests(id) ON DELETE CASCADE
    )");
}

// Initialize tables
initDB($db);

// Helper function to get settings
function get_setting($db, $key, $default = '') {
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// Helper function to set settings
function set_setting($db, $key, $value) {
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                          ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value");
    $stmt->execute([$key, $value]);
}

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
