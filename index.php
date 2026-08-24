<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$route = isset($_GET['route']) ? rtrim($_GET['route'], '/') : '';

// Simple Router
if ($route == '' || $route == 'home' || $route == 'index') {
    $page = 'home.php';
} elseif ($route == 'admin' || strpos($route, 'admin/') === 0) {
    // Admin routing handled directly by accessing the /admin directory
    // But if somehow it passes here, redirect to /admin/
    header('Location: ' . BASE_URL . '/admin/');
    exit;
} elseif ($route == 'blog') {
    $page = 'blog-list.php';
} elseif (preg_match('#^blog/(.+)$#', $route, $matches)) {
    // Blog Single
    $slug = $matches[1];
    $post = get_post_by_slug($db, $slug);
    if ($post) {
        $page = 'blog-single.php';
    } else {
        $page = '404.php';
    }
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
$template_path = BASE_PATH . '/templates/' . $page;
if (file_exists($template_path)) {
    require $template_path;
} else {
    require BASE_PATH . '/templates/404.php';
}
