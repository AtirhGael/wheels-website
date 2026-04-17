<?php
/**
 * Checkout Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/cart_functions.php';

$page          = 'checkout';
$page_title    = "Checkout - " . SITE_NAME;
$cart_is_empty = cart_is_empty();
$error         = '';

/* ── Payment methods from admin settings ── */
$pay_btc_on  = (bool) get_site_setting('payment_bitcoin_enabled', '0');
$pay_bank_on = (bool) get_site_setting('payment_bank_enabled',    '0');
$pay_pp_on   = (bool) get_site_setting('payment_paypal_enabled',  '0');
$enabled_methods = [];
if ($pay_btc_on)  $enabled_methods[] = 'bitcoin';
if ($pay_bank_on) $enabled_methods[] = 'bank';
if ($pay_pp_on)   $enabled_methods[] = 'paypal';

/* ── Coupon discount from cart session ── */
$subtotal      = cart_total();
$active_coupon = $_SESSION['coupon'] ?? null;
$discount      = 0;
if ($active_coupon) {
    $discount = $active_coupon['type'] === 'percent'
        ? $subtotal * ($active_coupon['value'] / 100)
        : min($active_coupon['value'], $subtotal);
}
$after_coupon = max(0, $subtotal - $discount);

/* ── Bitcoin discount (applied on POST or for sidebar preview) ── */
$chosen_method   = $_POST['payment_method'] ?? ($enabled_methods[0] ?? 'email_transfer');
$btc_discount    = ($chosen_method === 'bitcoin') ? round($after_coupon * 0.10, 2) : 0;
$grand_total     = max(0, $after_coupon - $btc_discount);

/* ── Order submission ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {

    // Recalculate with the posted payment method
    $chosen_method = sanitize($_POST['payment_method'] ?? 'email_transfer');
    $btc_discount  = ($chosen_method === 'bitcoin') ? round($after_coupon * 0.10, 2) : 0;
    $grand_total   = max(0, $after_coupon - $btc_discount);

    $required = ['customer_name', 'customer_email', 'customer_phone'];
    $missing  = [];
    foreach ($required as $f) {
        if (empty(trim($_POST[$f] ?? ''))) $missing[] = str_replace('_', ' ', $f);
    }

    if (!empty($missing)) {
        $error = 'Please fill in all required fields: ' . implode(', ', $missing);
    } elseif (!filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $order_number = generate_order_number();
        $items        = cart_format_for_order();

        $order_data = [
            'order_number'    => $order_number,
            'customer_name'   => sanitize($_POST['customer_name']),
            'customer_email'  => sanitize($_POST['customer_email']),
            'customer_phone'  => sanitize($_POST['customer_phone']),
            'billing_address' => sanitize($_POST['billing_address']  ?? ''),
            'shipping_address'=> sanitize($_POST['shipping_address'] ?? ''),
            'vehicle_make'    => '',
            'vehicle_model'   => '',
            'vehicle_year'    => '',
            'notes'           => sanitize($_POST['notes']            ?? ''),
            'items_json'      => json_encode($items),
            'subtotal'        => $subtotal,
            'shipping_cost'   => 0,
            'total'           => $grand_total,
            'payment_method'  => $chosen_method,
            'status'          => 'pending',
        ];

        try {
            $sql = "INSERT INTO orders (
                        order_number, customer_name, customer_email, customer_phone,
                        billing_address, shipping_address, vehicle_make, vehicle_model, vehicle_year,
                        notes, items_json, subtotal, shipping_cost, total, payment_method, status
                    ) VALUES (
                        :order_number, :customer_name, :customer_email, :customer_phone,
                        :billing_address, :shipping_address, :vehicle_make, :vehicle_model, :vehicle_year,
                        :notes, :items_json, :subtotal, :shipping_cost, :total, :payment_method, :status
                    )";
            get_db()->prepare($sql)->execute($order_data);

            send_order_email($order_number, $_POST, $items, $grand_total, $discount, $active_coupon, $btc_discount, $chosen_method);

            cart_clear();
            unset($_SESSION['coupon']);

            header("Location: " . site_url('checkout/success?order=' . $order_number . '&method=' . urlencode($chosen_method)));
            exit;

        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again or contact us directly.';
            error_log("Order Error: " . $e->getMessage());
        }
    }
}

function send_order_email($order_number, $customer, $items, $total, $discount = 0, $coupon = null, $btc_discount = 0, $payment_method = '') {
    require_once INCLUDES_PATH . '/mailer.php';
    return send_order_notification($order_number, $customer, $items, $total, $discount, $coupon, $btc_discount, $payment_method);
}

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

        /* ── Page hero ── */
        .co-hero {
            background: linear-gradient(135deg, #0d0f13 0%, #1a1f2e 100%);
            padding: 36px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .co-hero-inner { max-width: 1200px; margin: 0 auto; }
        .co-breadcrumb {
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 10px;
        }
        .co-breadcrumb a { color: #008cb2; text-decoration: none; }
        .co-breadcrumb a:hover { color: #fff; }
        .co-breadcrumb .sep { margin: 0 8px; color: rgba(255,255,255,0.2); }
        .co-hero h1 {
            font-family: 'Barlow', sans-serif;
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 900;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        /* ── Progress steps ── */
        .co-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-top: 24px;
        }
        .co-step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }
        .co-step.active { color: #fff; }
        .co-step.done   { color: #008cb2; }
        .co-step-num {
            width: 26px; height: 26px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 900;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
        }
        .co-step.active .co-step-num { background: #008cb2; border-color: #008cb2; color: #fff; }
        .co-step.done   .co-step-num { background: transparent; border-color: #008cb2; color: #008cb2; }
        .co-step-line {
            width: 48px; height: 1px;
            background: rgba(255,255,255,0.12);
            margin: 0 4px;
        }

        /* ── Layout ── */
        .co-wrap {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 32px;
            align-items: start;
        }

        /* ── Alert ── */
        .co-alert {
            grid-column: 1 / -1;
            padding: 14px 20px;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 600;
        }
        .co-alert.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }

        /* ── Form card ── */
        .co-form-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
        }
        .co-section {
            padding: 28px 28px 0;
        }
        .co-section:last-of-type { padding-bottom: 28px; }
        .co-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f2f5;
        }
        .co-section-title span.icon {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
        }
        .co-divider { height: 1px; background: #f0f2f5; margin: 0 28px; }

        /* Form rows */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #777;
            margin-bottom: 7px;
        }
        .form-group label .req { color: #e53e3e; margin-left: 2px; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #dde1e7;
            border-radius: 8px;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            color: #1a1a1a;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #008cb2;
            box-shadow: 0 0 0 3px rgba(0,140,178,0.1);
        }
        .form-group textarea { height: 90px; resize: vertical; }
        .form-group select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }

        .field-hint { font-size: 12px; color: #999; margin-top: 4px; font-family: 'Lato', sans-serif; }

        /* ── Submit button ── */
        .co-submit-wrap {
            padding: 20px 28px 28px;
            background: #f8f9fb;
            border-top: 1px solid #eef0f3;
        }
        .co-submit-btn {
            width: 100%;
            padding: 17px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s;
        }
        .co-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,140,178,0.35); }
        .co-submit-note {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 10px;
            font-family: 'Lato', sans-serif;
        }

        /* ── Order summary sidebar ── */
        .co-sidebar {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
            position: sticky;
            top: 100px;
        }
        .co-sidebar-header {
            background: #1a1a1a;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .co-sidebar-header h2 {
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
        }
        .co-sidebar-header a {
            font-family: 'Barlow', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #008cb2;
            text-decoration: none;
        }
        .co-sidebar-header a:hover { color: #00b4e0; }

        .co-items { padding: 16px 24px; }
        .co-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f2f5;
        }
        .co-item:last-child { border-bottom: none; }
        .co-item-img {
            width: 52px; height: 52px;
            object-fit: cover;
            border-radius: 6px;
            background: #f5f5f5;
            flex-shrink: 0;
            border: 1px solid #eee;
        }
        .co-item-name {
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.25;
            flex: 1;
        }
        .co-item-qty { font-size: 11px; color: #aaa; margin-top: 2px; }
        .co-item-price {
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            font-weight: 800;
            color: #1a1a1a;
            flex-shrink: 0;
        }

        .co-totals { padding: 0 24px 8px; border-top: 1px solid #f0f2f5; }
        .co-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }
        .co-total-row:last-child { border-bottom: none; }
        .co-total-row .lbl { font-family: 'Barlow', sans-serif; font-weight: 600; color: #666; }
        .co-total-row .val { font-family: 'Barlow', sans-serif; font-weight: 700; color: #1a1a1a; }
        .co-total-row.discount .val { color: #16a34a; }
        .co-total-row.grand {
            padding: 14px 0 4px;
            border-top: 2px solid #1a1a1a;
            border-bottom: none;
            margin-top: 4px;
        }
        .co-total-row.grand .lbl { font-size: 14px; font-weight: 900; color: #1a1a1a; text-transform: uppercase; letter-spacing: 1px; }
        .co-total-row.grand .val { font-size: 22px; font-weight: 900; color: #008cb2; }

        .co-sidebar-note {
            padding: 14px 24px 20px;
            font-size: 12px;
            color: #999;
            line-height: 1.6;
            border-top: 1px solid #f0f2f5;
        }

        /* ── Empty cart ── */
        .co-empty {
            grid-column: 1 / -1;
            background: #fff;
            border-radius: 12px;
            text-align: center;
            padding: 80px 40px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border: 1px solid #e8ecf0;
        }
        .co-empty-icon { font-size: 52px; opacity: 0.2; margin-bottom: 20px; }
        .co-empty h2 { font-family: 'Barlow', sans-serif; font-size: 22px; font-weight: 900; color: #1a1a1a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .co-empty p { color: #888; margin-bottom: 28px; }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff; padding: 14px 36px; border-radius: 8px;
            font-family: 'Barlow', sans-serif; font-size: 13px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase; text-decoration: none;
            transition: all 0.25s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,140,178,0.35); color: #fff; }

        /* ── Payment method cards ── */
        .pay-methods { display: flex; flex-direction: column; gap: 10px; }
        .pay-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border: 2px solid #e5e9ee;
            border-radius: 10px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
            background: #fff;
        }
        .pay-card input[type="radio"] { display: none; }
        .pay-card:hover { border-color: #b3d9e8; background: #f8fbfc; }
        .pay-card.selected { border-color: #008cb2; background: #f0f9fc; }
        .pay-card-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 900; color: #fff;
            flex-shrink: 0;
            background: #008cb2;
        }
        .pay-card[data-method="bitcoin"]  .pay-card-icon { background: #f7931a; }
        .pay-card[data-method="bank"]     .pay-card-icon { background: #1a6b3e; }
        .pay-card[data-method="paypal"]   .pay-card-icon { background: #003087; }
        .pay-card-body { flex: 1; display: flex; flex-direction: column; gap: 2px; }
        .pay-card-name { font-family: 'Barlow', sans-serif; font-size: 14px; font-weight: 800; color: #1a1a1a; letter-spacing: 0.3px; }
        .pay-card-desc { font-size: 12px; color: #888; font-family: 'Lato', sans-serif; }
        .pay-badge-btc {
            display: inline-block;
            background: #e8f8f0; color: #1a6b3e;
            font-size: 10px; font-weight: 800;
            padding: 1px 7px; border-radius: 10px;
            margin-left: 6px; letter-spacing: 0.5px;
        }
        .pay-card-check {
            width: 22px; height: 22px;
            border-radius: 50%;
            border: 2px solid #dde1e7;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 900; color: transparent;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .pay-card.selected .pay-card-check {
            border-color: #008cb2;
            background: #008cb2;
            color: #fff;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .co-wrap { grid-template-columns: 1fr; }
            .co-sidebar { position: static; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="woocommerce-checkout wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">

    <?php require INCLUDES_PATH . '/header.php'; ?>

    <!-- Page hero -->
    <div class="co-hero">
        <div class="co-hero-inner">
            <div class="co-breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="sep">›</span>
                <a href="<?php echo SITE_URL; ?>/cart">Cart</a>
                <span class="sep">›</span>
                <span>Checkout</span>
            </div>
            <h1>Checkout</h1>

            <div class="co-steps">
                <div class="co-step done">
                    <span class="co-step-num">&#10003;</span>
                    <span>Cart</span>
                </div>
                <div class="co-step-line"></div>
                <div class="co-step active">
                    <span class="co-step-num">2</span>
                    <span>Details</span>
                </div>
                <div class="co-step-line"></div>
                <div class="co-step">
                    <span class="co-step-num">3</span>
                    <span>Confirm</span>
                </div>
            </div>
        </div>
    </div>

    <main>
        <div class="co-wrap">

            <?php if ($error): ?>
            <div class="co-alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($cart_is_empty): ?>

            <div class="co-empty">
                <div class="co-empty-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Add some wheels before checking out.</p>
                <a href="<?php echo SITE_URL; ?>/shop" class="btn-primary">Browse Wheels</a>
            </div>

            <?php else: ?>

            <!-- Left: form -->
            <form method="POST" action="" id="checkout-form">
                <div class="co-form-card">

                    <!-- Contact -->
                    <div class="co-section">
                        <div class="co-section-title">
                            <span class="icon">&#9993;</span>
                            Contact Details
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name <span class="req">*</span></label>
                                <input type="text" name="customer_name" required
                                       placeholder="John Smith"
                                       value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Address <span class="req">*</span></label>
                                <input type="email" name="customer_email" required
                                       placeholder="john@example.com"
                                       value="<?php echo htmlspecialchars($_POST['customer_email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group" style="max-width:340px;">
                            <label>Phone Number <span class="req">*</span></label>
                            <input type="tel" name="customer_phone" required
                                   placeholder="+1 (555) 000-0000"
                                   value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="co-divider"></div>

                    <!-- Address -->
                    <div class="co-section">
                        <div class="co-section-title">
                            <span class="icon">&#8982;</span>
                            Shipping &amp; Billing
                        </div>
                        <div class="form-group">
                            <label>Billing Address</label>
                            <textarea name="billing_address" placeholder="Street, City, State, ZIP"><?php echo htmlspecialchars($_POST['billing_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Shipping Address <span style="font-weight:500;text-transform:none;letter-spacing:0;color:#bbb;">(if different)</span></label>
                            <textarea name="shipping_address" placeholder="Leave blank if same as billing"><?php echo htmlspecialchars($_POST['shipping_address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <?php if (!empty($enabled_methods)): ?>
                    <div class="co-divider"></div>

                    <!-- Payment Method -->
                    <div class="co-section">
                        <div class="co-section-title">
                            <span class="icon">&#9830;</span>
                            Payment Method
                        </div>
                        <div class="pay-methods">
                            <?php foreach ($enabled_methods as $method):
                                $is_checked = ($chosen_method === $method);
                            ?>
                            <label class="pay-card <?php echo $is_checked ? 'selected' : ''; ?>" data-method="<?php echo $method; ?>">
                                <input type="radio" name="payment_method" value="<?php echo $method; ?>"
                                       <?php echo $is_checked ? 'checked' : ''; ?>>
                                <span class="pay-card-icon">
                                    <?php if ($method === 'bitcoin'): ?>&#8383;
                                    <?php elseif ($method === 'bank'): ?>&#127981;
                                    <?php else: ?>P<?php endif; ?>
                                </span>
                                <span class="pay-card-body">
                                    <?php if ($method === 'bitcoin'): ?>
                                        <span class="pay-card-name">Bitcoin</span>
                                        <span class="pay-card-desc">Pay with BTC <span class="pay-badge-btc">Save 10%</span></span>
                                    <?php elseif ($method === 'bank'): ?>
                                        <span class="pay-card-name">Bank Transfer</span>
                                        <span class="pay-card-desc">Direct bank deposit</span>
                                    <?php else: ?>
                                        <span class="pay-card-name">PayPal</span>
                                        <span class="pay-card-desc">Pay via PayPal</span>
                                    <?php endif; ?>
                                </span>
                                <span class="pay-card-check">&#10003;</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="payment_method" value="email_transfer">
                    <?php endif; ?>

                    <div class="co-divider"></div>

                    <!-- Notes -->
                    <div class="co-section">
                        <div class="co-section-title">
                            <span class="icon">&#9998;</span>
                            Order Notes
                        </div>
                        <div class="form-group">
                            <textarea name="notes" placeholder="Any special requests, questions, or fitment concerns..."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                        </div>
                    </div>

                </div><!-- .co-form-card -->

                <div class="co-submit-wrap" style="background:transparent;border:none;padding:20px 0 0;">
                    <button type="submit" name="submit_order" class="co-submit-btn">Place Order</button>
                    <p class="co-submit-note">By placing your order you agree to our <a href="<?php echo SITE_URL; ?>/terms-conditions" style="color:#008cb2;">Terms &amp; Conditions</a>. We'll contact you within 24 hours.</p>
                </div>
            </form>

            <!-- Right: order summary -->
            <div class="co-sidebar">
                <div class="co-sidebar-header">
                    <h2>Order Summary</h2>
                    <a href="<?php echo SITE_URL; ?>/cart">Edit Cart</a>
                </div>

                <div class="co-items">
                    <?php foreach (cart_items() as $item):
                        $img = $item['image'] ?: asset_url('images/placeholder.png');
                    ?>
                    <div class="co-item">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="co-item-img">
                        <div style="flex:1;min-width:0;">
                            <div class="co-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="co-item-qty">Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="co-item-price"><?php echo format_price($item['price'] * $item['quantity']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="co-totals">
                    <div class="co-total-row">
                        <span class="lbl">Subtotal</span>
                        <span class="val"><?php echo format_price($subtotal); ?></span>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="co-total-row discount">
                        <span class="lbl">Coupon (<?php echo htmlspecialchars($active_coupon['code']); ?>)</span>
                        <span class="val">&#8722;<?php echo format_price($discount); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="co-total-row discount" id="btc-discount-row" style="<?php echo $btc_discount > 0 ? '' : 'display:none;'; ?>">
                        <span class="lbl">Bitcoin discount (10%)</span>
                        <span class="val" id="btc-discount-val">&#8722;<?php echo format_price($btc_discount); ?></span>
                    </div>
                    <div class="co-total-row">
                        <span class="lbl">Shipping</span>
                        <span class="val" style="font-size:12px;color:#aaa;">Calculated after order</span>
                    </div>
                    <div class="co-total-row grand">
                        <span class="lbl">Total</span>
                        <span class="val" id="sidebar-grand-total"><?php echo format_price($grand_total); ?></span>
                    </div>
                </div>

                <div class="co-sidebar-note">
                    After placing your order we'll contact you within 24 hours with shipping costs and payment details.
                </div>
            </div>

            <?php endif; ?>

        </div><!-- .co-wrap -->
    </main>

    <?php require INCLUDES_PATH . '/footer.php'; ?>

</div><!-- #wrapper -->

<script src="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/js/flatsomed02f.js"></script>
<script>var siteUrl = '<?php echo SITE_URL; ?>';</script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();

    /* ── Payment card interactivity ── */
    var afterCoupon = <?php echo json_encode((float)$after_coupon); ?>;

    function formatPrice(n) {
        return '$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function updateTotals(method) {
        var btcRow   = document.getElementById('btc-discount-row');
        var btcVal   = document.getElementById('btc-discount-val');
        var grandEl  = document.getElementById('sidebar-grand-total');
        if (!grandEl) return;

        var btcDiscount = 0;
        if (method === 'bitcoin') {
            btcDiscount = Math.round(afterCoupon * 0.10 * 100) / 100;
        }
        var grand = Math.max(0, afterCoupon - btcDiscount);

        if (btcRow) btcRow.style.display = btcDiscount > 0 ? '' : 'none';
        if (btcVal) btcVal.textContent = '\u2212' + formatPrice(btcDiscount);
        grandEl.textContent = formatPrice(grand);
    }

    document.querySelectorAll('.pay-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.pay-card').forEach(function(c) { c.classList.remove('selected'); });
            card.classList.add('selected');
            var radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            updateTotals(card.dataset.method);
        });
    });
});
</script>
</body>
</html>
