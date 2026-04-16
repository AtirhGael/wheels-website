<?php
/**
 * Checkout Success Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'checkout';
$page_title = "Order Confirmed - " . SITE_NAME;
$order_number = isset($_GET['order']) ? sanitize($_GET['order']) : '';

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
        body { margin: 0; }
        
        #header {
            background: rgba(10, 10, 10, 0.9);
            height: 86px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 20px;
        }
        
        .logo a {
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
        }
        
        .logo-tagline {
            font-family: 'Dancing Script', cursive;
            font-size: 14px;
            color: #fff;
            margin: 0;
        }
        
        .main-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 25px;
        }
        
        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        .main-nav a:hover { color: #4ad8ff; }
        
        .header-right a {
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-icon {
            background: #008cb2;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
        }
        
        .success-page {
            max-width: 700px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: #d4edda;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 50px;
            color: #28a745;
        }
        
        .success-page h1 {
            font-size: 36px;
            color: #28a745;
            margin-bottom: 15px;
        }
        
        .success-page .order-number {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .success-page .order-number strong {
            color: #333;
            font-size: 22px;
        }
        
        .success-message {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .success-message p {
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .success-message strong {
            color: #008cb2;
        }
        
        .next-steps {
            margin-bottom: 40px;
        }
        
        .next-steps h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .next-steps ul {
            list-style: none;
            padding: 0;
            text-align: left;
            display: inline-block;
        }
        
        .next-steps li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        
        .next-steps li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #008cb2;
            font-weight: 700;
        }
        
        .success-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            display: inline-block;
            padding: 15px 40px;
            background: #008cb2;
            color: #fff;
            text-decoration: none;
            border-radius: 99px;
            font-weight: 700;
            text-transform: uppercase;
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
            background: #006f8f;
        }
        
        .btn-outline {
            display: inline-block;
            padding: 15px 40px;
            border: 2px solid #008cb2;
            color: #008cb2;
            text-decoration: none;
            border-radius: 99px;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.3s;
        }
        
        .btn-outline:hover {
            background: #008cb2;
            color: #fff;
        }
        
        footer {
            background: #222;
            color: #999;
            padding: 60px 20px 20px;
            margin-top: 60px;
        }
        
        .footer-main {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }
        
        .footer-section h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section a {
            color: #999;
            text-decoration: none;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid #333;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="header-wrapper">
            <div class="logo">
                <a href="<?php echo SITE_URL; ?>/">ELITE BBS RIMS</a>
                <p class="logo-tagline">ELITE BBS RIMS</p>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shop">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
                </ul>
            </nav>
            
            <div class="header-right">
                <a href="<?php echo SITE_URL; ?>/cart">
                    <span>Cart</span>
                    <span class="cart-icon">0</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="success-page">
            <div class="success-icon">✓</div>
            
            <h1>Thank You!</h1>
            
            <p class="order-number">Your order has been successfully placed.</p>
            
            <div class="success-message">
                <p><strong>Order Number:</strong> <?php echo $order_number; ?></p>
                <p>We have received your order and will process it shortly. <strong>You will receive a confirmation email at your registered email address.</strong></p>
                <p>Our team will contact you within <strong>24 hours</strong> to confirm your order details, provide shipping costs, and discuss payment options.</p>
            </div>
            
            <div class="next-steps">
                <h2>What happens next?</h2>
                <ul>
                    <li>We will review your order and verify product availability</li>
                    <li>We'll contact you to confirm shipping costs</li>
                    <li>Payment options will be discussed (bank transfer, etc.)</li>
                    <li>Once payment is received, we'll ship your wheels</li>
                    <li>You'll receive tracking information via email</li>
                </ul>
            </div>
            
            <div class="success-actions">
                <a href="<?php echo SITE_URL; ?>/" class="btn-primary">Back to Home</a>
                <a href="<?php echo SITE_URL; ?>/shop" class="btn-outline">Continue Shopping</a>
            </div>
            
            <p style="margin-top: 40px; color: #666; font-size: 14px;">
                Questions? Contact us at <a href="mailto:<?php echo EMAIL_TO; ?>" style="color: #008cb2;"><?php echo EMAIL_TO; ?></a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-main">
            <div class="footer-section">
                <h3><?php echo SITE_NAME; ?></h3>
                <p>Premium BBS Wheels for the true enthusiast.</p>
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
</body>
</html>