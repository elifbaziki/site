<?php 
$site_title = 'Sayfa Bulunamadı | ' . get_setting($db, 'site_title');
require_once BASE_PATH . '/includes/header.php'; 
?>

<section class="error-404" style="padding: 150px 0 100px; text-align: center; min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="container">
        
        <h1 style="font-size: 8rem; color: var(--primary-color); margin: 0; line-height: 1; font-weight: 900;">404</h1>
        <h2 style="font-size: 2rem; color: #333; margin-bottom: 20px;">Aradığınız sayfa bulunamadı.</h2>
        <p style="font-size: 1.2rem; color: #666; max-width: 600px; margin: 0 auto 40px;">
            Yolunuzu kaybetmiş olabilirsiniz ama zihinsel performansınızı geliştirme yolculuğunuz devam ediyor. Hemen iletişime geçerek doğru adımı atabilirsiniz.
        </p>
        
        <div class="cta-buttons" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="tel:+905307200396" class="btn btn-primary btn-large" style="font-size: 1.1rem; padding: 15px 30px;"><i class="fas fa-phone-alt"></i> Hemen Ara</a>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline btn-large" style="font-size: 1.1rem; padding: 15px 30px;">Anasayfaya Dön</a>
        </div>
        
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
