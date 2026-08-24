<?php
session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// Auth Check (No Basic Auth check needed here because index.php handles entry, but let's be safe)
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_PW'] !== 'baziki') {
    header('WWW-Authenticate: Basic realm="Secure Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $msg = "Yazı başarıyla silindi.";
    } elseif (isset($_POST['save_post'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $meta_description = $_POST['meta_description'];
        $focus_keyword = $_POST['focus_keyword'];
        $keywords = $_POST['keywords'];
        $featured_image = $_POST['featured_image']; // URL for now
        $id = $_POST['post_id'] ?? null;
        
        if ($id) {
            $slug = generate_slug($db, $title, $id);
            $stmt = $db->prepare("UPDATE blog_posts SET title=?, slug=?, content=?, meta_description=?, focus_keyword=?, keywords=?, featured_image=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
            $stmt->execute([$title, $slug, $content, $meta_description, $focus_keyword, $keywords, $featured_image, $id]);
            $msg = "Yazı güncellendi.";
        } else {
            $slug = generate_slug($db, $title);
            $stmt = $db->prepare("INSERT INTO blog_posts (title, slug, content, meta_description, focus_keyword, keywords, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $content, $meta_description, $focus_keyword, $keywords, $featured_image]);
            $msg = "Yeni yazı eklendi.";
        }
        $action = 'list';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Blog Yöneticisi</title>
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
        .btn-primary { background: #1a365d; }
        input[type=text], textarea { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .alert { padding: 15px; background: #dcfce7; color: #166534; border-radius: 4px; margin-bottom: 20px; }
        
        /* Simple layout for editor */
        .flex-row { display: flex; gap: 20px; }
        .flex-col { flex: 1; }
    </style>
    <!-- Trumbowyg Editor (Lightweight HTML Editor) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css">
</head>
<body>

<div class="sidebar">
    <h2>CMS Panel</h2>
    <a href="index.php?action=dashboard">Gösterge Paneli</a>
    <a href="blog-manager.php">Blog Yazıları</a>
    <a href="index.php?action=settings">Site SEO Ayarları</a>
    <a href="../" target="_blank">Siteyi Görüntüle</a>
</div>

<div class="content">
    <?php if($msg): ?><div class="alert"><?php echo $msg; ?></div><?php endif; ?>

    <?php if($action == 'list'): ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Blog Yazıları</h1>
            <a href="?action=edit" class="btn">Yeni Yazı Ekle</a>
        </div>
        
        <div class="card">
            <table>
                <tr>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
                <?php 
                $posts = get_all_posts($db);
                foreach($posts as $p): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['title']); ?></td>
                    <td><?php echo date('d.m.Y H:i', strtotime($p['created_at'])); ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-primary">Düzenle</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="btn btn-danger">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($posts)): ?>
                <tr><td colspan="3">Henüz blog yazısı eklenmemiş.</td></tr>
                <?php endif; ?>
            </table>
        </div>

    <?php elseif($action == 'edit'): 
        $id = $_GET['id'] ?? null;
        $p = ['title'=>'', 'content'=>'', 'meta_description'=>'', 'focus_keyword'=>'', 'keywords'=>'', 'featured_image'=>''];
        if ($id) {
            $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            $p = $stmt->fetch();
        }
    ?>
        <h1><?php echo $id ? 'Yazıyı Düzenle' : 'Yeni Yazı Ekle'; ?></h1>
        <form method="POST">
            <input type="hidden" name="post_id" value="<?php echo $id; ?>">
            <div class="flex-row">
                <div class="flex-col" style="flex: 2;">
                    <div class="card">
                        <label>Başlık</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($p['title']); ?>" required>
                        
                        <label>İçerik (HTML)</label>
                        <textarea id="editor" name="content"><?php echo htmlspecialchars($p['content']); ?></textarea>
                    </div>
                </div>
                <div class="flex-col" style="flex: 1;">
                    <div class="card">
                        <h3>SEO ve Meta Verileri</h3>
                        <label>Meta Description</label>
                        <textarea name="meta_description" rows="3"><?php echo htmlspecialchars($p['meta_description']); ?></textarea>
                        
                        <label>Odak Anahtar Kelime (Focus Keyword)</label>
                        <input type="text" name="focus_keyword" value="<?php echo htmlspecialchars($p['focus_keyword']); ?>" placeholder="Örn: Mental Performans">
                        <small style="color:#666; display:block; margin-top:-10px; margin-bottom:15px;">Bu kelime otomatik iç linkleme (internal link) için kullanılacaktır.</small>
                        
                        <label>Etiketler (Keywords - virgülle ayırın)</label>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($p['keywords']); ?>">
                        
                        <label>Öne Çıkan Görsel Linki (URL)</label>
                        <input type="text" name="featured_image" value="<?php echo htmlspecialchars($p['featured_image']); ?>" placeholder="/assets/images/blog1.jpg">
                        <small style="color:#666; display:block; margin-top:-10px; margin-bottom:15px;">Şimdilik klasördeki resmin yolunu yazınız.</small>
                        
                        <button type="submit" name="save_post" class="btn" style="width: 100%; margin-top:20px;">Kaydet / Yayınla</button>
                    </div>
                </div>
            </div>
        </form>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
        <script>
            $('#editor').trumbowyg({
                svgPath: 'https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/icons.svg'
            });
        </script>
    <?php endif; ?>
</div>

</body>
</html>
