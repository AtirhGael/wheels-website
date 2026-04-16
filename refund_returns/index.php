<?php
/**
 * Refund Returns Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'refund_returns';
$page_title = "Refund and Returns Policy - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Elite BBS Rims refund and returns policy.">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
                                                                                        .page-header { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/bbss-800x800.png') center/cover; padding: 80px 20px; text-align: center; color: #fff; }
        .page-header h1 { font-size: 48px; margin: 0; font-family: 'Montserrat', sans-serif; text-transform: uppercase; }
        .content-page { max-width: 900px; margin: 60px auto; padding: 0 20px; font-family: 'Lato', sans-serif; }
        .content-page p { font-size: 17px; line-height: 1.85; color: #444; margin-bottom: 18px; }
        .content-page h2 { color: #1a1a1a; font-size: 26px; margin: 45px 0 22px; font-family: 'Montserrat', sans-serif; font-weight: 700; letter-spacing: 0.5px; }
        .content-page h3 { color: #333; font-size: 20px; margin: 30px 0 15px; font-family: 'Montserrat', sans-serif; font-weight: 600; }
        .content-page ul, .content-page ol { margin: 15px 0 20px 25px; }
        .content-page li { margin-bottom: 12px; color: #444; line-height: 1.7; font-size: 16px; }
        .policy-box { background: #fff; padding: 28px 30px; border-radius: 12px; margin: 35px 0; border-left: 5px solid #008cb2; box-shadow: 0 3px 15px rgba(0,0,0,0.07); border: 1px solid #e8e8e8; }
                                                                @media (max-width: 768px) { .page-header h1 { font-size: 32px; } }

        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }

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
    </style>
</head>
<body class="page wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>

    <div class="page-header"><h1>Refund and Returns Policy</h1></div>
    <main>
        <div class="content-page">
            <p>We want you to be completely satisfied with your purchase. Please read our policy carefully.</p>
            
            <div class="policy-box">
                <h3>30-Day Return Window</h3>
                <p>You may return any item within 30 days of delivery for a full refund or exchange. Items must be unused, in the original packaging, and in the same condition as when you received them.</p>
            </div>
            
            <h2>Eligibility for Returns</h2>
            <ul>
                <li>Item must be unused and in original packaging</li>
                <li>All tags and labels must be attached</li>
                <li>Customer is responsible for return shipping costs</li>
                <li>Items that have been installed or mounted are not returnable</li>
                <li>Wheels must not have any scratches or damage</li>
            </ul>
            
            <h2>How to Initiate a Return</h2>
            <ol>
                <li>Contact us at info@elitebbswheelsus.shop to request a Return Authorization</li>
                <li>Pack the item securely in original packaging</li>
                <li>Ship the item to the address provided in the return authorization</li>
                <li>Provide tracking number for your shipment</li>
                <li>Wait for confirmation of receipt and inspection</li>
            </ol>
            
            <h2>Refunds</h2>
            <p>Once we receive and inspect your return, we will process your refund within 5-7 business days. The refund will be credited to your original payment method. Shipping costs are non-refundable unless the return is due to our error.</p>
            
            <h2>Exchanges</h2>
            <p>If you need a different size or finish, we can exchange items. Contact us to discuss your options. Price differences may apply.</p>
            
            <h2>Damaged or Defective Items</h2>
            <p>If you receive a damaged or defective item, please contact us immediately with photos of the damage. We will arrange for a replacement or full refund at no cost to you.</p>
            
            <h2>Non-Returnable Items</h2>
            <ul>
                <li>Custom-ordered or special-order items</li>
                <li>Items that have been installed</li>
                <li>Items without original packaging</li>
                <li>Items damaged due to improper installation</li>
            </ul>
            
            <h2>Restocking Fee</h2>
            <p>A 15% restocking fee may apply to returns that are not due to our error. This covers the cost of inspection and repackaging.</p>
            
            <h2>Contact Us</h2>
            <p>Questions about returns? Contact us at info@elitebbswheelsus.shop</p>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>