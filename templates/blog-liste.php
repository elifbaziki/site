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

        <?php 
            $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            if ($page < 1) $page = 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            $total_posts = get_total_posts_count($db);
            $total_pages = ceil($total_posts / $limit);
            $posts = get_paginated_posts($db, $limit, $offset);
        ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            <?php foreach($posts as $post): 
                $img = $post['featured_image'] ? htmlspecialchars($post['featured_image']) : BASE_URL . '/assets/images/default-blog.jpg';
                $cat = $post['keywords'] ? htmlspecialchars($post['keywords']) : 'Genel';
            ?>
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div style="height: 220px; background-image: url('<?php echo $img; ?>'); background-size: cover; background-position: center;"></div>
                <div style="padding: 2rem; flex: 1; display: flex; flex-direction: column;">
                    <span style="color: var(--accent); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;"><?php echo $cat; ?></span>
                    <h3 style="margin-bottom: 1rem; font-size: 1.25rem; line-height: 1.4;"><a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p style="margin-bottom: 1.5rem; flex: 1; font-size: 0.95rem; color: #475569;"><?php echo htmlspecialchars($post['meta_description']); ?></p>
                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" style="color: var(--primary-color); font-weight: 600; font-size: 0.9rem; text-decoration: none;">Devamını Oku &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 4rem;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?p=<?php echo $i; ?>" class="btn-main" style="padding: 0.5rem 1rem; <?php echo ($i === $page) ? 'background: var(--primary-color); color: #fff;' : 'background: #fff; color: var(--text-dark); border-color: #ddd;'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>    
            <?php if(empty($posts)): ?>
                <p style="text-align: center; width: 100%;">Henüz blog yazısı bulunmamaktadır.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
