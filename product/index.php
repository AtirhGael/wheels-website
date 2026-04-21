<?php
/**
 * Product Detail Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'product';
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

$product = get_product_by_slug($slug);

if (!$product) {
    header("Location: " . site_url('shop'));
    exit;
}

$page_title       = htmlspecialchars($product['name']) . " - " . SITE_NAME;
$page_description = htmlspecialchars($product['short_description'] ?? '');
$images           = json_decode($product['images'] ?? '[]', true);
$price_info       = get_display_price($product);

$prod_domain  = 'https://www.elitebbswheelsus.shop';
$prod_url     = $prod_domain . '/product/' . $product['slug'];
$og_image     = !empty($images[0]) ? $images[0] : $prod_domain . '/wp-content/uploads/2026/02/bbs.png';
$og_images    = array_slice(!empty($images) ? $images : [$og_image], 0, 3);
$schema_price = number_format((float)$price_info['price'], 2, '.', '');
$schema_avail = ($product['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
$cat_url      = $prod_domain . '/shop?category=' . urlencode($product['category'] ?? '');

// Related products: same category, excluding current
$related = db_get_all(
    "SELECT * FROM products WHERE status='active' AND category=:cat AND id!=:id ORDER BY RAND() LIMIT 4",
    [':cat' => $product['category'], ':id' => $product['id']]
);

?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <link rel="canonical" href="<?php echo $prod_url; ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="product">
    <meta property="og:url"         content="<?php echo $prod_url; ?>">
    <meta property="og:site_name"   content="Elite BBS Rims">
    <meta property="og:title"       content="<?php echo htmlspecialchars($product['name']); ?> — Elite BBS Rims">
    <meta property="og:description" content="<?php echo htmlspecialchars($product['short_description'] ?? ''); ?>">
    <meta property="og:locale"      content="en_US">
    <?php foreach ($og_images as $og_img): ?>
    <meta property="og:image"            content="<?php echo htmlspecialchars($og_img); ?>">
    <meta property="og:image:secure_url" content="<?php echo htmlspecialchars($og_img); ?>">
    <meta property="og:image:width"      content="1200">
    <meta property="og:image:height"     content="960">
    <meta property="og:image:alt"        content="<?php echo htmlspecialchars($product['name']); ?>">
    <meta property="og:image:type"       content="image/jpeg">
    <?php endforeach; ?>
    <meta property="product:price:amount"   content="<?php echo $schema_price; ?>">
    <meta property="product:price:currency" content="USD">
    <meta property="product:availability"   content="<?php echo $product['stock'] > 0 ? 'instock' : 'oos'; ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo htmlspecialchars($product['name']); ?> — Elite BBS Rims">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($product['short_description'] ?? ''); ?>">
    <meta name="twitter:image"       content="<?php echo htmlspecialchars($og_image); ?>">
    <meta name="twitter:label1"      content="Price">
    <meta name="twitter:data1"       content="<?php echo htmlspecialchars(format_price($price_info['price'])); ?>">
    <meta name="twitter:label2"      content="Availability">
    <meta name="twitter:data2"       content="<?php echo $product['stock'] > 0 ? 'In stock' : 'Out of stock'; ?>">

    <!-- JSON-LD: Product + Offer + BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Product",
          "name": <?php echo json_encode($product['name']); ?>,
          "description": <?php echo json_encode(strip_tags($product['short_description'] ?? $product['description'] ?? '')); ?>,
          "sku": <?php echo json_encode($product['sku'] ?? ''); ?>,
          "image": <?php echo json_encode(!empty($images) ? array_values($images) : [$og_image]); ?>,
          "brand": {
            "@type": "Brand",
            "name": <?php echo json_encode($product['brand'] ?? 'BBS'); ?>
          },
          "offers": {
            "@type": "Offer",
            "url": <?php echo json_encode($prod_url); ?>,
            "priceCurrency": "USD",
            "price": "<?php echo $schema_price; ?>",
            "priceValidUntil": "<?php echo date('Y-12-31', strtotime('+1 year')); ?>",
            "availability": "<?php echo $schema_avail; ?>",
            "itemCondition": "https://schema.org/NewCondition",
            "seller": {
              "@type": "Organization",
              "name": "Elite BBS Rims",
              "url": "https://www.elitebbswheelsus.shop/"
            }
          }
        },
        {
          "@type": "BreadcrumbList",
          "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home",  "item": "https://www.elitebbswheelsus.shop/"},
            {"@type": "ListItem", "position": 2, "name": "Shop",  "item": "https://www.elitebbswheelsus.shop/shop"},
            {"@type": "ListItem", "position": 3, "name": <?php echo json_encode($product['category'] ?? 'Shop'); ?>,
                                                  "item": <?php echo json_encode($cat_url); ?>},
            {"@type": "ListItem", "position": 4, "name": <?php echo json_encode($product['name']); ?>,
                                                  "item": <?php echo json_encode($prod_url); ?>}
          ]
        }
      ]
    }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <!-- PhotoSwipe v4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/default-skin/default-skin.min.css">

    <style>
        /* ── Product Detail ─────────────────────────────────────── */
        .product-detail {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .breadcrumb {
            padding: 15px 0;
            color: #666;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #008cb2;
            text-decoration: none;
        }

        .breadcrumb a:hover { text-decoration: underline; }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

        /* ── Gallery ─────────────────────────────────────────────── */
        .product-gallery {
            position: sticky;
            top: 100px;
        }

        .product-gallery-main {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        .product-gallery-main a {
            display: block;
            cursor: zoom-in;
        }

        .product-gallery-main img {
            width: 100%;
            height: auto;
            display: block;
        }

        .product-gallery-thumbs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .product-gallery-thumbs img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .product-gallery-thumbs img:hover,
        .product-gallery-thumbs img.active {
            border-color: #008cb2;
        }

        /* ── Product Info ────────────────────────────────────────── */
        .product-info h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .product-sku {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 32px;
            color: #008cb2;
            font-weight: 700;
            margin: 20px 0;
        }

        .product-price .regular-price {
            text-decoration: line-through;
            color: #999;
            font-size: 20px;
            margin-left: 15px;
        }

        /* Short description — shown between price and meta box */
        .product-short-description {
            margin: 0 0 20px;
            font-size: 14px;
            line-height: 1.75;
            color: #555;
        }

        .product-short-description p { margin: 0 0 8px; }

        /* Meta link — category/tags as inline links */
        .meta-link {
            color: #008cb2;
            text-decoration: none;
        }

        .meta-link:hover { text-decoration: underline; }

        .product-meta {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .product-meta p {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            margin: 0;
        }

        .product-meta p:last-child { border-bottom: none; }

        .product-meta strong {
            display: inline-block;
            width: 100px;
            color: #333;
        }

        .stock-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .in-stock  { background: #d4edda; color: #155724; }
        .out-of-stock { background: #f8d7da; color: #721c24; }

        /* ── Add to Cart ─────────────────────────────────────────── */
        .add-to-cart-section { margin: 30px 0; }

        .add-to-cart-form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .qty-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .qty-wrapper label {
            font-size: 13px;
            font-weight: 700;
            color: #333;
        }

        .qty-control {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 48px;
            background: #f5f5f5;
            border: none;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            color: #333;
            transition: background 0.2s;
            line-height: 1;
        }

        .qty-btn:hover { background: #e0e0e0; }

        .qty-input {
            width: 60px;
            height: 48px;
            padding: 0 8px;
            text-align: center;
            font-size: 16px;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            -moz-appearance: textfield;
        }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .add-to-cart-btn {
            flex: 1;
            padding: 15px 40px;
            background: #008cb2;
            color: #fff;
            border: none;
            border-radius: 99px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-to-cart-btn:hover { background: #006f8f; }

        .product-fitment {
            margin-top: 30px;
            padding: 20px;
            background: #f0f7ff;
            border-radius: 8px;
            border-left: 4px solid #008cb2;
        }

        .product-fitment h3 { margin-bottom: 10px; }

        /* ── Description / Additional Info Tabs ──────────────────── */
        .product-description-section {
            max-width: 1200px;
            margin: 50px auto 0;
            padding: 0 20px;
        }

        .description-tabs {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 0;
            gap: 4px;
        }

        .description-tab {
            padding: 14px 30px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-bottom: none;
            cursor: pointer;
            position: relative;
            top: 2px;
            transition: background 0.2s, color 0.2s;
            border-radius: 4px 4px 0 0;
        }

        .description-tab.active {
            color: #fff;
            background: #222;
            border-color: #222;
        }

        .description-tab:hover:not(.active) {
            background: #e0e0e0;
            color: #333;
        }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        .description-body {
            border: 1px solid #e0e0e0;
            border-top: none;
            padding: 36px 40px;
            background: #fff;
            line-height: 1.9;
            color: #444;
            font-size: 14px;
        }

        .description-body h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #111;
            margin: 0 0 18px;
        }

        .description-body p  { margin: 0 0 14px; }
        .description-body ul,
        .description-body ol { padding-left: 20px; margin: 0 0 14px; }
        .description-body li { margin-bottom: 8px; }
        .description-body li strong { color: #111; }

        /* Additional Information table */
        .product-attributes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .product-attributes-table th,
        .product-attributes-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .product-attributes-table th {
            width: 160px;
            font-weight: 700;
            color: #333;
            background: #fafafa;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
        }

        .product-attributes-table tr:last-child th,
        .product-attributes-table tr:last-child td {
            border-bottom: none;
        }

        /* ── Related Products ────────────────────────────────────── */
        .related-products {
            max-width: 1200px;
            margin: 60px auto 40px;
            padding: 0 20px;
            border-top: 1px solid #eee;
            padding-top: 50px;
        }

        .related-products h2 {
            font-size: 22px;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 30px;
            color: #111;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .related-card {
            border: 1px solid #eee;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
            transition: box-shadow 0.2s;
        }

        .related-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .related-card a { text-decoration: none; color: inherit; }

        .related-card-img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            display: block;
            background: #f9f9f9;
        }

        .related-card-body { padding: 12px 14px 14px; }

        .related-card-name {
            font-size: 13px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            color: #008cb2;
            margin: 0 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .related-card-size  { font-size: 11px; color: #999; margin: 0 0 6px; }

        .related-card-price { font-size: 14px; font-weight: 700; color: #222; margin: 0 0 10px; }
        .related-card-price del { color: #999; font-weight: 400; margin-right: 6px; font-size: 12px; }

        .related-card-btn {
            display: block;
            width: 100%;
            padding: 8px 0;
            background: #008cb2;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            text-align: center;
            transition: background 0.2s;
        }

        .related-card-btn:hover { background: #006e8a; }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 768px) {
            .related-grid { grid-template-columns: repeat(2, 1fr); }
            .product-detail-grid { grid-template-columns: 1fr; }
            .product-gallery { position: static; }
            .description-body { padding: 24px 20px; }
        }

        /* ── Header / Footer theme ───────────────────────────────── */
        .header-main { height: 86px; }
        #logo img { max-height: 86px; }
        #logo { width: 136px; }
        .header-bg-color { background-color: rgba(10,10,10,0.9) !important; }
        .header-bottom { background-color: #f1f1f1; }
        @media (max-width: 549px) { .header-main { height: 70px; } #logo img { max-height: 70px; } }
        .nav-dropdown { font-size: 100%; }
        .nav .nav-dropdown { background-color: #000000; }
        .nav-dropdown-has-arrow li.has-dropdown:after { border-bottom-color: #000000; }
        .nav > li > a { font-family: Montserrat, sans-serif; font-weight: 700; color: #fff; }
        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }
    </style>
</head>
<body class="single-product wp-theme-flatsome woocommerce nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>

    <main>
        <div class="product-detail">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a> &raquo;
                <a href="<?php echo SITE_URL; ?>/shop">Shop</a> &raquo;
                <?php if ($product['category']): ?>
                    <a href="<?php echo SITE_URL; ?>/shop?category=<?php echo urlencode($product['category']); ?>">
                        <?php echo sanitize($product['category']); ?>
                    </a> &raquo;
                <?php endif; ?>
                <?php echo sanitize($product['name']); ?>
            </div>

            <div class="product-detail-grid">
                <!-- Gallery -->
                <div class="product-gallery" id="product-gallery">
                    <div class="product-gallery-main">
                        <?php if (!empty($images[0])): ?>
                            <a href="<?php echo htmlspecialchars($images[0]); ?>"
                               id="gallery-main-link"
                               onclick="event.preventDefault(); openLightbox(currentImageIndex);">
                                <img src="<?php echo htmlspecialchars($images[0]); ?>"
                                     alt="<?php echo sanitize($product['name']); ?>"
                                     id="main-image">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo asset_url('images/placeholder.png'); ?>" alt="No image" id="main-image">
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="product-gallery-thumbs">
                            <?php foreach ($images as $index => $img): ?>
                                <img src="<?php echo htmlspecialchars($img); ?>"
                                     onclick="switchImage(this, '<?php echo htmlspecialchars($img, ENT_QUOTES); ?>', <?php echo $index; ?>)"
                                     class="<?php echo $index === 0 ? 'active' : ''; ?>"
                                     alt="<?php echo sanitize($product['name']); ?> — view <?php echo $index + 1; ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1><?php echo sanitize($product['name']); ?></h1>

                    <?php if ($product['sku']): ?>
                        <p class="product-sku">SKU: <?php echo sanitize($product['sku']); ?></p>
                    <?php endif; ?>

                    <p class="product-price">
                        <?php echo format_price($price_info['price']); ?>
                        <?php if ($price_info['on_sale']): ?>
                            <span class="regular-price"><?php echo format_price($price_info['regular']); ?></span>
                        <?php endif; ?>
                    </p>

                    <!-- Short description -->
                    <?php if (!empty($product['short_description'])): ?>
                        <div class="product-short-description">
                            <?php echo nl2br(htmlspecialchars($product['short_description'])); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Product meta -->
                    <div class="product-meta">
                        <?php if ($product['category']): ?>
                            <p>
                                <strong>Category:</strong>
                                <a href="<?php echo SITE_URL; ?>/shop?category=<?php echo urlencode($product['category']); ?>"
                                   class="meta-link">
                                    <?php echo sanitize($product['category']); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($product['brand']): ?>
                            <p><strong>Brand:</strong> <?php echo sanitize($product['brand']); ?></p>
                        <?php endif; ?>
                        <?php if ($product['size']): ?>
                            <p><strong>Size:</strong> <?php echo sanitize($product['size']); ?></p>
                        <?php endif; ?>
                        <?php if ($product['finish']): ?>
                            <p><strong>Finish:</strong> <?php echo sanitize($product['finish']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($product['tags'])): ?>
                            <p><strong>Tags:</strong>
                                <?php
                                $tag_list  = array_filter(array_map('trim', explode(',', $product['tags'])));
                                $tag_links = [];
                                foreach ($tag_list as $tag) {
                                    $tag_links[] = '<a href="' . SITE_URL . '/shop?search=' . urlencode($tag) . '" class="meta-link">' . sanitize($tag) . '</a>';
                                }
                                echo implode(', ', $tag_links);
                                ?>
                            </p>
                        <?php endif; ?>
                        <p>
                            <strong>Availability:</strong>
                            <span class="stock-status <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php echo $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ' available)' : 'Out of Stock'; ?>
                            </span>
                        </p>
                    </div>

                    <?php if ($product['stock'] > 0): ?>
                        <div class="add-to-cart-section">
                            <form class="add-to-cart-form" onsubmit="event.preventDefault(); addToCartFromDetail();">
                                <input type="hidden" id="product-id"    value="<?php echo $product['id']; ?>">
                                <input type="hidden" id="product-name"  value="<?php echo sanitize($product['name']); ?>">
                                <input type="hidden" id="product-price" value="<?php echo $price_info['price']; ?>">
                                <input type="hidden" id="product-image" value="<?php echo !empty($images[0]) ? htmlspecialchars($images[0]) : ''; ?>">

                                <div class="qty-wrapper">
                                    <label>Quantity:</label>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn qty-minus" onclick="changeQty(-1)">−</button>
                                        <input type="number" id="qty" class="qty-input" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                        <button type="button" class="qty-btn qty-plus"  onclick="changeQty(1)">+</button>
                                    </div>
                                </div>

                                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="add-to-cart-section">
                            <button class="add-to-cart-btn" disabled style="background:#999;cursor:not-allowed;">Out of Stock</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['fitment_data']): ?>
                        <div class="product-fitment">
                            <h3>Vehicle Fitment</h3>
                            <p><?php echo sanitize($product['fitment_data']); ?></p>
                        </div>
                    <?php endif; ?>
                </div><!-- /.product-info -->
            </div><!-- /.product-detail-grid -->
        </div><!-- /.product-detail -->
    </main>

    <!-- ── Product Tabs ─────────────────────────────────────────── -->
    <?php
    $desc       = trim($product['description'] ?? '');
    $short_desc = trim($product['short_description'] ?? '');
    $has_desc   = !empty($desc) || !empty($short_desc);
    $has_attrs  = $product['size'] || $product['finish'] || $product['brand'] || $product['category'] || $product['sku'];
    ?>
    <?php if ($has_desc || $has_attrs): ?>
    <div class="product-description-section">
        <!-- Tab nav -->
        <div class="description-tabs" role="tablist">
            <?php if ($has_desc): ?>
                <button class="description-tab active"
                        role="tab" aria-selected="true"
                        aria-controls="tab-panel-description"
                        id="tab-btn-description"
                        onclick="switchTab(this, 'tab-panel-description')">
                    Description
                </button>
            <?php endif; ?>
            <?php if ($has_attrs): ?>
                <button class="description-tab<?php echo !$has_desc ? ' active' : ''; ?>"
                        role="tab" aria-selected="<?php echo !$has_desc ? 'true' : 'false'; ?>"
                        aria-controls="tab-panel-additional"
                        id="tab-btn-additional"
                        onclick="switchTab(this, 'tab-panel-additional')">
                    Additional Information
                </button>
            <?php endif; ?>
        </div>

        <!-- Description panel -->
        <?php if ($has_desc): ?>
        <div class="description-body tab-panel active"
             id="tab-panel-description"
             role="tabpanel" aria-labelledby="tab-btn-description">
            <?php if (!empty($desc)): ?>
                <?php echo $desc; ?>
            <?php elseif (!empty($short_desc)): ?>
                <?php echo nl2br(htmlspecialchars($short_desc)); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Additional Information panel -->
        <?php if ($has_attrs): ?>
        <div class="description-body tab-panel<?php echo !$has_desc ? ' active' : ''; ?>"
             id="tab-panel-additional"
             role="tabpanel" aria-labelledby="tab-btn-additional"
             style="<?php echo $has_desc ? 'display:none;' : ''; ?>">
            <table class="product-attributes-table" aria-label="Product Details">
                <tbody>
                    <?php if ($product['size']): ?>
                        <tr><th>Size</th><td><?php echo sanitize($product['size']); ?></td></tr>
                    <?php endif; ?>
                    <?php if ($product['finish']): ?>
                        <tr><th>Finish</th><td><?php echo sanitize($product['finish']); ?></td></tr>
                    <?php endif; ?>
                    <?php if ($product['brand']): ?>
                        <tr><th>Brand</th><td><?php echo sanitize($product['brand']); ?></td></tr>
                    <?php endif; ?>
                    <?php if ($product['category']): ?>
                        <tr>
                            <th>Category</th>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/shop?category=<?php echo urlencode($product['category']); ?>"
                                   class="meta-link">
                                    <?php echo sanitize($product['category']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($product['sku']): ?>
                        <tr><th>SKU</th><td><?php echo sanitize($product['sku']); ?></td></tr>
                    <?php endif; ?>
                    <?php if (!empty($product['tags'])): ?>
                        <tr><th>Tags</th><td><?php echo sanitize($product['tags']); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ── Related Products ──────────────────────────────────────── -->
    <?php if (!empty($related)): ?>
    <section class="related-products">
        <h2>Related Products</h2>
        <div class="related-grid">
            <?php foreach ($related as $rel):
                $rel_imgs  = json_decode($rel['images'] ?? '[]', true);
                $rel_img   = !empty($rel_imgs[0]) ? $rel_imgs[0] : asset_url('images/placeholder.png');
                $rel_price = get_display_price($rel);
            ?>
            <div class="related-card">
                <a href="<?php echo SITE_URL; ?>/product/<?php echo $rel['slug']; ?>">
                    <img class="related-card-img"
                         src="<?php echo htmlspecialchars($rel_img); ?>"
                         alt="<?php echo htmlspecialchars($rel['name']); ?>"
                         loading="lazy">
                    <div class="related-card-body">
                        <p class="related-card-name"><?php echo htmlspecialchars($rel['name']); ?></p>
                        <?php if ($rel['size']): ?>
                            <p class="related-card-size"><?php echo htmlspecialchars($rel['size']); ?></p>
                        <?php endif; ?>
                        <p class="related-card-price">
                            <?php if ($rel_price['on_sale']): ?>
                                <del>$<?php echo number_format($rel_price['regular'], 2); ?></del>
                            <?php endif; ?>
                            $<?php echo number_format($rel_price['price'], 2); ?>
                        </p>
                    </div>
                </a>
                <div style="padding:0 14px 14px;">
                    <button class="related-card-btn"
                        onclick="addToCart(<?php echo (int)$rel['id']; ?>, '<?php echo addslashes(htmlspecialchars($rel['name'])); ?>', <?php echo (float)$rel_price['price']; ?>, '<?php echo htmlspecialchars($rel_img, ENT_QUOTES); ?>')">
                        ADD TO CART
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
        <div class="footer-main">
            <div class="footer-section">
                <h3><?php echo SITE_NAME; ?></h3>
                <p>Premium BBS Wheels for the true enthusiast.</p>
                <p>Email: info@elitebbswheels.store</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shop">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/refund_returns">Refund Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/terms-conditions">Terms</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>

    <?php require INCLUDES_PATH . '/footer.php'; ?>

    <!-- PhotoSwipe v4 DOM template (required) -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- PhotoSwipe v4 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe-ui-default.min.js"></script>

    <script>
    // Gallery images data for PhotoSwipe
    var galleryImages = <?php echo json_encode(array_map(function($img) use ($product) {
        return ['src' => $img, 'w' => 1200, 'h' => 960, 'title' => htmlspecialchars($product['name'], ENT_QUOTES)];
    }, !empty($images) ? $images : [$og_image])); ?>;

    var currentImageIndex = 0;

    function switchImage(thumb, url, index) {
        document.getElementById('main-image').src = url;
        var mainLink = document.getElementById('gallery-main-link');
        if (mainLink) mainLink.href = url;
        currentImageIndex = index || 0;
        document.querySelectorAll('.product-gallery-thumbs img').forEach(function(t) {
            t.classList.remove('active');
        });
        thumb.classList.add('active');
    }

    function openLightbox(index) {
        var pswpEl = document.querySelector('.pswp');
        var options = {
            index: index || 0,
            shareEl: false,
            closeOnScroll: false,
            history: false
        };
        var gallery = new PhotoSwipe(pswpEl, PhotoSwipeUI_Default, galleryImages, options);
        gallery.init();
    }

    function switchTab(btn, panelId) {
        document.querySelectorAll('.description-tab').forEach(function(t) {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('.tab-panel').forEach(function(p) {
            p.classList.remove('active');
            p.style.display = 'none';
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        var panel = document.getElementById(panelId);
        if (panel) {
            panel.classList.add('active');
            panel.style.display = 'block';
        }
    }

    function changeQty(delta) {
        var input = document.getElementById('qty');
        var min = parseInt(input.min) || 1;
        var max = parseInt(input.max) || 9999;
        var val = parseInt(input.value) || 1;
        input.value = Math.min(max, Math.max(min, val + delta));
    }

    function addToCartFromDetail() {
        var productId = document.getElementById('product-id').value;
        var name      = document.getElementById('product-name').value;
        var price     = document.getElementById('product-price').value;
        var image     = document.getElementById('product-image').value;
        var qty       = parseInt(document.getElementById('qty').value) || 1;
        addToCart(productId, name, price, image, qty);
    }
    </script>
</body>
</html>
