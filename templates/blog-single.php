<?php 
// $post is injected by index.php
$site_title = htmlspecialchars($post['title']) . ' | ' . get_setting($db, 'site_title');
$meta_description = htmlspecialchars($post['meta_description']);
$meta_keywords = htmlspecialchars($post['keywords']);
$og_image = $post['featured_image'];

require_once BASE_PATH . '/includes/header.php'; 
?>

<section class="blog-single" style="padding: 120px 0 80px;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        
        <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 20px; line-height: 1.2;">
            <?php echo htmlspecialchars($post['title']); ?>
        </h1>
        
        <div class="blog-meta" style="color: #666; font-size: 1rem; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($post['created_at'])); ?> 
            <span style="margin: 0 10px;">|</span> 
            <i class="fas fa-user"></i> Elif Baziki
        </div>
        
        <?php if($post['featured_image']): ?>
        <div class="featured-image" style="margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; height: auto; display: block;">
        </div>
        <?php endif; ?>
        
        <div class="blog-content" style="font-size: 1.1rem; line-height: 1.8; color: #333;">
            <?php 
                // Auto Internal Linking logic happens here
                echo auto_internal_link($db, $post['content']); 
            ?>
        </div>
        
        <div class="blog-footer" style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #eee; text-align: center;">
            <h3>Yazıyı Paylaş</h3>
            <div class="social-share" style="margin-top: 20px;">
                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode(BASE_URL . '/blog/' . $post['slug']); ?>" target="_blank" style="display: inline-block; width: 40px; height: 40px; line-height: 40px; background: #1DA1F2; color: white; border-radius: 50%; margin: 0 5px;"><i class="fab fa-twitter"></i></a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(BASE_URL . '/blog/' . $post['slug']); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" style="display: inline-block; width: 40px; height: 40px; line-height: 40px; background: #0077b5; color: white; border-radius: 50%; margin: 0 5px;"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($post['title'] . ' ' . BASE_URL . '/blog/' . $post['slug']); ?>" target="_blank" style="display: inline-block; width: 40px; height: 40px; line-height: 40px; background: #25D366; color: white; border-radius: 50%; margin: 0 5px;"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
