<?php
/**
 * Blog Post Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/blog_data.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$post = get_blog_post($slug);
$page = 'blog';

if (!$post) {
    header("Location: " . site_url('blog'));
    exit;
}

$related      = get_related_posts($post['category'], $post['slug'], 3);
$hero_img     = get_blog_product_image($post['id']);

$page_title       = htmlspecialchars($post['title']) . " - " . SITE_NAME . " Blog";
$page_description = htmlspecialchars($post['excerpt']);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($post['keywords'] ?? ''); ?>">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/blog/<?php echo $slug; ?>">

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Lato:wght@300;400;700&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
        /* ── Global ── */
        body { background: #fff !important; color: #333; }

        /* ── Header ── */
        .header-main { height: 86px; }
        #logo img { max-height: 86px; }
        #logo { width: 136px; }
        .header-bg-color { background-color: rgba(10,10,10,0.92) !important; }
        .nav > li > a { font-family: Montserrat, sans-serif; font-weight: 700; color: #fff; }
        .nav .nav-dropdown { background-color: #000; }
        .nav-dropdown { font-size: 100%; }
        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }
        @media (max-width: 549px) { .header-main { height: 70px; } #logo img { max-height: 70px; } }

        /* ── Hero ── */
        .post-hero {
            position: relative;
            height: 520px;
            overflow: hidden;
            background: #1a1a1a;
        }
        .post-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            filter: brightness(0.45) saturate(0.8);
            transition: transform 10s ease;
        }
        .post-hero:hover .post-hero-img { transform: scale(1.04); }
        .post-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                rgba(0,0,0,0.25) 0%,
                rgba(0,0,0,0.1) 40%,
                rgba(0,0,0,0.8) 80%,
                rgba(0,0,0,0.92) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 0 0 48px;
        }
        .post-hero-inner {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
        }
        .post-hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-family: 'Barlow', sans-serif;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
            margin-bottom: 18px;
        }
        .post-hero-breadcrumb a { color: #00b4e0; text-decoration: none; }
        .post-hero-breadcrumb a:hover { color: #fff; }
        .post-hero-breadcrumb .sep { color: rgba(255,255,255,0.25); }
        .post-hero-category {
            display: inline-block;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 3px;
            margin-bottom: 16px;
        }
        .post-hero-title {
            font-family: 'Barlow', sans-serif;
            font-size: clamp(28px, 5vw, 52px);
            font-weight: 900;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.5px;
            margin: 0 0 20px;
            text-transform: uppercase;
        }
        .post-hero-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px 20px;
            font-size: 13px;
            font-family: 'Barlow', sans-serif;
            color: rgba(255,255,255,0.55);
        }
        .post-hero-meta .dot { color: rgba(255,255,255,0.2); }
        .post-hero-meta strong { color: rgba(255,255,255,0.8); font-weight: 600; }

        /* ── Article layout ── */
        .post-layout {
            max-width: 860px;
            margin: 0 auto;
            padding: 50px 24px 80px;
        }

        /* ── Article body ── */
        .blog-content {
            font-family: 'Lato', sans-serif;
            font-size: 17px;
            line-height: 1.9;
            color: #444;
        }
        .blog-content h2 {
            font-family: 'Barlow', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 50px 0 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(0,140,178,0.4);
        }
        .blog-content h3 {
            font-family: 'Barlow', sans-serif;
            font-size: 21px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 36px 0 14px;
        }
        .blog-content h4 {
            font-family: 'Barlow', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #222;
            margin: 26px 0 10px;
        }
        .blog-content p { margin-bottom: 22px; }
        .blog-content a { color: #007fa3; text-decoration: underline; }
        .blog-content a:hover { color: #005f7a; }
        .blog-content strong { color: #111; }
        .blog-content ul, .blog-content ol { margin: 18px 0 24px 28px; }
        .blog-content li { margin-bottom: 10px; }
        .blog-content blockquote {
            background: #f0f8fb;
            border-left: 4px solid #008cb2;
            padding: 22px 28px;
            margin: 32px 0;
            font-style: italic;
            color: #555;
            border-radius: 0 8px 8px 0;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 24px 0;
            border: 1px solid #e5e5e5;
        }
        .table-custom { width: 100%; border-collapse: collapse; margin: 24px 0; }
        .table-custom th, .table-custom td { padding: 11px 14px; border: 1px solid #ddd; text-align: left; }
        .table-custom th { background: #f0f8fb; font-weight: 700; color: #1a1a1a; font-family: 'Barlow', sans-serif; letter-spacing: 0.5px; }
        .table-custom tr:nth-child(even) { background: #fafafa; }

        /* ── Divider ── */
        .post-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #ddd, transparent);
            margin: 48px 0;
        }

        /* ── Author box ── */
        .author-box {
            display: flex;
            align-items: center;
            gap: 22px;
            background: #f7f9fb;
            border: 1px solid #e5e9ee;
            padding: 26px 28px;
            border-radius: 12px;
            margin: 0 0 40px;
        }
        .author-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            font-weight: 900;
            font-family: 'Barlow', sans-serif;
            flex-shrink: 0;
        }
        .author-info h4 {
            margin: 0 0 4px;
            color: #111;
            font-family: 'Barlow', sans-serif;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .author-info p { margin: 0; color: #666; font-size: 13px; line-height: 1.5; }

        /* ── Tags ── */
        .tags-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            padding: 28px 0;
        }
        .tags-label {
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #999;
            margin-right: 4px;
        }
        .tag {
            display: inline-block;
            background: #eef7fb;
            border: 1px solid #b3d9e8;
            color: #007fa3;
            padding: 5px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-family: 'Barlow', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .tag:hover { background: #008cb2; border-color: #008cb2; color: #fff; }

        /* ── Share ── */
        .share-section {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 24px 28px;
            background: #f7f9fb;
            border: 1px solid #e5e9ee;
            border-radius: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .share-label {
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #999;
            margin-right: 4px;
        }
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: #fff;
            border: 1px solid #ddd;
            color: #555;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.2s;
        }
        .share-btn:hover { background: #008cb2; border-color: #008cb2; color: #fff; transform: translateY(-2px); }

        /* ── Related articles ── */
        .related-section { margin-top: 70px; }
        .related-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }
        .related-header h2 {
            font-family: 'Barlow', sans-serif;
            font-size: 22px;
            font-weight: 900;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .related-header-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, #008cb2, transparent);
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .related-card {
            background: #fff;
            border: 1px solid #e5e9ee;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .related-card:hover {
            border-color: #008cb2;
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }
        .related-card-img-wrap {
            position: relative;
            aspect-ratio: 16/10;
            overflow: hidden;
            background: #f5f5f5;
        }
        .related-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }
        .related-card:hover .related-card-img { transform: scale(1.06); }
        .related-card-body { padding: 20px; }
        .related-card-cat {
            display: inline-block;
            background: #eef7fb;
            border: 1px solid #b3d9e8;
            color: #007fa3;
            font-family: 'Barlow', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .related-card h4 {
            font-family: 'Barlow', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 8px;
            line-height: 1.35;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .related-card h4 a { color: inherit; text-decoration: none; }
        .related-card h4 a:hover { color: #008cb2; }
        .related-card .excerpt {
            font-size: 13px;
            color: #777;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .related-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }
        .related-card-date { font-size: 12px; color: #999; font-family: 'Barlow', sans-serif; }
        .related-card-link {
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #008cb2;
            text-decoration: none;
        }
        .related-card-link:hover { color: #005f7a; }

        /* ── CTA ── */
        .post-cta {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #007fa3 0%, #008cb2 50%, #006f8f 100%);
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            margin-top: 60px;
        }
        .post-cta::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .post-cta-eyebrow {
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-bottom: 14px;
        }
        .post-cta h2 {
            font-family: 'Barlow', sans-serif;
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 900;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0 0 12px;
        }
        .post-cta p {
            font-size: 16px;
            color: rgba(255,255,255,0.8);
            margin: 0 0 30px;
        }
        .post-cta-btns { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }
        .post-cta-btn-primary {
            display: inline-block;
            background: #fff;
            color: #008cb2;
            padding: 15px 36px;
            border-radius: 6px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s;
        }
        .post-cta-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); color: #006f8f; }
        .post-cta-btn-ghost {
            display: inline-block;
            background: transparent;
            color: rgba(255,255,255,0.85);
            padding: 15px 36px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.4);
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s;
        }
        .post-cta-btn-ghost:hover { border-color: #fff; color: #fff; transform: translateY(-2px); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .post-hero { height: 380px; }
            .related-grid { grid-template-columns: 1fr; }
            .author-box { flex-direction: column; text-align: center; }
            .post-cta { padding: 40px 20px; }
        }
        @media (max-width: 480px) {
            .post-hero { height: 300px; }
            .post-hero-meta { flex-direction: column; gap: 4px; }
            .post-hero-meta .dot { display: none; }
        }
    </style>
</head>
<body class="page wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>

    <!-- Hero -->
    <div class="post-hero">
        <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-hero-img">
        <div class="post-hero-overlay">
            <div class="post-hero-inner">
                <div class="post-hero-breadcrumb">
                    <a href="<?php echo SITE_URL; ?>/">Home</a>
                    <span class="sep">›</span>
                    <a href="<?php echo SITE_URL; ?>/blog">Blog</a>
                    <span class="sep">›</span>
                    <span><?php echo htmlspecialchars($post['category']); ?></span>
                </div>
                <div class="post-hero-category"><?php echo htmlspecialchars($post['category']); ?></div>
                <h1 class="post-hero-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-hero-meta">
                    <strong><?php echo htmlspecialchars($post['author']); ?></strong>
                    <span class="dot">•</span>
                    <span><?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
                    <span class="dot">•</span>
                    <span><?php echo $post['read_time']; ?> min read</span>
                    <span class="dot">•</span>
                    <span><?php echo number_format($post['views']); ?> views</span>
                </div>
            </div>
        </div>
    </div>

    <main>
        <div class="post-layout">

            <!-- Article body -->
            <div class="blog-content">
                <?php echo $post['content']; ?>
            </div>

            <div class="post-divider"></div>

            <!-- Author -->
            <div class="author-box">
                <div class="author-avatar"><?php echo strtoupper(substr($post['author'], 0, 1)); ?></div>
                <div class="author-info">
                    <h4><?php echo htmlspecialchars($post['author']); ?></h4>
                    <p>Elite BBS Team — Your trusted source for authentic BBS wheels and expert wheel advice since 2015.</p>
                </div>
            </div>

            <!-- Share -->
            <div class="share-section">
                <span class="share-label">Share</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog/' . $slug); ?>" class="share-btn" target="_blank" rel="noopener" title="Share on Facebook">f</a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog/' . $slug); ?>&text=<?php echo urlencode($post['title']); ?>" class="share-btn" target="_blank" rel="noopener" title="Share on X / Twitter">𝕏</a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SITE_URL . '/blog/' . $slug); ?>" class="share-btn" target="_blank" rel="noopener" title="Share on LinkedIn">in</a>
                <a href="mailto:?subject=<?php echo urlencode($post['title']); ?>&body=<?php echo urlencode(SITE_URL . '/blog/' . $slug); ?>" class="share-btn" title="Share via Email">✉</a>
            </div>

            <!-- Tags -->
            <?php
            $tags = array_filter(array_map('trim', explode(',', $post['tags'] ?? '')));
            if (!empty($tags)):
            ?>
            <div class="tags-section">
                <span class="tags-label">Tags</span>
                <?php foreach ($tags as $tag): ?>
                <a href="<?php echo SITE_URL; ?>/blog?search=<?php echo urlencode($tag); ?>" class="tag"><?php echo htmlspecialchars($tag); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Related articles -->
            <?php if (!empty($related)): ?>
            <div class="related-section">
                <div class="related-header">
                    <h2>Related Articles</h2>
                    <div class="related-header-line"></div>
                </div>
                <div class="related-grid">
                    <?php foreach ($related as $i => $rel):
                        $rel_img = get_blog_product_image($rel['id'] + 10 + $i);
                    ?>
                    <div class="related-card">
                        <div class="related-card-img-wrap">
                            <a href="<?php echo SITE_URL; ?>/blog/<?php echo $rel['slug']; ?>">
                                <img src="<?php echo htmlspecialchars($rel_img); ?>" alt="<?php echo htmlspecialchars($rel['title']); ?>" class="related-card-img">
                            </a>
                        </div>
                        <div class="related-card-body">
                            <div class="related-card-cat"><?php echo htmlspecialchars($rel['category']); ?></div>
                            <h4><a href="<?php echo SITE_URL; ?>/blog/<?php echo $rel['slug']; ?>"><?php echo htmlspecialchars($rel['title']); ?></a></h4>
                            <p class="excerpt"><?php echo htmlspecialchars($rel['excerpt']); ?></p>
                            <div class="related-card-footer">
                                <span class="related-card-date"><?php echo date('M j, Y', strtotime($rel['published_at'])); ?></span>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $rel['slug']; ?>" class="related-card-link">Read &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- CTA -->
            <div class="post-cta">
                <div class="post-cta-eyebrow">Elite BBS Specialists</div>
                <h2>Need Expert Wheel Advice?</h2>
                <p>Our team of BBS specialists is ready to help you find the perfect fitment for your build.</p>
                <div class="post-cta-btns">
                    <a href="<?php echo SITE_URL; ?>/shop" class="post-cta-btn-primary">Browse Wheels</a>
                    <a href="<?php echo SITE_URL; ?>/contact" class="post-cta-btn-ghost">Contact Us</a>
                </div>
            </div>

        </div><!-- .post-layout -->
    </main>

    <?php require INCLUDES_PATH . '/footer.php'; ?>
</div><!-- #wrapper -->
</body>
</html>
