<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="<?php echo htmlspecialchars($meta_description ?? get_setting($db, 'meta_description')); ?>">
    <?php if(isset($meta_keywords) && !empty($meta_keywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <?php endif; ?>
    
    <!-- Open Graph for Social Media -->
    <meta property="og:title" content="<?php echo htmlspecialchars($site_title ?? get_setting($db, 'site_title')); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description ?? get_setting($db, 'meta_description')); ?>">
    <meta property="og:image" content="<?php echo BASE_URL . ($og_image ?? '/assets/images/elif-yeni.jpg'); ?>">
    <meta property="og:type" content="website">

    <title><?php echo isset($site_title) ? $site_title : 'Elif Baziki'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/favicon.png">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Mobile Sticky CTA */
        .mobile-sticky-cta {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #ea580c; /* McLaren Orange */
            color: white;
            text-align: center;
            padding: 15px 0;
            font-weight: 700;
            font-size: 1.1rem;
            z-index: 9999;
            text-decoration: none;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
        }
        .mobile-sticky-cta i { margin-right: 8px; }
        
        @media (max-width: 900px) {
            .mobile-sticky-cta { display: block; }
            body { padding-bottom: 60px; /* Space for sticky CTA */ }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?php echo BASE_URL; ?>" class="logo" style="display:flex; flex-direction:column; line-height:1.2; align-items:center; text-align:center;">
                Elif Baziki
                <span style="font-size: 0.65rem; font-weight: 600; color: #777; letter-spacing: 2px; text-transform: uppercase;">KLİNİK PSİKOLOG & MENTAL PERFORMANS KOÇU</span>
            </a>
            
            <button class="mobile-menu-btn" aria-label="Menü">
                <svg width="28" height="10" viewBox="0 0 28 10" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="1.5" fill="currentColor"/><rect y="8.5" width="28" height="1.5" fill="currentColor"/></svg>
            </button>
            <div class="nav-links">
                <a href="<?php echo BASE_URL; ?>">Anasayfa</a>
                <a href="<?php echo BASE_URL; ?>/hakkimda">Hakkında</a>
                <a href="<?php echo BASE_URL; ?>/uzmanlik-alanlari" class="hide-on-mobile">Uzmanlık Alanlarım</a>
                <a href="<?php echo BASE_URL; ?>/hizmetler">Hizmetler</a>
                <a href="<?php echo BASE_URL; ?>/blog">Blog</a>
                <a href="<?php echo BASE_URL; ?>/iletisim">İletişim</a>
                <a href="https://wa.me/905307200396" target="_blank" class="btn btn-primary" style="display: none !important;">Online Terapi</a>
            </div>
        </div>
    </nav>
