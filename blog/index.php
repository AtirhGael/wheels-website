<?php
/**
 * Blog Index Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/blog_data.php';

$page = 'blog';
$page_title = "Blog - " . SITE_NAME;
$page_description = "Expert guides, wheel tips, and industry insights for BBS wheel enthusiasts.";

$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

$categories = get_blog_categories();
$featured   = get_blog_featured();
$posts      = get_blog_posts(20, $category);
$img_pool   = get_product_images_pool();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/blog">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
        body { background: #0d0f13; color: #e0e0e0; }

        /* ── Page hero ── */
        .blog-page-hero {
            position: relative;
            background: linear-gradient(160deg, #0a0c10 0%, #111318 100%);
            padding: 70px 24px 60px;
            text-align: center;
            overflow: hidden;
        }
        .blog-page-hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.025) 1px,transparent 1px);
            background-size: 48px 48px;
        }
        .blog-page-hero::after {
            content: '';
            position: absolute; top: -80px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 300px;
            background: radial-gradient(ellipse, rgba(0,140,178,0.18) 0%, transparent 70%);
        }
        .blog-hero-inner { position: relative; z-index: 1; }
        .blog-hero-eyebrow {
            display: inline-flex; align-items: center; gap: 10px;
            font-family: 'Barlow', sans-serif; font-size: 11px; font-weight: 700;
            letter-spacing: 4px; text-transform: uppercase; color: #008cb2;
            margin-bottom: 16px;
        }
        .blog-hero-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: #008cb2; }
        .blog-hero-title {
            font-family: 'Barlow', sans-serif; font-size: clamp(32px,5vw,58px);
            font-weight: 800; text-transform: uppercase; letter-spacing: -1px;
            color: #fff; margin: 0 0 14px;
        }
        .blog-hero-sub { font-size: 17px; color: rgba(255,255,255,0.45); max-width: 520px; margin: 0 auto; }

        /* ── Layout ── */
        .blog-wrap { max-width: 1240px; margin: 0 auto; padding: 60px 24px 80px; display: grid; grid-template-columns: 1fr 300px; gap: 40px; }

        /* ── Featured post ── */
        .blog-featured-card {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0;
            background: #16181e; border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px; overflow: hidden; margin-bottom: 40px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .blog-featured-card:hover { border-color: rgba(0,140,178,0.35); box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
        .blog-featured-img-wrap { position: relative; overflow: hidden; aspect-ratio: 4/3; }
        .blog-featured-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; display: block; }
        .blog-featured-card:hover .blog-featured-img-wrap img { transform: scale(1.05); }
        .blog-featured-badge {
            position: absolute; top: 16px; left: 16px;
            background: linear-gradient(135deg,#008cb2,#00b4e0); color: #fff;
            font-family: 'Barlow',sans-serif; font-size: 10px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            padding: 5px 12px; border-radius: 99px;
        }
        .blog-featured-body { padding: 36px 32px; display: flex; flex-direction: column; justify-content: center; }
        .blog-cat-tag {
            display: inline-block; background: rgba(0,140,178,0.15); color: #008cb2;
            border: 1px solid rgba(0,140,178,0.3);
            font-family: 'Barlow',sans-serif; font-size: 10px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            padding: 4px 12px; border-radius: 99px; margin-bottom: 14px;
            text-decoration: none;
        }
        .blog-featured-title {
            font-family: 'Barlow',sans-serif; font-size: clamp(20px,2.5vw,28px);
            font-weight: 800; color: #fff; line-height: 1.2; margin: 0 0 14px;
        }
        .blog-featured-title a { color: inherit; text-decoration: none; transition: color 0.2s; }
        .blog-featured-title a:hover { color: #008cb2; }
        .blog-featured-excerpt { font-size: 15px; color: rgba(255,255,255,0.5); line-height: 1.7; margin: 0 0 20px; }
        .blog-post-meta { display: flex; align-items: center; gap: 16px; font-size: 13px; color: rgba(255,255,255,0.35); }
        .blog-post-meta span { display: flex; align-items: center; gap: 5px; }
        .blog-read-link {
            display: inline-flex; align-items: center; gap: 8px; margin-top: 24px;
            color: #008cb2; font-family: 'Barlow',sans-serif; font-size: 13px;
            font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            text-decoration: none; transition: gap 0.2s;
        }
        .blog-read-link:hover { gap: 12px; color: #00b4e0; }

        /* ── Post grid ── */
        .blog-posts-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 24px; }
        .blog-card {
            background: #16181e; border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px; overflow: hidden;
            transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
        }
        .blog-card:hover { transform: translateY(-5px); border-color: rgba(0,140,178,0.3); box-shadow: 0 16px 48px rgba(0,0,0,0.4); }
        .blog-card-img-wrap { position: relative; aspect-ratio: 16/9; overflow: hidden; }
        .blog-card-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s; }
        .blog-card:hover .blog-card-img-wrap img { transform: scale(1.06); }
        .blog-card-body { padding: 22px 22px 20px; }
        .blog-card-title {
            font-family: 'Barlow',sans-serif; font-size: 17px; font-weight: 700;
            color: #fff; line-height: 1.35; margin: 8px 0 10px;
        }
        .blog-card-title a { color: inherit; text-decoration: none; }
        .blog-card-title a:hover { color: #008cb2; }
        .blog-card-excerpt { font-size: 14px; color: rgba(255,255,255,0.45); line-height: 1.6; margin: 0 0 16px; }
        .blog-card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); }
        .blog-card-date { font-size: 12px; color: rgba(255,255,255,0.3); }
        .blog-card-cta {
            font-family: 'Barlow',sans-serif; font-size: 12px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase; color: #008cb2;
            text-decoration: none; display: flex; align-items: center; gap: 5px;
            transition: gap 0.2s;
        }
        .blog-card-cta:hover { gap: 8px; color: #00b4e0; }

        /* ── Sidebar ── */
        .blog-sidebar { display: flex; flex-direction: column; gap: 28px; }
        .sidebar-widget {
            background: #16181e; border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px; padding: 24px;
        }
        .sidebar-widget-title {
            font-family: 'Barlow',sans-serif; font-size: 12px; font-weight: 800;
            letter-spacing: 3px; text-transform: uppercase; color: #008cb2;
            margin: 0 0 18px; padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .blog-cats { list-style: none; padding: 0; margin: 0; }
        .blog-cats li { margin-bottom: 4px; }
        .blog-cats a {
            display: flex; align-items: center; justify-content: space-between;
            padding: 9px 12px; border-radius: 8px; color: rgba(255,255,255,0.6);
            font-size: 14px; text-decoration: none;
            border-left: 2px solid transparent;
            transition: all 0.2s;
        }
        .blog-cats a:hover, .blog-cats a.active {
            background: rgba(0,140,178,0.1); color: #fff; border-left-color: #008cb2;
        }
        .blog-cats-count {
            font-size: 11px; background: rgba(255,255,255,0.08);
            padding: 2px 7px; border-radius: 99px; color: rgba(255,255,255,0.35);
        }

        /* Sidebar product cards */
        .sidebar-product-list { display: flex; flex-direction: column; gap: 14px; }
        .sidebar-product {
            display: flex; gap: 14px; align-items: center;
            text-decoration: none; padding: 10px; border-radius: 10px;
            transition: background 0.2s;
        }
        .sidebar-product:hover { background: rgba(255,255,255,0.04); }
        .sidebar-product-img { width: 56px; height: 56px; border-radius: 8px; object-fit: cover; flex-shrink: 0; background: #0d0f13; }
        .sidebar-product-info { flex: 1; min-width: 0; }
        .sidebar-product-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
        .sidebar-product-price { font-size: 13px; font-weight: 700; color: #008cb2; }

        /* ── CTA ── */
        .blog-cta {
            margin: 60px 24px 0;
            max-width: 1240px; margin-left: auto; margin-right: auto;
            background: linear-gradient(135deg, #0a1a24 0%, #0d1e2c 100%);
            border: 1px solid rgba(0,140,178,0.25);
            border-radius: 20px; padding: 60px 40px; text-align: center;
            position: relative; overflow: hidden;
        }
        .blog-cta::before {
            content: ''; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);
            width: 400px; height: 200px;
            background: radial-gradient(ellipse, rgba(0,140,178,0.2) 0%, transparent 70%);
        }
        .blog-cta-inner { position: relative; z-index: 1; }
        .blog-cta h2 { font-family: 'Barlow',sans-serif; font-size: clamp(24px,3vw,36px); font-weight: 800; text-transform: uppercase; color: #fff; margin: 0 0 12px; }
        .blog-cta p { font-size: 17px; color: rgba(255,255,255,0.5); margin: 0 0 28px; }
        .blog-cta-btn {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 40px; background: linear-gradient(135deg,#008cb2,#00b4e0);
            color: #fff; font-family: 'Barlow',sans-serif; font-size: 14px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase; text-decoration: none;
            border-radius: 99px; box-shadow: 0 6px 28px rgba(0,140,178,0.45);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .blog-cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 36px rgba(0,140,178,0.6); color: #fff; }

        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }
        .header-main { height: 86px; }
        #logo img { max-height: 86px; }
        #logo { width: 136px; }
        .header-bg-color { background-color: rgba(10,10,10,0.9) !important; }

        @media (max-width: 900px) { .blog-wrap { grid-template-columns: 1fr; } .blog-featured-card { grid-template-columns: 1fr; } }
        @media (max-width: 600px) { .blog-posts-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="page wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>

    <!-- Hero -->
    <div class="blog-page-hero">
        <div class="blog-hero-inner">
            <div class="blog-hero-eyebrow">
                <span class="blog-hero-eyebrow-dot"></span>
                Knowledge &amp; Expertise
                <span class="blog-hero-eyebrow-dot"></span>
            </div>
            <h1 class="blog-hero-title">Elite BBS Blog</h1>
            <p class="blog-hero-sub">Expert guides, wheel tips, and industry insights for BBS enthusiasts.</p>
        </div>
    </div>

    <main>
        <div class="blog-wrap">
            <!-- Main content -->
            <div class="blog-main">

                <!-- Featured post -->
                <?php if ($featured):
                    $fi = get_blog_product_image($featured['id']);
                ?>
                <div class="blog-featured-card">
                    <div class="blog-featured-img-wrap">
                        <a href="<?php echo SITE_URL; ?>/blog/<?php echo $featured['slug']; ?>">
                            <img src="<?php echo htmlspecialchars($fi); ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                        </a>
                        <span class="blog-featured-badge">Featured</span>
                    </div>
                    <div class="blog-featured-body">
                        <a href="<?php echo SITE_URL; ?>/blog?category=<?php echo urlencode($featured['category']); ?>" class="blog-cat-tag"><?php echo htmlspecialchars($featured['category']); ?></a>
                        <h2 class="blog-featured-title">
                            <a href="<?php echo SITE_URL; ?>/blog/<?php echo $featured['slug']; ?>"><?php echo htmlspecialchars($featured['title']); ?></a>
                        </h2>
                        <p class="blog-featured-excerpt"><?php echo htmlspecialchars($featured['excerpt']); ?></p>
                        <div class="blog-post-meta">
                            <span><?php echo date('M j, Y', strtotime($featured['published_at'])); ?></span>
                            <span>·</span>
                            <span><?php echo $featured['read_time']; ?> min read</span>
                        </div>
                        <a href="<?php echo SITE_URL; ?>/blog/<?php echo $featured['slug']; ?>" class="blog-read-link">
                            Read Article
                            <svg viewBox="0 0 16 16" fill="none" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Posts grid -->
                <div class="blog-posts-grid">
                    <?php foreach ($posts as $i => $post):
                        if ($featured && $post['slug'] === $featured['slug']) continue;
                        $pimg = get_blog_product_image($post['id'] + 2);
                    ?>
                    <article class="blog-card">
                        <div class="blog-card-img-wrap">
                            <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                                <img src="<?php echo htmlspecialchars($pimg); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy">
                            </a>
                        </div>
                        <div class="blog-card-body">
                            <a href="<?php echo SITE_URL; ?>/blog?category=<?php echo urlencode($post['category']); ?>" class="blog-cat-tag"><?php echo htmlspecialchars($post['category']); ?></a>
                            <h3 class="blog-card-title">
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                            </h3>
                            <p class="blog-card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                            <div class="blog-card-footer">
                                <span class="blog-card-date"><?php echo date('M j, Y', strtotime($post['published_at'])); ?> · <?php echo $post['read_time']; ?> min</span>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="blog-card-cta">
                                    Read
                                    <svg viewBox="0 0 14 14" fill="none" width="12" height="12"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">

                <!-- Categories -->
                <div class="sidebar-widget">
                    <p class="sidebar-widget-title">Browse Topics</p>
                    <ul class="blog-cats">
                        <li><a href="<?php echo SITE_URL; ?>/blog" class="<?php echo !$category ? 'active' : ''; ?>">All Posts</a></li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/blog?category=<?php echo urlencode($cat['category']); ?>" class="<?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Featured wheels -->
                <?php
                $sidebar_products = get_all_products(5);
                if (!empty($sidebar_products)):
                ?>
                <div class="sidebar-widget">
                    <p class="sidebar-widget-title">Featured Wheels</p>
                    <div class="sidebar-product-list">
                        <?php foreach ($sidebar_products as $sp):
                            $spi = json_decode($sp['images'] ?? '[]', true);
                            $spimg = !empty($spi[0]) ? $spi[0] : asset_url('images/placeholder.png');
                            $sprice = get_display_price($sp);
                        ?>
                        <a href="<?php echo SITE_URL; ?>/product/<?php echo $sp['slug']; ?>" class="sidebar-product">
                            <img src="<?php echo htmlspecialchars($spimg); ?>" alt="<?php echo htmlspecialchars($sp['name']); ?>" class="sidebar-product-img" loading="lazy">
                            <div class="sidebar-product-info">
                                <div class="sidebar-product-name"><?php echo htmlspecialchars($sp['name']); ?></div>
                                <div class="sidebar-product-price">$<?php echo number_format($sprice['price'], 2); ?></div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </aside>
        </div>

        <!-- CTA -->
        <div class="blog-cta">
            <div class="blog-cta-inner">
                <h2>Ready to Upgrade Your Wheels?</h2>
                <p>Browse our curated selection of authentic BBS wheels — built for drivers who demand the best.</p>
                <a href="<?php echo SITE_URL; ?>/shop" class="blog-cta-btn">
                    <svg viewBox="0 0 20 20" fill="none" width="16" height="16"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.4 3M17 13l1.4 3M9 18a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Shop All Wheels
                </a>
            </div>
        </div>
    </main>

    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
