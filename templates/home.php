<?php require_once BASE_PATH . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1 class="hero-title animate-up">Zihinsel Sınırlarınızı Yeniden Tanımlayın</h1>
            <p class="hero-subtitle animate-up" style="animation-delay: 0.1s">Spor, sanat ve iş dünyasında en üst potansiyelinize ulaşmak için bilimsel temelli psikolojik performans danışmanlığı.</p>
            <div class="hero-cta animate-up" style="animation-delay: 0.2s">
                <a href="<?php echo BASE_URL; ?>/iletisim" class="btn btn-primary">Ön Görüşme Planla</a>
                <a href="<?php echo BASE_URL; ?>/uzmanlik-alanlari" class="btn btn-outline">Hizmetleri İncele</a>
            </div>
        </div>
    </section>

    <!-- Expertise Areas (Home) -->
    <section class="expertise" id="uzmanlik">
        <div class="container">
            <div class="expertise-grid">
                <!-- Card 1 -->
                <div class="expertise-card">
                    <div class="expertise-content">
                        <h3>MENTAL <br> PERFORMANS</h3>
                        <p class="subtitle">Sporcular, Performans Sanatçıları, Liderler, Öğrenciler</p>
                        <p class="desc">Motorsporları, Performans Sanatçıları, Dikkat Eksikliği ve Hiperaktivite Bozukluğu, İş dünyası Profesyonelleri ve sınav dönemindeki Öğrenciler için optimal performansa erişim..</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-bolt"></i> Yüksek Performans Koçluğu</li>
                            <li><i class="fas fa-brain"></i> Beyin Antrenmanları</li>
                            <li><i class="fas fa-chart-line"></i> Peak (Yüksek) Performans</li>
                            <li><i class="fas fa-bullseye"></i> Zihinsel Dayanıklılık & Odak</li>
                            <li><i class="fas fa-users"></i> Liderlik ve Takım Dinamikleri</li>
                        </ul>
                        <a href="<?php echo BASE_URL; ?>/uzmanlik-alanlari" class="link-btn">Sınırlarını Aş <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Quote Section -->
                <div class="quote-section">
                    <blockquote>
                        "Kariyerim boyunca 9000’den fazla şut kaçırdım. Hayatımda tekrar, tekrar ve tekrar başarısız oldum. Ve işte bu yüzden başardım."
                        <cite>– Michael Jordan</cite>
                    </blockquote>
                </div>

                <!-- Card 2 -->
                <div class="expertise-card alternate">
                    <div class="expertise-content">
                        <h3>Klinik <br> Psikoloji</h3>
                        <p class="subtitle">Genç Yetişkinler, Yetişkinler ve Kurumlar İçin</p>
                        <p class="desc">İçsel denge, güvenli bağlar kurma ve derin psikolojik iyi oluş üzerine şefkatli bir yolculuk.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-leaf"></i> Bireysel Psikoterapi</li>
                            <li><i class="fas fa-hands-helping"></i> Genç Yetişkin Terapisi</li>
                            <li><i class="fas fa-building"></i> Kurumsal Psikolojik İyi Oluş</li>
                            <li><i class="fas fa-laptop-medical"></i> Online Terapi Desteği</li>
                        </ul>
                        <a href="<?php echo BASE_URL; ?>/hizmetler" class="link-btn">Hizmetlerim <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Quote Section 2 -->
                <div class="quote-section">
                    <blockquote>
                        "En karanlık anlarımızda bile ışığı bulabilmek, içimizdeki sese güvenmekle başlar."
                        <cite>– Klinik Psikoloji Yaklaşımı</cite>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- About Snippet -->
    <section class="about-snippet">
        <div class="container about-grid">
            <div class="about-image">
                <img src="<?php echo BASE_URL; ?>/assets/images/elif-yeni.jpg" alt="Elif Baziki" 
style="width: 250px; height: 380px; object-fit: cover; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="experience-badge">
                    <span class="number">10+</span>
                    <span class="text">Yıllık<br>Deneyim</span>
                </div>
            </div>
            <div class="about-text">
                <h2>Elif Baziki Kimdir?</h2>
                <p>İnsan psikolojisi ve performans gelişimi üzerine uzmanlaşmış, uluslararası akreditasyonlara sahip bir psikolog ve mental koç.</p>
                <p>Amacım, bireylerin kendi potansiyellerini keşfetmelerine rehberlik etmek, zihinsel bariyerleri aşmalarını sağlamak ve sürdürülebilir başarı hikayeleri yaratmaktır.</p>
                
                <div class="credentials">
                    <div class="credential-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Klinik Psikoloji Yüksek Lisans</span>
                    </div>
                    <div class="credential-item">
                        <i class="fas fa-certificate"></i>
                        <span>Spor Psikolojisi Uzmanlığı</span>
                    </div>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/hakkimda" class="btn btn-outline">Daha Fazla Bilgi</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container cta-container">
            <h2>Değişime Hazır Mısınız?</h2>
            <p>Zihinsel performansınızı artırmak ve psikolojik iyi oluşunuzu desteklemek için ilk adımı bugün atın.</p>
            <a href="<?php echo BASE_URL; ?>/iletisim" class="btn btn-primary btn-large">Randevu Al</a>
        </div>
    </section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
