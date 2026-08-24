<?php
session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: messages.php?deleted=1");
    exit;
}

if (isset($_GET['read'])) {
    $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$_GET['read']]);
    header("Location: messages.php");
    exit;
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İletişim Mesajları</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; }
        .sidebar { width: 250px; background: #1a365d; color: white; position: fixed; top: 0; bottom: 0; left: 0; padding-top: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 20px; }
        .sidebar a { display: block; color: #fff; text-decoration: none; padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a:hover { background: #ea580c; }
        .content { margin-left: 250px; padding: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .btn-danger { background: #dc2626; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn-success { background: #16a34a; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>CMS Panel</h2>
    <a href="index.php?action=dashboard">Gösterge Paneli</a>
    <a href="messages.php" style="background: #ea580c;">İletişim Mesajları</a>
    <a href="blog-manager.php">Blog Yazıları</a>
    <a href="index.php?action=settings">Site SEO Ayarları</a>
    <a href="../" target="_blank">Siteyi Görüntüle</a>
</div>
<div class="content">
    <h1>İletişim Mesajları</h1>
    <div class="card">
        <table>
            <tr>
                <th>Tarih</th>
                <th>İsim</th>
                <th>E-posta</th>
                <th>Konu</th>
                <th>Mesaj</th>
                <th>İşlem</th>
            </tr>
            <?php foreach($messages as $m): ?>
            <tr style="<?php echo $m['is_read'] ? 'opacity: 0.7;' : 'font-weight:bold; background:#f0f9ff;'; ?>">
                <td><?php echo $m['created_at']; ?></td>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><?php echo htmlspecialchars($m['email']); ?></a></td>
                <td><?php echo htmlspecialchars($m['subject']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                <td>
                    <?php if(!$m['is_read']): ?>
                        <a href="?read=<?php echo $m['id']; ?>" class="btn-success">Okundu İşaretle</a>
                    <?php endif; ?>
                    <a href="?delete=<?php echo $m['id']; ?>" class="btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($messages)): ?>
            <tr><td colspan="6">Henüz mesaj yok.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
