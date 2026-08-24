<?php

function get_all_posts($db) {
    $stmt = $db->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function get_paginated_posts($db, $limit = 12, $offset = 0) {
    $stmt = $db->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function get_total_posts_count($db) {
    $stmt = $db->query("SELECT COUNT(*) FROM blog_posts");
    return $stmt->fetchColumn();
}

function get_post_by_slug($db, $slug) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function auto_internal_link($db, $content) {
    // Fetch all posts to potentially link to
    $posts = get_all_posts($db);
    
    // Limits per keyword to prevent spamming
    $max_links_per_post = 4;
    $total_links_added = 0;
    
    foreach ($posts as $post) {
        if ($total_links_added >= $max_links_per_post) {
            break;
        }

        // Use focus keyword for internal linking
        $keyword = trim($post['focus_keyword']);
        if (empty($keyword)) continue;

        // Escape keyword for regex, allow case insensitive whole word match
        $regex = '/\b(' . preg_quote($keyword, '/') . ')\b/iu';
        
        // We only want to replace the first occurrence of the keyword in the text
        // Note: we should avoid replacing inside existing <a> tags or HTML tags
        // This is a complex regex to ensure we don't break HTML
        $content = preg_replace_callback($regex, function($matches) use ($post, &$total_links_added, $keyword) {
            // Only add link once per keyword
            static $replaced = false;
            if (!$replaced && $total_links_added < 4) {
                $replaced = true;
                $total_links_added++;
                $url = BASE_URL . '/blog/' . $post['slug'];
                return '<a href="' . $url . '" class="internal-link" title="' . htmlspecialchars($post['title']) . '">' . $matches[1] . '</a>';
            }
            return $matches[0];
        }, $content, 1); // Limit to 1 replacement per keyword
    }
    
    return $content;
}

// Ensure unique slug
function generate_slug($db, $title, $id = null) {
    // Transliterate Turkish characters
    $search  = array('ç', 'Ç', 'ğ', 'Ğ', 'ı', 'I', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü');
    $replace = array('c', 'c', 'g', 'g', 'i', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u');
    $title = str_replace($search, $replace, $title);
    
    // Remove invalid characters and create slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = trim($slug, '-');
    
    // Check if slug exists
    $sql = "SELECT count(*) FROM blog_posts WHERE slug = ?";
    $params = [$slug];
    if ($id) {
        $sql .= " AND id != ?";
        $params[] = $id;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        $slug = $slug . '-' . time();
    }
    
    return $slug;
}
