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

$page_title = htmlspecialchars($product['name']) . " - " . SITE_NAME;
$page_description = htmlspecialchars($product['short_description'] ?? '');
$images = json_decode($product['images'] ?? '[]', true);
$price_info = get_display_price($product);

// Related products: same category, excluding current
$related = db_get_all(
    "SELECT * FROM products WHERE status='active' AND category=:cat AND id!=:id ORDER BY RAND() LIMIT 4",
    [':cat' => $product['category'], ':id' => $product['id']]
);

?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me_ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="canonical" href="<?php echo site_url('product/' . $slug); ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    
    <style>
        /* Product Detail */
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

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

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

        .product-meta p:last-child {
            border-bottom: none;
        }

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

        .in-stock {
            background: #d4edda;
            color: #155724;
        }

        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }

        .add-to-cart-section {
            margin: 30px 0;
        }

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

        .qty-btn:hover {
            background: #e0e0e0;
        }

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

        .add-to-cart-btn:hover {
            background: #006f8f;
        }

        /* Description full-width section */
        .product-description-section {
            max-width: 1200px;
            margin: 50px auto 0;
            padding: 0 20px;
        }

        .description-tabs {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 0;
        }

        .description-tab {
            padding: 14px 30px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
            background: #222;
            border: none;
            cursor: default;
            position: relative;
            top: 2px;
        }

        .description-body {
            border: 1px solid #e0e0e0;
            border-top: none;
            padding: 36px 40px;
            background: #fff;
            line-height: 1.9;
            color: #444;
            font-size: 14px;
        }

        .description-body .desc-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #111;
            margin: 0 0 18px;
        }

        .description-body p {
            margin: 0 0 14px;
        }

        .description-body .desc-features-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: #111;
            margin: 22px 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .description-body ol,
        .description-body ul {
            padding-left: 20px;
            margin: 0 0 14px;
        }

        .description-body li {
            margin-bottom: 8px;
        }

        .description-body li strong {
            color: #111;
        }

        .product-fitment {
            margin-top: 30px;
            padding: 20px;
            background: #f0f7ff;
            border-radius: 8px;
            border-left: 4px solid #008cb2;
        }

        .product-fitment h3 {
            margin-bottom: 10px;
        }

        /* Related products */
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

        .related-card-body {
            padding: 12px 14px 14px;
        }

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

        .related-card-size {
            font-size: 11px;
            color: #999;
            margin: 0 0 6px;
        }

        .related-card-price {
            font-size: 14px;
            font-weight: 700;
            color: #222;
            margin: 0 0 10px;
        }

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

        @media (max-width: 768px) {
            .related-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
            }

            .product-gallery {
                position: static;
            }
        }

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
                <?php echo sanitize($product['name']); ?>
            </div>
            
            <div class="product-detail-grid">
                <!-- Gallery -->
                <div class="product-gallery">
                    <div class="product-gallery-main">
                        <?php if (!empty($images[0])): ?>
                            <img src="<?php echo $images[0]; ?>" alt="<?php echo sanitize($product['name']); ?>" id="main-image">
                        <?php else: ?>
                            <img src="<?php echo asset_url('images/placeholder.png'); ?>" alt="No image">
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="product-gallery-thumbs">
                            <?php foreach ($images as $index => $img): ?>
                                <img src="<?php echo $img; ?>" onclick="document.getElementById('main-image').src='<?php echo $img; ?>'" class="<?php echo $index === 0 ? 'active' : ''; ?>">
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
                    
                    <div class="product-meta">
                        <?php if ($product['category']): ?>
                            <p><strong>Category:</strong> <?php echo sanitize($product['category']); ?></p>
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
                                <input type="hidden" id="product-id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" id="product-name" value="<?php echo sanitize($product['name']); ?>">
                                <input type="hidden" id="product-price" value="<?php echo $price_info['price']; ?>">
                                <input type="hidden" id="product-image" value="<?php echo !empty($images[0]) ? $images[0] : ''; ?>">

                                <div class="qty-wrapper">
                                    <label>Quantity:</label>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn qty-minus" onclick="changeQty(-1)">−</button>
                                        <input type="number" id="qty" class="qty-input" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                        <button type="button" class="qty-btn qty-plus" onclick="changeQty(1)">+</button>
                                    </div>
                                </div>

                                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="add-to-cart-section">
                            <button class="add-to-cart-btn" disabled style="background: #999; cursor: not-allowed;">Out of Stock</button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['fitment_data']): ?>
                        <div class="product-fitment">
                            <h3>Vehicle Fitment</h3>
                            <p><?php echo sanitize($product['fitment_data']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Description -->
    <?php $desc = trim($product['short_description'] ?? $product['description'] ?? ''); ?>
    <?php if ($desc): ?>
    <div class="product-description-section">
        <div class="description-tabs">
            <span class="description-tab">Description</span>
        </div>
        <div class="description-body">
            <?php
            // Bold first sentence as title, then render rest as paragraphs
            // Split on newlines for key features
            $lines = array_filter(array_map('trim', explode("\n", $desc)));
            $first = array_shift($lines);
            echo '<p class="desc-title">' . htmlspecialchars($first) . '</p>';

            $in_list   = false;
            $list_html = '';
            foreach ($lines as $line) {
                // Detect numbered feature lines like "1. Striking..." or "- ..."
                if (preg_match('/^(\d+\.\s|\-\s)/', $line)) {
                    if (!$in_list) {
                        echo '<ol>';
                        $in_list = true;
                    }
                    $clean = preg_replace('/^(\d+\.\s|\-\s)/', '', $line);
                    // Bold the part before the first colon
                    $clean = preg_replace('/^([^:]+):/', '<strong>$1:</strong>', htmlspecialchars($clean));
                    echo '<li>' . $clean . '</li>';
                } else {
                    if ($in_list) { echo '</ol>'; $in_list = false; }
                    if (stripos($line, 'key feature') !== false || stripos($line, 'why choose') !== false) {
                        echo '<p class="desc-features-title">' . htmlspecialchars($line) . '</p>';
                    } else {
                        echo '<p>' . htmlspecialchars($line) . '</p>';
                    }
                }
            }
            if ($in_list) echo '</ol>';
            ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Products -->
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
                    <img class="related-card-img" src="<?php echo htmlspecialchars($rel_img); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>" loading="lazy">
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
                <div style="padding: 0 14px 14px;">
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
                <p>Email: info@elitebbswheelsus.shop</p>
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
    <script>
    function changeQty(delta) {
        const input = document.getElementById('qty');
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 9999;
        let val = parseInt(input.value) || 1;
        val = Math.min(max, Math.max(min, val + delta));
        input.value = val;
    }
    function addToCartFromDetail() {
        const productId = document.getElementById('product-id').value;
        const name = document.getElementById('product-name').value;
        const price = document.getElementById('product-price').value;
        const image = document.getElementById('product-image').value;
        const qty = parseInt(document.getElementById('qty').value) || 1;
        addToCart(productId, name, price, image, qty);
    }
    </script>
</body>
</html>