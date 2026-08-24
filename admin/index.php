<?php
session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// 1st Layer: HTTP Basic Auth (as requested: password 'baziki')
// Using 'admin' as standard username for the prompt, password is 'baziki'
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_PW'] !== 'baziki') {
    header('WWW-Authenticate: Basic realm="Secure Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Yetkisiz erişim. Lütfen birinci katman şifresini girin.';
    exit;
}

// 2nd Layer: PHP Session Auth (elif / Elifbahar1)
$admin_user = 'elif';
$admin_pass = 'Elifbahar1';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit;
    }
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre hatalı.';
    }
}

$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// If not logged in, show login form
if (!$is_logged_in):
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>CMS Yönetim Girişi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .login-box h2 { margin-top: 0; color: #1a365d; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { display: block; margin-bottom: 5px; color: #333; font-size: 14px; font-weight: bold; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background: #ea580c; color: #fff; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #c2410c; }
        .error { color: red; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Yönetici Girişi</h2>
        <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label>Şifre</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
<?php 
exit; 
endif; 

// ---------------------------------------------------------
// LOGGED IN DASHBOARD
// ---------------------------------------------------------

$action = $_GET['action'] ?? 'dashboard';

// Handle Settings Save
if ($action == 'settings' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    set_setting($db, 'site_title', $_POST['site_title']);
    set_setting($db, 'meta_description', $_POST['meta_description']);
    $success = "Ayarlar başarıyla kaydedildi.";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>CMS Kontrol Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; color: #333; }
        .sidebar { width: 250px; background: #1a365d; color: white; position: fixed; top: 0; bottom: 0; left: 0; padding-top: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 20px; }
        .sidebar a { display: block; color: #fff; text-decoration: none; padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a:hover { background: #ea580c; }
        .content { margin-left: 250px; padding: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .btn { background: #ea580c; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; border:none; cursor:pointer;}
        .btn-danger { background: #dc2626; }
        input[type=text], textarea { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .alert { padding: 15px; background: #dcfce7; color: #166534; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CMS Panel</h2>
    <a href="?action=dashboard">Gösterge Paneli</a>
    <a href="blog-manager.php">Blog Yazıları</a>
    <a href="?action=settings">Site SEO Ayarları</a>
    <a href="../" target="_blank">Siteyi Görüntüle</a>
    <form method="POST" style="margin:0; padding:0;">
        <button type="submit" name="logout" style="width:100%; text-align:left; background:none; border:none; color:white; padding:15px 20px; cursor:pointer; font-size:16px; border-bottom: 1px solid rgba(255,255,255,0.1);">Çıkış Yap</button>
    </form>
</div>

<div class="content">
    <?php if(isset($success)): ?><div class="alert"><?php echo $success; ?></div><?php endif; ?>

    <?php if($action == 'dashboard'): 
        $post_count = $db->query("SELECT count(*) FROM blog_posts")->fetchColumn();
        $test_count = $db->query("SELECT count(*) FROM tests")->fetchColumn();
    ?>
        <h1>Hoş Geldiniz, Elif Hanım</h1>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="card" style="text-align: center; margin-bottom: 0;">
                <h3 style="margin-top:0; color: #666;">Toplam Blog Yazısı</h3>
                <div style="font-size: 3rem; font-weight: bold; color: var(--primary-color, #1a365d);"><?php echo $post_count; ?></div>
                <a href="blog-manager.php" class="btn" style="margin-top:15px;">Yazıları Yönet</a>
            </div>
            <div class="card" style="text-align: center; margin-bottom: 0;">
                <h3 style="margin-top:0; color: #666;">Aktif Psikoloji Testleri</h3>
                <div style="font-size: 3rem; font-weight: bold; color: var(--primary-color, #1a365d);"><?php echo $test_count; ?></div>
                <a href="test-manager.php" class="btn" style="margin-top:15px;">Testleri Yönet</a>
            </div>
        </div>
        
        <div class="card">
            <p>Sol menüden içeriklerinizi, footer linklerinizi veya site temel SEO ayarlarınızı değiştirebilirsiniz.</p>
        </div>
    <?php elseif($action == 'settings'): ?>
        <h1>Site SEO Ayarları</h1>
        <div class="card">
            <form method="POST">
                <label>Site Ana Başlığı (Meta Title)</label>
                <input type="text" name="site_title" value="<?php echo htmlspecialchars(get_setting($db, 'site_title', 'Elif Baziki | Psikolog & Mental Performans Koçu')); ?>">
                
                <label>Site Ana Açıklaması (Meta Description)</label>
                <textarea name="meta_description" rows="3"><?php echo htmlspecialchars(get_setting($db, 'meta_description', 'Elif Baziki - Mental Performans Koçluğu ve Psikolojik Danışmanlık')); ?></textarea>
                
                <button type="submit" class="btn">Ayarları Kaydet</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
