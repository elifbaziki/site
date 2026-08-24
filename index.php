<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$route = isset($_GET['route']) ? rtrim($_GET['route'], '/') : '';

// Simple Router
if ($route == '' || $route == 'anasayfa' || $route == 'index') {
    $page = 'anasayfa.php';
} elseif ($route == 'admin' || strpos($route, 'admin/') === 0) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
} elseif ($route == 'submit-contact') {
    $page = 'submit-contact.php';
} elseif ($route == 'blog') {
    $page = 'blog-liste.php';
} elseif (preg_match('#^blog/(.+)$#', $route, $matches)) {
    $slug = $matches[1];
    $post = get_post_by_slug($db, $slug);
    if ($post) {
        $page = 'blog-detay.php';
    } else {
        $page = '404.php';
    }
} elseif (preg_match('#^test/(.+)$#', $route, $matches)) {
    $slug = $matches[1];
    $page = 'test-detay.php';
} else {
    // Check if it's a known static page
    $allowed_pages = ['hakkimda', 'hizmetler', 'iletisim', 'uzmanlik-alanlari'];
    if (in_array($route, $allowed_pages)) {
        $page = $route . '.php';
    } else {
        $page = '404.php';
    }
}

// Global variables for templates
$site_title = get_setting($db, 'site_title', 'Elif Baziki | Psikolog & Mental Performans Koçu');
$meta_description = get_setting($db, 'meta_description', 'Elif Baziki - Mental Performans Koçluğu ve Psikolojik Danışmanlık');

// Include the template
if (!isset($_SESSION['admin_logged_in'])) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $url = $_SERVER['REQUEST_URI'] ?? '/';
    $stmt = $db->prepare("INSERT INTO page_views (page_url, ip_address) VALUES (?, ?)");
    $stmt->execute([$url, $ip]);
}

$template_path = BASE_PATH . '/templates/' . $page;
if (file_exists($template_path)) {
    require $template_path;
} else {
    require BASE_PATH . '/templates/404.php';
}
