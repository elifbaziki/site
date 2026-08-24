<?php
// Fetch footer links grouped by column
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
    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
            
            <!-- Column 1: Logo and Text -->
            <div class="footer-brand">
                <a href="<?php echo BASE_URL; ?>" class="logo">ELİF BAZİKİ</a>
                <p>Sporcular, liderler ve profesyoneller için yüksek performans koçluğu ve psikolojik danışmanlık.</p>
                <div class="social-links" style="margin-top: 15px;">
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <!-- Column 2: Kurumsal -->
            <div class="footer-links">
                <h3>Kurumsal</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach($footer_data['kurumsal'] as $l): ?>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL . $l['link_url']; ?>" style="color: #ccc; text-decoration: none;"><?php echo htmlspecialchars($l['link_title']); ?></a></li>
                    <?php endforeach; ?>
                    <?php if(empty($footer_data['kurumsal'])): ?>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL; ?>/hakkimda" style="color: #ccc; text-decoration: none;">Hakkımızda</a></li>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL; ?>/hizmetler" style="color: #ccc; text-decoration: none;">Hizmetler</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Column 3: Yasal Mevzuatlar -->
            <div class="footer-links">
                <h3>Yasal Mevzuatlar</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach($footer_data['yasal'] as $l): ?>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL . $l['link_url']; ?>" style="color: #ccc; text-decoration: none;"><?php echo htmlspecialchars($l['link_title']); ?></a></li>
                    <?php endforeach; ?>
                    <?php if(empty($footer_data['yasal'])): ?>
                        <li style="margin-bottom: 10px;"><a href="#" style="color: #ccc; text-decoration: none;">Aydınlatma Metni</a></li>
                        <li style="margin-bottom: 10px;"><a href="#" style="color: #ccc; text-decoration: none;">Çerez Politikası</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 4: Hızlı Erişim -->
            <div class="footer-links">
                <h3>Hızlı Erişim</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach($footer_data['hizli_erisim'] as $l): ?>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL . $l['link_url']; ?>" style="color: #ccc; text-decoration: none;"><?php echo htmlspecialchars($l['link_title']); ?></a></li>
                    <?php endforeach; ?>
                    <?php if(empty($footer_data['hizli_erisim'])): ?>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL; ?>/blog" style="color: #ccc; text-decoration: none;">Blog</a></li>
                        <li style="margin-bottom: 10px;"><a href="<?php echo BASE_URL; ?>/iletisim" style="color: #ccc; text-decoration: none;">İletişim</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
        </div>
        <div class="footer-bottom" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
            <div class="container" style="text-align: center;">
                <p>&copy; <?php echo date('Y'); ?> Elif Baziki. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Sticky CTA -->
    <a href="tel:+905307200396" class="mobile-sticky-cta">
        <i class="fas fa-phone-alt"></i> Hemen Ara
    </a>

    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
