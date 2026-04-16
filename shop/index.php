<?php
/**
 * Shop Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'shop';
$page_title = "Shop - " . SITE_NAME;
$page_description = "Browse our selection of premium BBS wheels.";

$search    = isset($_GET['search'])    ? trim($_GET['search'])    : '';
$category  = isset($_GET['category'])  ? trim($_GET['category'])  : '';
$orderby   = isset($_GET['orderby'])   ? trim($_GET['orderby'])   : 'menu_order';
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$per_page     = 12;
$current_page = max(1, (int)($_GET['paged'] ?? 1));

// Preserve filter params for pagination links (without 'paged')
$query_parts = [];
if ($search)          $query_parts[] = 'search='    . urlencode($search);
if ($category)        $query_parts[] = 'category='  . urlencode($category);
if ($orderby && $orderby !== 'menu_order') $query_parts[] = 'orderby=' . urlencode($orderby);
if ($min_price !== null) $query_parts[] = 'min_price=' . $min_price;
if ($max_price !== null) $query_parts[] = 'max_price=' . $max_price;
$query_string = $query_parts ? '&' . implode('&', $query_parts) : '';

$where  = "WHERE status = 'active'";
$params = [];

if ($search) {
    $where .= " AND (name LIKE :search OR description LIKE :search OR category LIKE :search OR brand LIKE :search OR sku LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}
if ($category) {
    $where .= " AND category = :category";
    $params[':category'] = $category;
}
if ($min_price !== null) {
    $where .= " AND price >= :min_price";
    $params[':min_price'] = $min_price;
}
if ($max_price !== null) {
    $where .= " AND price <= :max_price";
    $params[':max_price'] = $max_price;
}

// Price bounds for slider
$price_bounds = db_get_row("SELECT MIN(price) as min_p, MAX(price) as max_p FROM products WHERE status='active'");
$bound_min  = (int) floor($price_bounds['min_p'] ?? 0);
$bound_max  = (int) ceil($price_bounds['max_p']  ?? 10000);
$filter_min = $min_price !== null ? (int)$min_price : $bound_min;
$filter_max = $max_price !== null ? (int)$max_price : $bound_max;

$order_sql = match ($orderby) {
    'price'      => "ORDER BY price ASC",
    'price-desc' => "ORDER BY price DESC",
    'date'       => "ORDER BY created_at DESC",
    default      => "ORDER BY featured DESC, created_at DESC",
};

$total        = (int) db_get_one("SELECT COUNT(*) FROM products $where", $params);
$total_pages  = max(1, (int) ceil($total / $per_page));
$current_page = min($current_page, $total_pages);
$real_offset  = ($current_page - 1) * $per_page;

$products = db_get_all(
    "SELECT * FROM products $where $order_sql LIMIT $per_page OFFSET $real_offset",
    $params
);
$categories = get_all_categories();
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me/ns#" class="loading-site no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/shop/">

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- Flatsome theme CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">

    <!-- Theme customizations -->
    <style>
    :root {
        --primary-color: #008cb2;
        --fs-color-primary: #008cb2;
        --fs-color-secondary: #4ad8ff;
        --fs-color-success: #7a9c59;
        --fs-color-alert: #b20000;
        --fs-experimental-link-color: #22bfe6;
        --fs-experimental-link-color-hover: #111;
    }
    .header-main { height: 86px; }
    #logo img { max-height: 86px; }
    #logo { width: 136px; }
    .transparent .header-main { height: 90px; }
    .transparent #logo img { max-height: 90px; }
    .header-bg-color { background-color: rgba(10,10,10,0.9); }
    .header-bottom { background-color: #f1f1f1; }
    @media (max-width: 549px) { .header-main { height: 70px; } #logo img { max-height: 70px; } }
    .nav-dropdown { font-size: 100%; }
    .nav .nav-dropdown { background-color: #000000; }
    .nav-dropdown-has-arrow li.has-dropdown:after { border-bottom-color: #000000; }
    body { font-family: Lato, sans-serif; }
    .nav > li > a { font-family: Montserrat, sans-serif; font-weight: 700; }
    h1,h2,h3,h4,h5,h6 { font-family: Montserrat, sans-serif; }
    .alt-font { font-family: "Dancing Script", sans-serif; font-weight: 400 !important; }
    .footer-1 { background-color: #222; }
    .footer-2 { background-color: #111; }
    .absolute-footer, html { background-color: #000; }
    </style>

    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
    /* ADD TO CART button */
    .btn-add-to-cart {
        display: block;
        width: 100%;
        padding: 9px 0;
        margin-top: 10px;
        background: #008cb2;
        color: #fff !important;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        text-align: center;
        transition: background 0.2s;
    }
    .btn-add-to-cart:hover { background: #006e8a; }

    /* Pagination */
    nav.woocommerce-pagination { clear: both; width: 100%; margin: 30px 0 20px; }
    nav.woocommerce-pagination ul.page-numbers {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 6px !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 auto !important;
        float: none !important;
        width: auto !important;
    }
    nav.woocommerce-pagination ul.page-numbers li {
        display: inline-flex !important;
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    nav.woocommerce-pagination ul.page-numbers li a,
    nav.woocommerce-pagination ul.page-numbers li span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        border: 1px solid #ccc !important;
        color: #444 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        background: #fff !important;
        line-height: 1 !important;
        transition: background 0.2s, border-color 0.2s;
    }
    nav.woocommerce-pagination ul.page-numbers li a:hover { border-color: #008cb2 !important; color: #008cb2 !important; }
    nav.woocommerce-pagination ul.page-numbers li span.current { background: #008cb2 !important; border-color: #008cb2 !important; color: #fff !important; }
    nav.woocommerce-pagination ul.page-numbers li span.dots { border: none !important; background: transparent !important; }

    /* Dual range price slider */
    .price-slider-wrap { position: relative; height: 6px; margin: 18px 4px 24px; }
    .price-slider-track { position: absolute; top: 0; left: 0; right: 0; height: 6px; background: #ddd; border-radius: 3px; }
    .price-slider-range { position: absolute; top: 0; height: 6px; background: #008cb2; border-radius: 3px; }
    .price-slider-wrap input[type=range] {
        position: absolute;
        top: -5px;
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        background: transparent;
        pointer-events: none;
        margin: 0;
    }
    .price-slider-wrap input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #008cb2;
        cursor: pointer;
        pointer-events: all;
        position: relative;
        z-index: 1;
    }
    .price-slider-wrap input[type=range]::-moz-range-thumb {
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #008cb2;
        cursor: pointer;
        pointer-events: all;
    }
    .price-display { font-size: 13px; color: #444; margin-bottom: 10px; }
    .btn-price-filter {
        display: inline-block;
        padding: 7px 18px;
        background: #008cb2;
        color: #fff;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
    }
    .btn-price-filter:hover { background: #006e8a; }
    </style>
</head>
<body class="archive post-type-archive-product wp-theme-flatsome woocommerce-shop woocommerce woocommerce-page nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">

<a class="skip-link screen-reader-text" href="#main">Skip to content</a>

<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>


<!-- PAGE TITLE -->
<div class="shop-page-title category-page-title page-title">
    <div class="page-title-inner flex-row medium-flex-wrap container">
        <div class="flex-col flex-grow medium-text-center">
            <div class="is-large">
                <nav class="woocommerce-breadcrumb breadcrumbs uppercase">
                    <a href="<?php echo SITE_URL; ?>/">Home</a>
                    <span class="divider">/</span>
                    <?php if ($category): ?>
                        <a href="<?php echo SITE_URL; ?>/shop">Shop</a>
                        <span class="divider">/</span> <?php echo htmlspecialchars($category); ?>
                    <?php elseif ($search): ?>
                        <a href="<?php echo SITE_URL; ?>/shop">Shop</a>
                        <span class="divider">/</span> Search: "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        Shop
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <div class="flex-col medium-text-center">
            <?php
            $showing_from = $total > 0 ? ($current_page - 1) * $per_page + 1 : 0;
            $showing_to   = min($current_page * $per_page, $total);
            ?>
            <p class="woocommerce-result-count">Showing <?php echo $showing_from; ?>&#8211;<?php echo $showing_to; ?> of <?php echo $total; ?> results</p>
            <form class="woocommerce-ordering" method="get" action="<?php echo SITE_URL; ?>/shop">
                <?php if ($search): ?><input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
                <?php if ($category): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>"><?php endif; ?>
                <select name="orderby" class="orderby" onchange="this.form.submit()">
                    <option value="menu_order" <?php selected($orderby,'menu_order'); ?>>Default sorting</option>
                    <option value="date"        <?php selected($orderby,'date'); ?>>Sort by latest</option>
                    <option value="price"       <?php selected($orderby,'price'); ?>>Sort by price: low to high</option>
                    <option value="price-desc"  <?php selected($orderby,'price-desc'); ?>>Sort by price: high to low</option>
                </select>
            </form>
        </div>
    </div>
</div>

<!-- MAIN -->
<main id="main">
<div class="row category-page-row">

    <!-- SIDEBAR -->
    <div class="col large-3 hide-for-medium">
        <div id="shop-sidebar" class="sidebar-inner col-inner">

            <aside class="widget woocommerce widget_product_categories">
                <span class="widget-title shop-sidebar">Browse</span>
                <div class="is-divider small"></div>
                <ul class="product-categories">
                    <li class="cat-item<?php echo !$category ? ' current-cat' : ''; ?>">
                        <a href="<?php echo SITE_URL; ?>/shop">All Products</a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="cat-item<?php echo ($category === $cat['category']) ? ' current-cat' : ''; ?>">
                            <a href="<?php echo SITE_URL; ?>/shop?category=<?php echo urlencode($cat['category']); ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <aside class="widget woocommerce widget_price_filter">
                <span class="widget-title shop-sidebar">Filter by Price</span>
                <div class="is-divider small"></div>
                <form method="get" action="<?php echo SITE_URL; ?>/shop" id="price-filter-form">
                    <?php if ($search):   ?><input type="hidden" name="search"   value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
                    <?php if ($category): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>"><?php endif; ?>
                    <?php if ($orderby && $orderby !== 'menu_order'): ?><input type="hidden" name="orderby" value="<?php echo htmlspecialchars($orderby); ?>"><?php endif; ?>
                    <input type="hidden" name="min_price" id="pf-min-input" value="<?php echo $filter_min; ?>">
                    <input type="hidden" name="max_price" id="pf-max-input" value="<?php echo $filter_max; ?>">

                    <div class="price-slider-wrap">
                        <div class="price-slider-track"></div>
                        <div class="price-slider-range" id="pf-range"></div>
                        <input type="range" id="pf-min" min="<?php echo $bound_min; ?>" max="<?php echo $bound_max; ?>" value="<?php echo $filter_min; ?>" step="1">
                        <input type="range" id="pf-max" min="<?php echo $bound_min; ?>" max="<?php echo $bound_max; ?>" value="<?php echo $filter_max; ?>" step="1">
                    </div>
                    <p class="price-display">Price: $<span id="pf-min-label"><?php echo number_format($filter_min); ?></span> &#8212; $<span id="pf-max-label"><?php echo number_format($filter_max); ?></span></p>
                    <button type="submit" class="btn-price-filter">Filter</button>
                </form>
            </aside>

        </div>
    </div>

    <!-- PRODUCTS -->
    <div class="col large-9">
    <div class="shop-container">
        <div class="woocommerce-notices-wrapper"></div>

        <?php if (empty($products)): ?>
            <div class="col-inner" style="text-align:center; padding: 60px 20px;">
                <p>No products found.</p>
                <a href="<?php echo SITE_URL; ?>/shop" class="button primary is-outline">View all products</a>
            </div>
        <?php else: ?>
        <div class="products row row-small large-columns-3 medium-columns-3 small-columns-2">

            <?php foreach ($products as $product):
                $images     = json_decode($product['images'] ?? '[]', true);
                $img        = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
                $price_info = get_display_price($product);
                $is_sale    = $price_info['on_sale'];
            ?>
            <div class="product-small col has-hover product type-product<?php echo $is_sale ? ' sale' : ''; ?>">
                <div class="col-inner">

                    <?php if ($is_sale): ?>
                    <div class="badge-container absolute left top z-1">
                        <div class="callout badge badge-circle">
                            <div class="badge-inner secondary on-sale"><span class="onsale">Sale!</span></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="product-small box">
                        <div class="box-image">
                            <div class="image-fade_in_back">
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                    <img src="<?php echo htmlspecialchars($img); ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         loading="lazy">
                                </a>
                            </div>
                            <div class="image-tools grid-tools text-center hide-for-small bottom hover-slide-in show-on-hover">
                                <a class="quick-view" href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">Quick View</a>
                            </div>
                        </div>

                        <div class="box-text box-text-products">
                            <div class="title-wrapper">
                                <p class="category uppercase is-smaller no-text-overflow product-cat op-7">
                                    <?php echo htmlspecialchars($product['category'] ?? 'ALL PRODUCTS'); ?>
                                </p>
                                <p class="name product-title woocommerce-loop-product__title">
                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </p>
                                <?php if (!empty($product['size'])): ?>
                                <p class="is-smaller op-7" style="margin:2px 0 6px;font-size:11px;color:#999;">
                                    <?php echo htmlspecialchars($product['size']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="price-wrapper">
                                <span class="price">
                                    <?php if ($is_sale): ?>
                                        <del><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['regular'], 2); ?></span></del>
                                        <ins><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span></ins>
                                    <?php else: ?>
                                        <span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="add-to-cart-button">
                                <button class="btn-add-to-cart"
                                    onclick="addToCart(<?php echo (int)$product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>', <?php echo (float)$price_info['price']; ?>, '<?php echo htmlspecialchars($img, ENT_QUOTES); ?>')">
                                    ADD TO CART
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>

        </div><!-- .products -->

        <?php endif; ?>
    </div><!-- .shop-container -->
    </div><!-- .col.large-9 -->

</div><!-- .row -->

<?php if ($total_pages > 1): ?>
<div style="clear:both;width:100%;padding:30px 0 20px;">
    <nav class="woocommerce-pagination">
        <ul class="page-numbers">
            <?php if ($current_page > 1): ?>
            <li><a class="page-numbers prev" href="?paged=<?php echo $current_page - 1; ?><?php echo $query_string; ?>">&#8592;</a></li>
            <?php endif; ?>
            <?php
            $start = max(1, $current_page - 3);
            $end   = min($total_pages, $current_page + 3);
            if ($start > 1): ?>
                <li><a class="page-numbers" href="?paged=1<?php echo $query_string; ?>">1</a></li>
                <?php if ($start > 2): ?><li><span class="page-numbers dots">&hellip;</span></li><?php endif; ?>
            <?php endif; ?>
            <?php for ($i = $start; $i <= $end; $i++): ?>
            <li>
                <?php if ($i === $current_page): ?>
                <span class="page-numbers current"><?php echo $i; ?></span>
                <?php else: ?>
                <a class="page-numbers" href="?paged=<?php echo $i; ?><?php echo $query_string; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            </li>
            <?php endfor; ?>
            <?php if ($end < $total_pages): ?>
                <?php if ($end < $total_pages - 1): ?><li><span class="page-numbers dots">&hellip;</span></li><?php endif; ?>
                <li><a class="page-numbers" href="?paged=<?php echo $total_pages; ?><?php echo $query_string; ?>"><?php echo $total_pages; ?></a></li>
            <?php endif; ?>
            <?php if ($current_page < $total_pages): ?>
            <li><a class="page-numbers next" href="?paged=<?php echo $current_page + 1; ?><?php echo $query_string; ?>">&#8594;</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>
</main>

<!-- MOBILE SIDEBAR -->
<div id="main-menu" class="mobile-sidebar no-scrollbar mfp-hide">
    <div class="sidebar-menu no-scrollbar">
        <ul class="nav nav-sidebar nav-vertical nav-uppercase">
            <li class="header-search-form search-form html relative has-icon">
                <form role="search" method="get" action="<?php echo SITE_URL; ?>/shop" class="searchform">
                    <div class="flex-row relative">
                        <div class="flex-col flex-grow">
                            <input type="search" class="search-field mb-0" placeholder="Search..." name="search">
                        </div>
                        <div class="flex-col">
                            <button type="submit" class="submit-button secondary button icon mb-0"><i class="icon-search"></i></button>
                        </div>
                    </div>
                </form>
            </li>
            <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop" class="active">Shop</a></li>
            <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
            <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
            <li><a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a></li>
            <li><a href="<?php echo SITE_URL; ?>/refund_returns">Refund and Returns Policy</a></li>
            <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
            <li><a href="<?php echo SITE_URL; ?>/my-account">Login</a></li>
        </ul>
    </div>
</div>

<!-- FOOTER -->
<footer id="footer" class="footer-wrapper">

    <div class="footer-widgets footer footer-1">
        <div class="row dark large-columns-3 mb-0" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

            <div class="col pb-0 widget woocommerce widget_products">
                <span class="widget-title">Latest</span>
                <div class="is-divider small"></div>
                <ul class="product_list_widget">
                    <?php foreach (array_slice(get_all_products(), 0, 4) as $fp):
                        $fi = json_decode($fp['images'] ?? '[]', true);
                        $fimg = !empty($fi[0]) ? $fi[0] : asset_url('images/placeholder.png');
                        $fpi = get_display_price($fp);
                    ?>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/product/<?php echo $fp['slug']; ?>">
                            <img src="<?php echo htmlspecialchars($fimg); ?>" alt="<?php echo htmlspecialchars($fp['name']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                            <span class="product-title"><?php echo htmlspecialchars($fp['name']); ?></span>
                        </a>
                        <span class="woocommerce-Price-amount amount">$<?php echo number_format($fpi['price'], 2); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col pb-0 widget block_widget footer-about">
                <span class="widget-title">About us</span>
                <div class="is-divider small"></div>
                <p>Welcome to Elite BBS Wheels, your premier boutique destination for genuine BBS forged and performance wheels in America. We specialize in hand-selecting iconic, lightweight, and timeless BBS rims that blend German motorsport heritage with street-dominating style.</p>
            </div>

            <div class="col pb-0 widget widget_text footer-contact">
                <span class="widget-title">Contact us</span>
                <div class="is-divider small"></div>
                <p><a href="mailto:Sales@elitebbswheelsus.shop">Sales@elitebbswheelsus.shop</a></p>
                <p><a href="tel:+16177082284">+1(617)708-2284</a></p>
                <p>20802 Highland Knolls Drive<br>Katy, TX 77450, USA</p>
            </div>

        </div>
    </div>

    <div class="footer-widgets footer footer-2 dark">
        <div class="row dark large-columns-3 mb-0" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div class="col pb-0 widget widget_nav_menu">
                <span class="widget-title">Legal Policy</span>
                <div class="is-divider small"></div>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/refund_returns">Refund and Returns Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/privacy-policy">Privacy Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/terms-conditions">Terms &amp; Conditions</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shipping-policy">Shipping Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="col pb-0 widget">
                <span class="widget-title">Quick Links</span>
                <div class="is-divider small"></div>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shop">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a></li>
                </ul>
            </div>
            <div class="col pb-0 widget footer-contact">
                <span class="widget-title">Contact us</span>
                <div class="is-divider small"></div>
                <p><a href="mailto:Sales@elitebbswheelsus.shop">Sales@elitebbswheelsus.shop</a></p>
                <p><a href="tel:+16177082284">+1(617)708-2284</a></p>
                <p>Monday - Friday, 9:00 AM - 5:00 PM</p>
            </div>
        </div>
    </div>

    <div class="absolute-footer dark medium-text-center text-center">
        <div class="container clearfix">
            <div class="footer-secondary pull-right">
                <div class="payment-icons inline-block">
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349221.png" alt="Visa" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349228.png" alt="Mastercard" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349230.png" alt="Amex" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/888/888870.png" alt="Apple Pay" style="height:24px;"></div>
                </div>
            </div>
            <div class="footer-primary pull-left">
                <div class="links footer-nav uppercase" style="display:flex;justify-content:center;gap:20px;margin-bottom:15px;flex-wrap:wrap;">
                    <a href="<?php echo SITE_URL; ?>/refund_returns">Refund Policy</a>
                    <a href="<?php echo SITE_URL; ?>/privacy-policy">Privacy Policy</a>
                    <a href="<?php echo SITE_URL; ?>/terms-conditions">Terms &amp; Conditions</a>
                    <a href="<?php echo SITE_URL; ?>/shipping-policy">Shipping Policy</a>
                    <a href="<?php echo SITE_URL; ?>/faq">FAQ</a>
                </div>
                <div class="copyright-footer">
                    Copyright <?php echo date('Y'); ?> &copy; <strong>ELITE BBS RIMS</strong>
                </div>
            </div>
        </div>
    </div>

</footer>

<a href="#top" class="back-to-top button icon invert plain fixed bottom z-1 is-outline hide-for-medium circle" id="top-link" aria-label="Go to top">
    <i class="icon-angle-up"></i>
</a>

</div><!-- #wrapper -->

<script src="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/js/flatsomed02f.js"></script>
<script>var siteUrl = '<?php echo SITE_URL; ?>';</script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
(function() {
    var minInput  = document.getElementById('pf-min');
    var maxInput  = document.getElementById('pf-max');
    var minHidden = document.getElementById('pf-min-input');
    var maxHidden = document.getElementById('pf-max-input');
    var minLabel  = document.getElementById('pf-min-label');
    var maxLabel  = document.getElementById('pf-max-label');
    var range     = document.getElementById('pf-range');
    if (!minInput) return;

    function updateSlider() {
        var lo = parseInt(minInput.value);
        var hi = parseInt(maxInput.value);
        if (lo > hi) { var t = lo; lo = hi; hi = t; }
        var total = parseInt(minInput.max) - parseInt(minInput.min);
        var pctLo = ((lo - parseInt(minInput.min)) / total) * 100;
        var pctHi = ((hi - parseInt(minInput.min)) / total) * 100;
        range.style.left  = pctLo + '%';
        range.style.width = (pctHi - pctLo) + '%';
        minLabel.textContent = lo.toLocaleString();
        maxLabel.textContent = hi.toLocaleString();
        minHidden.value = lo;
        maxHidden.value = hi;
    }

    minInput.addEventListener('input', updateSlider);
    maxInput.addEventListener('input', updateSlider);
    updateSlider();
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    // Mobile menu toggle
    var toggle = document.querySelector('[data-open="#main-menu"]');
    if (toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            var menu = document.getElementById('main-menu');
            if (menu) { menu.classList.toggle('open'); menu.style.transform = 'translateX(0)'; }
        });
    }
    // Back to top
    var btt = document.getElementById('top-link');
    if (btt) {
        window.addEventListener('scroll', function() {
            btt.classList.toggle('show', window.scrollY > 500);
        });
    }
});
</script>

</body>
</html>

<?php
// Helper used only on this page
function selected($current, $value) {
    echo $current === $value ? "selected='selected'" : '';
}
?>
