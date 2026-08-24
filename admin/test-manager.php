<?php
session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_test'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $id = $_POST['test_id'] ?? null;
        
        if ($id) {
            $slug = generate_slug($db, $title, $id);
            $stmt = $db->prepare("UPDATE tests SET title=?, slug=?, description=? WHERE id=?");
            $stmt->execute([$title, $slug, $description, $id]);
            $msg = "Test güncellendi.";
        } else {
            $slug = generate_slug($db, $title);
            $stmt = $db->prepare("INSERT INTO tests (title, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$title, $slug, $description]);
            $msg = "Yeni test eklendi.";
        }
        $action = 'list';
    } elseif (isset($_POST['delete_test'])) {
        $stmt = $db->prepare("DELETE FROM tests WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $msg = "Test silindi.";
    } elseif (isset($_POST['add_question'])) {
        $stmt = $db->prepare("INSERT INTO test_questions (test_id, question_text) VALUES (?, ?)");
        $stmt->execute([$_POST['test_id'], $_POST['question_text']]);
        $msg = "Soru eklendi.";
    } elseif (isset($_POST['delete_question'])) {
        $stmt = $db->prepare("DELETE FROM test_questions WHERE id = ?");
        $stmt->execute([$_POST['delete_question_id']]);
        $msg = "Soru silindi.";
    } elseif (isset($_POST['add_option'])) {
        $stmt = $db->prepare("INSERT INTO test_options (question_id, option_text, score) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['question_id'], $_POST['option_text'], (int)$_POST['score']]);
        $msg = "Seçenek eklendi.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Test Yöneticisi</title>
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
        input[type=text], input[type=number], textarea { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .alert { padding: 15px; background: #dcfce7; color: #166534; border-radius: 4px; margin-bottom: 20px; }
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
    <?php if($msg): ?><div class="alert"><?php echo $msg; ?></div><?php endif; ?>

    <?php if($action == 'list'): ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Psikoloji Testleri</h1>
            <a href="?action=edit" class="btn">Yeni Test Ekle</a>
        </div>
        
        <div class="card">
            <table>
                <tr>
                    <th>Test Adı</th>
                    <th>Link</th>
                    <th>İşlemler</th>
                </tr>
                <?php 
                $stmt = $db->query("SELECT * FROM tests ORDER BY id DESC");
                $tests = $stmt->fetchAll();
                foreach($tests as $t): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['title']); ?></td>
                    <td><a href="<?php echo BASE_URL; ?>/test/<?php echo $t['slug']; ?>" target="_blank">/test/<?php echo $t['slug']; ?></a></td>
                    <td>
                        <a href="?action=manage_questions&id=<?php echo $t['id']; ?>" class="btn btn-primary">Soruları Yönet</a>
                        <a href="?action=edit&id=<?php echo $t['id']; ?>" class="btn">Düzenle</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="delete_id" value="<?php echo $t['id']; ?>">
                            <button type="submit" name="delete_test" class="btn btn-danger">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($tests)): ?>
                <tr><td colspan="3">Henüz test eklenmemiş.</td></tr>
                <?php endif; ?>
            </table>
        </div>

    <?php elseif($action == 'edit'): 
        $id = $_GET['id'] ?? null;
        $t = ['title'=>'', 'description'=>''];
        if ($id) {
            $stmt = $db->prepare("SELECT * FROM tests WHERE id = ?");
            $stmt->execute([$id]);
            $t = $stmt->fetch();
        }
    ?>
        <h1><?php echo $id ? 'Testi Düzenle' : 'Yeni Test Ekle'; ?></h1>
        <div class="card">
            <form method="POST">
                <input type="hidden" name="test_id" value="<?php echo $id; ?>">
                
                <label>Test Adı</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($t['title']); ?>" required>
                
                <label>Test Açıklaması</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($t['description']); ?></textarea>
                
                <button type="submit" name="save_test" class="btn">Kaydet</button>
            </form>
        </div>

    <?php elseif($action == 'manage_questions'): 
        $test_id = $_GET['id'];
        $stmt = $db->prepare("SELECT * FROM tests WHERE id = ?");
        $stmt->execute([$test_id]);
        $test = $stmt->fetch();
        
        $stmt = $db->prepare("SELECT * FROM test_questions WHERE test_id = ? ORDER BY id ASC");
        $stmt->execute([$test_id]);
        $questions = $stmt->fetchAll();
    ?>
        <h1>Soruları Yönet: <?php echo htmlspecialchars($test['title']); ?></h1>
        
        <div class="card">
            <h3>Yeni Soru Ekle</h3>
            <form method="POST" style="display:flex; gap:10px;">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                <input type="text" name="question_text" placeholder="Soru metni..." required style="margin-bottom:0;">
                <button type="submit" name="add_question" class="btn">Soru Ekle</button>
            </form>
        </div>

        <?php foreach($questions as $index => $q): ?>
        <div class="card" style="border-left: 4px solid var(--primary-color);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Soru <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></h3>
                <form method="POST" onsubmit="return confirm('Sil?');">
                    <input type="hidden" name="delete_question_id" value="<?php echo $q['id']; ?>">
                    <button type="submit" name="delete_question" class="btn btn-danger" style="padding: 5px 10px;">Soruyu Sil</button>
                </form>
            </div>
            
            <hr style="border:0; border-top:1px solid #eee; margin: 15px 0;">
            
            <h4>Seçenekler</h4>
            <ul style="list-style:none; padding:0;">
                <?php 
                $stmt = $db->prepare("SELECT * FROM test_options WHERE question_id = ?");
                $stmt->execute([$q['id']]);
                $options = $stmt->fetchAll();
                foreach($options as $opt):
                ?>
                <li style="margin-bottom: 5px; background:#f9f9f9; padding:10px; border-radius:4px;">
                    <?php echo htmlspecialchars($opt['option_text']); ?> <strong style="color:#ea580c;">(<?php echo $opt['score']; ?> Puan)</strong>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <form method="POST" style="display:flex; gap:10px; margin-top:15px; background:#f0f0f0; padding:15px; border-radius:4px;">
                <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                <input type="text" name="option_text" placeholder="Seçenek metni (Örn: Hiçbir zaman)" required style="margin-bottom:0;">
                <input type="number" name="score" placeholder="Puan (Örn: 0)" required style="margin-bottom:0; width:100px;">
                <button type="submit" name="add_option" class="btn btn-primary">Seçenek Ekle</button>
            </form>
        </div>
        <?php endforeach; ?>
        
    <?php endif; ?>
</div>

</body>
</html>
