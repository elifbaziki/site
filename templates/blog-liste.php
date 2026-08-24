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

<section class="blog-list" style="padding: 80px 0;">
    <div class="container">
        <?php 
            $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            if ($page < 1) $page = 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            $total_posts = get_total_posts_count($db);
            $total_pages = ceil($total_posts / $limit);
            $posts = get_paginated_posts($db, $limit, $offset);
        ?>
        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            <?php foreach($posts as $post): ?>
            <article class="blog-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                <?php if($post['featured_image']): ?>
                <div class="blog-image" style="height: 200px; overflow: hidden;">
                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                </div>
                <?php endif; ?>
                <div class="blog-content" style="padding: 25px;">
                    <div class="blog-meta" style="color: #666; font-size: 0.9rem; margin-bottom: 10px;">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($post['created_at'])); ?>
                    </div>
                    <h2 style="font-size: 1.3rem; margin-bottom: 15px;">
                        <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" style="color: var(--primary-color); text-decoration: none;">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h2>
                    <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                        <?php echo htmlspecialchars(mb_substr($post['meta_description'], 0, 120)) . '...'; ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="read-more" style="color: var(--accent-color); font-weight: 600; text-decoration: none;">Devamını Oku <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
            <?php endforeach; ?>
            
            <?php if(empty($posts)): ?>
                <p>Henüz blog yazısı bulunmamaktadır.</p>
            <?php endif; ?>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 4rem;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?p=<?php echo $i; ?>" class="btn-main" style="padding: 0.5rem 1rem; <?php echo ($i === $page) ? 'background: var(--primary-color); color: #fff;' : 'background: #fff; color: #333; border: 1px solid #ddd;'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
