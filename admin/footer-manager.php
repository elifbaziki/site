<?php
session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_link'])) {
        $stmt = $db->prepare("INSERT INTO footer_links (column_name, link_title, link_url) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['column_name'], $_POST['link_title'], $_POST['link_url']]);
        $msg = "Link eklendi.";
    } elseif (isset($_POST['delete_link'])) {
        $stmt = $db->prepare("DELETE FROM footer_links WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $msg = "Link silindi.";
    }
}

// Fetch existing links grouped by column
$stmt = $db->query("SELECT * FROM footer_links ORDER BY column_name, sort_order ASC, id ASC");
$links_raw = $stmt->fetchAll();
$footer_data = [
    'kurumsal' => [],
    'yasal' => [],
    'hizli_erisim' => []
];
foreach($links_raw as $l) {
    if(isset($footer_data[$l['column_name']])) {
        $footer_data[$l['column_name']][] = $l;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Footer Yöneticisi</title>
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
        input[type=text], select { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .alert { padding: 15px; background: #dcfce7; color: #166534; border-radius: 4px; margin-bottom: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CMS Panel</h2>
    <a href="index.php?action=dashboard">Gösterge Paneli</a>
    <a href="blog-manager.php">Blog Yazıları</a>
    <a href="test-manager.php">Psikoloji Testleri</a>
    <a href="footer-manager.php">Footer Yönetimi</a>
    <a href="index.php?action=settings">Site SEO Ayarları</a>
    <a href="../" target="_blank">Siteyi Görüntüle</a>
</div>

<div class="content">
    <h1>Footer Link Yönetimi</h1>
    <?php if($msg): ?><div class="alert"><?php echo $msg; ?></div><?php endif; ?>

    <div class="card">
        <h3>Yeni Link Ekle</h3>
        <form method="POST" style="display:flex; gap: 15px; align-items:flex-end;">
            <div style="flex:1;">
                <label>Sütun (Kategori)</label>
                <select name="column_name">
                    <option value="kurumsal">Kurumsal</option>
                    <option value="yasal">Yasal Mevzuatlar</option>
                    <option value="hizli_erisim">Hızlı Erişim</option>
                </select>
            </div>
            <div style="flex:1;">
                <label>Link Başlığı (Örn: Hakkımızda)</label>
                <input type="text" name="link_title" required>
            </div>
            <div style="flex:1;">
                <label>Link URL'si (Örn: /hakkimda)</label>
                <input type="text" name="link_url" required>
            </div>
            <button type="submit" name="add_link" class="btn" style="margin-bottom: 15px;">Ekle</button>
        </form>
    </div>

    <div class="grid-3">
        <!-- Kurumsal -->
        <div class="card">
            <h3>Kurumsal</h3>
            <table>
                <?php foreach($footer_data['kurumsal'] as $link): ?>
                <tr>
                    <td><a href="<?php echo BASE_URL . $link['link_url']; ?>" target="_blank"><?php echo htmlspecialchars($link['link_title']); ?></a></td>
                    <td style="text-align:right;">
                        <form method="POST" onsubmit="return confirm('Sil?');">
                            <input type="hidden" name="delete_id" value="<?php echo $link['id']; ?>">
                            <button type="submit" name="delete_link" class="btn btn-danger" style="padding: 5px 10px;">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Yasal -->
        <div class="card">
            <h3>Yasal Mevzuatlar</h3>
            <table>
                <?php foreach($footer_data['yasal'] as $link): ?>
                <tr>
                    <td><a href="<?php echo BASE_URL . $link['link_url']; ?>" target="_blank"><?php echo htmlspecialchars($link['link_title']); ?></a></td>
                    <td style="text-align:right;">
                        <form method="POST" onsubmit="return confirm('Sil?');">
                            <input type="hidden" name="delete_id" value="<?php echo $link['id']; ?>">
                            <button type="submit" name="delete_link" class="btn btn-danger" style="padding: 5px 10px;">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Hizli -->
        <div class="card">
            <h3>Hızlı Erişim</h3>
            <table>
                <?php foreach($footer_data['hizli_erisim'] as $link): ?>
                <tr>
                    <td><a href="<?php echo BASE_URL . $link['link_url']; ?>" target="_blank"><?php echo htmlspecialchars($link['link_title']); ?></a></td>
                    <td style="text-align:right;">
                        <form method="POST" onsubmit="return confirm('Sil?');">
                            <input type="hidden" name="delete_id" value="<?php echo $link['id']; ?>">
                            <button type="submit" name="delete_link" class="btn btn-danger" style="padding: 5px 10px;">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
