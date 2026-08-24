<?php
// $slug is injected by index.php
$stmt = $db->prepare("SELECT * FROM tests WHERE slug = ?");
$stmt->execute([$slug]);
$test = $stmt->fetch();

if (!$test) {
    header("Location: " . BASE_URL . "/404");
    exit;
}

$site_title = htmlspecialchars($test['title']) . ' | ' . get_setting($db, 'site_title');
require_once BASE_PATH . '/includes/header.php'; 

// Fetch Questions
$stmt = $db->prepare("SELECT * FROM test_questions WHERE test_id = ? ORDER BY id ASC");
$stmt->execute([$test['id']]);
$questions = $stmt->fetchAll();

// Handle Submission
$result_score = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_test'])) {
    $result_score = 0;
    foreach ($questions as $q) {
        $selected_option_id = $_POST['q_' . $q['id']] ?? null;
        if ($selected_option_id) {
            $stmt = $db->prepare("SELECT score FROM test_options WHERE id = ?");
            $stmt->execute([$selected_option_id]);
            $result_score += (int)$stmt->fetchColumn();
        }
    }
}
?>

<section class="test-single" style="padding: 120px 0 80px; background: #f9f9f9; min-height: 70vh;">
    <div class="container" style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        
        <h1 style="font-size: 2.2rem; color: var(--primary-color); margin-bottom: 20px; text-align: center;">
            <?php echo htmlspecialchars($test['title']); ?>
        </h1>
        
        <?php if($result_score === null): ?>
            <p style="font-size: 1.1rem; color: #555; text-align: center; margin-bottom: 40px;">
                <?php echo nl2br(htmlspecialchars($test['description'])); ?>
            </p>
            
            <form method="POST">
                <?php foreach($questions as $index => $q): ?>
                <div class="question-block" style="margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; color: #333; margin-bottom: 15px;">
                        <?php echo ($index + 1) . ". " . htmlspecialchars($q['question_text']); ?>
                    </h3>
                    
                    <?php 
                    $stmt = $db->prepare("SELECT * FROM test_options WHERE question_id = ?");
                    $stmt->execute([$q['id']]);
                    $options = $stmt->fetchAll();
                    foreach($options as $opt):
                    ?>
                    <label style="display: block; margin-bottom: 10px; cursor: pointer; padding: 10px; background: #f4f7f6; border-radius: 4px;">
                        <input type="radio" name="q_<?php echo $q['id']; ?>" value="<?php echo $opt['id']; ?>" required style="margin-right: 10px;">
                        <?php echo htmlspecialchars($opt['option_text']); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                
                <div style="text-align: center; margin-top: 40px;">
                    <button type="submit" name="submit_test" class="btn btn-primary btn-large" style="padding: 15px 40px; font-size: 1.2rem;">Testi Bitir ve Sonucu Gör</button>
                </div>
            </form>
            
        <?php else: ?>
            
            <div class="test-result" style="text-align: center; padding: 30px;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #166534; margin-bottom: 20px;"></i>
                <h2 style="font-size: 2rem; color: #333; margin-bottom: 10px;">Test Tamamlandı!</h2>
                <div style="font-size: 1.5rem; color: var(--primary-color); font-weight: bold; margin-bottom: 30px;">
                    Toplam Puanınız: <span style="background: var(--accent-color); color: white; padding: 5px 15px; border-radius: 20px;"><?php echo $result_score; ?></span>
                </div>
                
                <div style="background: #f4f7f6; padding: 20px; border-radius: 8px; font-size: 1.1rem; color: #555; margin-bottom: 30px; text-align: left;">
                    <p>Bu test bir uzman teşhisi yerine geçmez. Çıkan sonuçlar sadece bir öz-değerlendirme (farkındalık) aracıdır.</p>
                    <p>Eğer hissettikleriniz günlük yaşantınızı etkiliyorsa, profesyonel bir destek almaktan çekinmeyin.</p>
                </div>
                
                <div class="cta-buttons" style="display: flex; gap: 15px; justify-content: center;">
                    <a href="<?php echo BASE_URL; ?>/iletisim" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Uzmanla Görüş</a>
                    <a href="<?php echo BASE_URL; ?>/test/<?php echo $test['slug']; ?>" class="btn btn-outline">Testi Tekrar Çöz</a>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
