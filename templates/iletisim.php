<?php require_once BASE_PATH . "/includes/header.php"; ?>

<!-- Contact Section -->
    <section class="section contact-section" style="margin-top: 80px; padding-top: 3rem; padding-bottom: 3rem;">
        <div class="container">
            <div style="display: flex; flex-wrap: wrap; gap: 4rem;">
                
                <!-- Contact Info -->
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="font-family: var(--font-main) !important; font-weight: 900; letter-spacing: -1px; text-transform: uppercase; font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">Bana Ulaşın</h2>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="font-family: var(--font-main) !important; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--accent); margin-right: 10px;"></i> Adres</h4>
                        <p style="color: var(--text-light); margin-left: 25px;"><?php echo htmlspecialchars(get_setting($db, 'contact_address', 'İstanbul, Türkiye')); ?></p>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="font-family: var(--font-main) !important; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem;"><i class="fas fa-phone" style="color: var(--accent); margin-right: 10px;"></i> Telefon & WhatsApp</h4>
                        <p style="color: var(--text-light); margin-left: 25px;"><?php echo htmlspecialchars(get_setting($db, 'contact_phone', '+90 (530) 720 03 96')); ?></p>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="font-family: var(--font-main) !important; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem;"><i class="fas fa-envelope" style="color: var(--accent); margin-right: 10px;"></i> E-posta</h4>
                        <p style="color: var(--text-light); margin-left: 25px;"><?php echo htmlspecialchars(get_setting($db, 'contact_email', 'info@elifbaziki.com')); ?></p>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div style="flex: 1; min-width: 300px; background: var(--white); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-md);">
                    <?php if(isset($_GET['success'])): ?>
                        <div style="padding: 15px; background: #dcfce7; color: #166534; border-radius: 4px; margin-bottom: 20px;">
                            Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağım.
                        </div>
                    <?php endif; ?>
                    <h3 style="font-family: var(--font-main) !important; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-color);">Mesaj Gönderin</h3>
                    <form action="<?php echo BASE_URL; ?>/submit-contact" method="POST">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-dark);">Ad Soyad</label>
                            <input type="text" name="name" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 1rem;" required>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-dark);">E-posta Adresi</label>
                            <input type="email" name="email" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 1rem;" required>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-dark);">Konu</label>
                            <select name="subject" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 1rem;">
                                <option>Bireysel Görüşme</option>
                                <option>Kurumsal Çalışmalar</option>
                                <option>Mental Performans Koçluğu</option>
                                <option>Beyin Antrenmanı</option>
                                <option>Moxo Dikkat Performansı Testi</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-dark);">Mesajınız</label>
                            <textarea name="message" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 1rem; resize: vertical;" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-main" style="width: 100%; cursor: pointer;">Gönder</button>
                    </form>
                </div>
                
            </div>
        </div>
    </section>

<?php require_once BASE_PATH . "/includes/footer.php"; ?>
