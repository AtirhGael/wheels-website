<?php
/**
 * Cart Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/cart_functions.php';

$page = 'cart';
$page_title = "Shopping Cart - " . SITE_NAME;

/* ── Coupon definitions ── */
$coupons = [
    'ELITE10'   => ['type' => 'percent', 'value' => 10,  'label' => '10% off'],
    'BBS20'     => ['type' => 'percent', 'value' => 20,  'label' => '20% off'],
    'WELCOME15' => ['type' => 'percent', 'value' => 15,  'label' => '15% off'],
    'SAVE50'    => ['type' => 'fixed',   'value' => 50,  'label' => '$50 off'],
];

$coupon_msg   = '';
$coupon_type  = ''; // 'success' | 'error'

/* ── Apply coupon ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'apply_coupon') {
        $code = strtoupper(trim($_POST['coupon_code'] ?? ''));
        if ($code === '') {
            $coupon_msg  = 'Please enter a coupon code.';
            $coupon_type = 'error';
        } elseif (isset($coupons[$code])) {
            $_SESSION['coupon'] = ['code' => $code] + $coupons[$code];
            $coupon_msg  = 'Coupon <strong>' . htmlspecialchars($code) . '</strong> applied — ' . $coupons[$code]['label'] . '!';
            $coupon_type = 'success';
        } else {
            $coupon_msg  = 'Invalid coupon code. Please try again.';
            $coupon_type = 'error';
        }
    } elseif ($_POST['action'] === 'remove_coupon') {
        unset($_SESSION['coupon']);
        $coupon_msg  = 'Coupon removed.';
        $coupon_type = 'success';
    } elseif ($_POST['action'] === 'update_cart') {
        $quantities = $_POST['quantities'] ?? [];
        foreach ($quantities as $pid => $qty) {
            cart_update((int)$pid, (int)$qty);
        }
        $coupon_msg  = 'Cart updated.';
        $coupon_type = 'success';
    }
}

$cart_items   = cart_items();
$cart_is_empty = cart_is_empty();
$subtotal     = cart_total();

/* ── Compute discount ── */
$discount     = 0;
$active_coupon = $_SESSION['coupon'] ?? null;
if ($active_coupon) {
    if ($active_coupon['type'] === 'percent') {
        $discount = $subtotal * ($active_coupon['value'] / 100);
    } else {
        $discount = min($active_coupon['value'], $subtotal);
    }
}
$grand_total = max(0, $subtotal - $discount);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Lato:wght@400;700&family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
        /* ── Flatsome header overrides ── */
        .header-main { height: 86px; }
        #logo { width: auto !important; }
        #logo img { display: none; }
        .header-bg-color { background-color: rgba(10,10,10,0.92) !important; }
        .nav > li > a { font-family: Montserrat, sans-serif; font-weight: 700; color: #fff; }
        .nav .nav-dropdown { background-color: #000; }
        .nav-dropdown { font-size: 100%; }
        @media (max-width: 549px) { .header-main { height: 70px; } }

        /* ── Footer ── */
        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }

        /* ── Page base ── */
        body { background: #f4f6f8; }

        /* ── Page header ── */
        .cart-page-hero {
            background: linear-gradient(135deg, #0d0f13 0%, #1a1f2e 100%);
            padding: 36px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .cart-page-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .cart-breadcrumb {
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 10px;
        }
        .cart-breadcrumb a { color: #008cb2; text-decoration: none; }
        .cart-breadcrumb a:hover { color: #fff; }
        .cart-breadcrumb .sep { margin: 0 8px; color: rgba(255,255,255,0.2); }
        .cart-page-hero h1 {
            font-family: 'Barlow', sans-serif;
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 900;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        /* ── Layout ── */
        .cart-wrap {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 32px;
            align-items: start;
        }

        /* ── Flash message ── */
        .cart-notice-flash {
            grid-column: 1 / -1;
            padding: 14px 20px;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 600;
        }
        .cart-notice-flash.success { background: #e8f8f0; border: 1px solid #a3d9b8; color: #1d6b3e; }
        .cart-notice-flash.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }

        /* ── Cart table card ── */
        .cart-main { display: flex; flex-direction: column; gap: 20px; }

        .cart-table-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table thead tr {
            background: #f8f9fb;
        }
        .cart-table th {
            padding: 14px 16px;
            text-align: left;
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #888;
            border-bottom: 1px solid #eef0f3;
        }
        .cart-table td {
            padding: 18px 16px;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }
        .cart-table tbody tr:last-child td { border-bottom: none; }
        .cart-table tbody tr:hover { background: #fafbfc; }

        /* Product cell */
        .prod-cell { display: flex; align-items: center; gap: 14px; }
        .prod-img {
            width: 72px; height: 72px;
            object-fit: cover;
            border-radius: 8px;
            background: #f5f5f5;
            flex-shrink: 0;
            border: 1px solid #eee;
        }
        .prod-name {
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 800;
            color: #1a1a1a;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.3;
            display: block;
            margin-bottom: 4px;
        }
        .prod-name:hover { color: #008cb2; }
        .prod-sku { font-size: 11px; color: #aaa; font-family: 'Barlow', sans-serif; letter-spacing: 0.5px; }

        /* Price / total cells */
        .price-cell {
            font-family: 'Barlow', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #008cb2;
        }
        .total-cell {
            font-family: 'Barlow', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #1a1a1a;
        }

        /* Qty input */
        .qty-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #dde1e7;
            border-radius: 8px;
            overflow: hidden;
            width: fit-content;
            background: #fff;
        }
        .qty-btn {
            width: 32px; height: 38px;
            background: #f4f6f8;
            border: none;
            font-size: 16px;
            color: #555;
            cursor: pointer;
            transition: background 0.15s;
            display: flex; align-items: center; justify-content: center;
        }
        .qty-btn:hover { background: #e8ecf0; color: #111; }
        .qty-input {
            width: 44px; height: 38px;
            border: none;
            text-align: center;
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #111;
            background: #fff;
            outline: none;
            -moz-appearance: textfield;
        }
        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }

        /* Remove btn */
        .remove-btn {
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 4px 8px;
            transition: color 0.2s;
            border-radius: 6px;
        }
        .remove-btn:hover { color: #e53e3e; background: #fff5f5; }

        /* ── Update cart row ── */
        .cart-actions-row {
            display: flex;
            justify-content: flex-end;
            padding: 14px 16px;
            background: #f8f9fb;
            border-top: 1px solid #eef0f3;
        }
        .update-cart-btn {
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 10px 24px;
            background: transparent;
            color: #555;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .update-cart-btn:hover { border-color: #008cb2; color: #008cb2; background: #f0f9fc; }

        /* ── Coupon card ── */
        .coupon-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px 24px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
        }
        .coupon-card-title {
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .coupon-card-title::before {
            content: '';
            display: inline-block;
            width: 14px; height: 14px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            border-radius: 3px;
        }
        .coupon-row {
            display: flex;
            gap: 10px;
        }
        .coupon-input {
            flex: 1;
            padding: 11px 14px;
            border: 1.5px solid #dde1e7;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s;
        }
        .coupon-input::placeholder { color: #bbb; font-weight: 500; text-transform: none; letter-spacing: 0; }
        .coupon-input:focus { border-color: #008cb2; }
        .coupon-apply-btn {
            padding: 11px 22px;
            background: #1a1a1a;
            color: #fff;
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .coupon-apply-btn:hover { background: #008cb2; }
        .coupon-active {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            padding: 10px 14px;
            background: #e8f8f0;
            border: 1px solid #a3d9b8;
            border-radius: 8px;
        }
        .coupon-active-label {
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #1d6b3e;
        }
        .coupon-remove-btn {
            background: none;
            border: none;
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #999;
            cursor: pointer;
            padding: 2px 6px;
            transition: color 0.2s;
        }
        .coupon-remove-btn:hover { color: #e53e3e; }

        /* ── Cart empty ── */
        .cart-empty-wrap {
            grid-column: 1 / -1;
            background: #fff;
            border-radius: 12px;
            text-align: center;
            padding: 80px 40px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
        }
        .cart-empty-icon {
            font-size: 56px;
            margin-bottom: 20px;
            opacity: 0.25;
        }
        .cart-empty-wrap h2 {
            font-family: 'Barlow', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .cart-empty-wrap p { color: #888; margin-bottom: 28px; }
        .btn-shop {
            display: inline-block;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
            padding: 14px 36px;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s;
        }
        .btn-shop:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,140,178,0.35); color: #fff; }

        /* ── Sidebar summary ── */
        .cart-sidebar {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
            position: sticky;
            top: 100px;
        }
        .sidebar-header {
            background: #1a1a1a;
            padding: 18px 24px;
        }
        .sidebar-header h2 {
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
        }
        .sidebar-body { padding: 20px 24px; }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
            color: #555;
        }
        .totals-row:last-child { border-bottom: none; }
        .totals-row .label { font-family: 'Barlow', sans-serif; font-weight: 600; }
        .totals-row .val   { font-family: 'Barlow', sans-serif; font-weight: 700; color: #1a1a1a; }
        .totals-row.discount .val { color: #16a34a; }
        .totals-row.grand {
            padding: 16px 0 4px;
            border-top: 2px solid #1a1a1a;
            border-bottom: none;
            margin-top: 6px;
        }
        .totals-row.grand .label {
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 900;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .totals-row.grand .val {
            font-family: 'Barlow', sans-serif;
            font-size: 22px;
            font-weight: 900;
            color: #008cb2;
        }

        .sidebar-actions { padding: 0 24px 24px; }
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.25s;
            border: none;
            cursor: pointer;
            margin-bottom: 12px;
        }
        .checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,140,178,0.35); color: #fff; }
        .continue-link {
            display: block;
            text-align: center;
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #aaa;
            text-decoration: none;
            transition: color 0.2s;
        }
        .continue-link:hover { color: #008cb2; }

        .sidebar-trust {
            padding: 16px 24px;
            border-top: 1px solid #f0f2f5;
            font-size: 12px;
            color: #999;
            line-height: 1.6;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .cart-wrap { grid-template-columns: 1fr; }
            .cart-sidebar { position: static; }
            .cart-table th:nth-child(2),
            .cart-table td:nth-child(2) { display: none; }
        }
        @media (max-width: 600px) {
            .prod-cell { flex-direction: column; align-items: flex-start; }
            .prod-img { width: 100%; height: 160px; border-radius: 8px; }
        }
    </style>
</head>
<body class="woocommerce-cart wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">

    <?php require INCLUDES_PATH . '/header.php'; ?>

    <!-- Page hero -->
    <div class="cart-page-hero">
        <div class="cart-page-hero-inner">
            <div class="cart-breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="sep">›</span>
                <span>Shopping Cart</span>
            </div>
            <h1>Shopping Cart</h1>
        </div>
    </div>

    <main>
        <form method="post" action="">
        <input type="hidden" name="action" value="update_cart">
        <div class="cart-wrap">

            <?php if ($coupon_msg): ?>
            <div class="cart-notice-flash <?php echo $coupon_type; ?>"><?php echo $coupon_msg; ?></div>
            <?php endif; ?>

            <?php if ($cart_is_empty): ?>

            <div class="cart-empty-wrap">
                <div class="cart-empty-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>You haven't added any wheels yet. Browse our full collection.</p>
                <a href="<?php echo SITE_URL; ?>/shop" class="btn-shop">Browse Wheels</a>
            </div>

            <?php else: ?>

            <!-- Left: table + coupon -->
            <div class="cart-main">

                <!-- Cart table -->
                <div class="cart-table-card">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $id => $item): ?>
                            <tr>
                                <td>
                                    <div class="prod-cell">
                                        <?php
                                        $img = $item['image'] ?: asset_url('images/placeholder.png');
                                        ?>
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="prod-img">
                                        <div>
                                            <a href="<?php echo SITE_URL; ?>/product/<?php echo $item['slug']; ?>" class="prod-name"><?php echo htmlspecialchars($item['name']); ?></a>
                                            <?php if ($item['sku']): ?>
                                            <span class="prod-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="price-cell"><?php echo format_price($item['price']); ?></td>
                                <td>
                                    <div class="qty-wrap">
                                        <button type="button" class="qty-btn" onclick="adjustQty(this, -1)">&#8722;</button>
                                        <input type="number" name="quantities[<?php echo $id; ?>]" class="qty-input"
                                               value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                        <button type="button" class="qty-btn" onclick="adjustQty(this, 1)">&#43;</button>
                                    </div>
                                </td>
                                <td class="total-cell"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
                                <td>
                                    <button type="button" class="remove-btn" onclick="removeFromCart(<?php echo $id; ?>)" title="Remove item">&times;</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-actions-row">
                        <button type="submit" class="update-cart-btn">Update Cart</button>
                    </div>
                </div>

                <!-- Coupon code -->
                <div class="coupon-card">
                    <div class="coupon-card-title">Coupon Code</div>
                    <?php if ($active_coupon): ?>
                    <div class="coupon-active">
                        <span class="coupon-active-label">
                            <strong><?php echo htmlspecialchars($active_coupon['code']); ?></strong>
                            &mdash; <?php echo htmlspecialchars($active_coupon['label']); ?>
                        </span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action" value="remove_coupon">
                            <button type="submit" class="coupon-remove-btn">Remove</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="coupon-row">
                        <input type="text" name="coupon_code" class="coupon-input" placeholder="Enter coupon code" autocomplete="off" form="coupon-form">
                        <button type="submit" form="coupon-form" class="coupon-apply-btn">Apply</button>
                    </div>
                    <?php endif; ?>
                </div>

            </div><!-- .cart-main -->

            <!-- Right: order summary -->
            <div class="cart-sidebar">
                <div class="sidebar-header">
                    <h2>Order Summary</h2>
                </div>
                <div class="sidebar-body">
                    <div class="totals-row">
                        <span class="label">Subtotal</span>
                        <span class="val"><?php echo format_price($subtotal); ?></span>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="totals-row discount">
                        <span class="label">Discount (<?php echo htmlspecialchars($active_coupon['code']); ?>)</span>
                        <span class="val">&#8722;<?php echo format_price($discount); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="totals-row">
                        <span class="label">Shipping</span>
                        <span class="val" style="color:#888;font-size:12px;">Calculated at checkout</span>
                    </div>
                    <div class="totals-row grand">
                        <span class="label">Total</span>
                        <span class="val"><?php echo format_price($grand_total); ?></span>
                    </div>
                </div>
                <div class="sidebar-actions">
                    <a href="<?php echo SITE_URL; ?>/checkout" class="checkout-btn">Proceed to Checkout</a>
                    <a href="<?php echo SITE_URL; ?>/shop" class="continue-link">&#8592; Continue Shopping</a>
                </div>
                <div class="sidebar-trust">
                    <strong>Note:</strong> After placing your order we'll contact you with shipping costs and payment details.
                </div>
            </div>

            <?php endif; ?>

        </div><!-- .cart-wrap -->
        </form>

        <!-- Separate form for coupon so it submits independently -->
        <form id="coupon-form" method="post" action="" style="display:none;">
            <input type="hidden" name="action" value="apply_coupon">
        </form>
    </main>

    <?php require INCLUDES_PATH . '/footer.php'; ?>

</div><!-- #wrapper -->

<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

/* ± qty buttons */
function adjustQty(btn, delta) {
    var input = btn.parentElement.querySelector('.qty-input');
    var val = parseInt(input.value, 10) || 1;
    val = Math.max(1, val + delta);
    input.value = val;
    /* live update row total */
    var row  = btn.closest('tr');
    var priceText = row.querySelector('.price-cell').textContent.replace(/[^0-9.]/g, '');
    var price = parseFloat(priceText) || 0;
    var totalCell = row.querySelector('.total-cell');
    if (totalCell) totalCell.textContent = '$' + (price * val).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/* coupon form: attach the coupon_code value before submit */
(function() {
    var couponForm = document.getElementById('coupon-form');
    var couponInput = document.querySelector('.coupon-input');
    if (couponForm && couponInput) {
        couponForm.addEventListener('submit', function() {
            var hidden = couponForm.querySelector('[name="coupon_code"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'coupon_code';
                couponForm.appendChild(hidden);
            }
            hidden.value = couponInput.value;
        });
    }
})();
</script>
</body>
</html>
