<?php 
$site_title = 'Blog | ' . get_setting($db, 'site_title');
require_once BASE_PATH . '/includes/header.php'; 
?>

<section class="page-header" style="background: var(--primary-color); color: white; padding: 100px 0 60px;">
    <div class="container">
        <h1>Blog</h1>
        <p>Psikoloji, mental performans ve zihinsel iyi oluş üzerine makaleler.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        
        <!-- Category Filter (Static Example) -->
        <div style="display: flex; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap; justify-content: center;">
            <a href="#" class="btn-main" style="background: var(--primary-color); color: var(--white); padding: 0.5rem 1.5rem;">Tümü</a>
            <a href="#" class="btn-main" style="background: transparent; color: var(--text-dark); border-color: #ddd; padding: 0.5rem 1.5rem;">Performans Psikolojisi</a>
            <a href="#" class="btn-main" style="background: transparent; color: var(--text-dark); border-color: #ddd; padding: 0.5rem 1.5rem;">Bilişsel Antrenman</a>
            <a href="#" class="btn-main" style="background: transparent; color: var(--text-dark); border-color: #ddd; padding: 0.5rem 1.5rem;">DEHB (ADHD)</a>
        </div>

        <div class="specialties-grid">
            <?php 
            $posts = get_all_posts($db);
            foreach($posts as $post): 
                $img = $post['featured_image'] ? htmlspecialchars($post['featured_image']) : BASE_URL . '/assets/images/default-blog.jpg';
                $cat = $post['keywords'] ? htmlspecialchars($post['keywords']) : 'Genel';
            ?>
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div style="height: 200px; background-image: url('<?php echo $img; ?>'); background-size: cover; background-position: center;"></div>
                <div style="padding: 2rem; flex: 1; display: flex; flex-direction: column;">
                    <span style="color: var(--accent); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;"><?php echo $cat; ?></span>
                    <h3 style="margin-bottom: 1rem;"><a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p style="margin-bottom: 1.5rem; flex: 1;"><?php echo htmlspecialchars($post['meta_description']); ?></p>
                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" style="color: var(--primary-color); font-weight: 600; font-size: 0.9rem;">Devamını Oku &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($posts)): ?>
                <p style="text-align: center; width: 100%;">Henüz blog yazısı bulunmamaktadır.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
