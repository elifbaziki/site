<?php
require_once dirname(__DIR__) . "/includes/config.php";
$db->exec("CREATE TABLE IF NOT EXISTS contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT,
    subject TEXT,
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read INTEGER DEFAULT 0
)");

$db->exec("CREATE TABLE IF NOT EXISTS page_views (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_url TEXT,
    ip_address TEXT,
    view_date DATE DEFAULT (date('now'))
)");
echo "DB Updated.";

