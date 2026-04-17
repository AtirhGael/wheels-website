<?php
/**
 * Terms and Conditions Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'terms-conditions';
$page_title = "Terms and Conditions - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Terms and conditions for purchasing BBS wheels at Elite BBS Rims. Review our policies on orders, payments, shipping, and liability.">
    <link rel="canonical" href="https://www.elitebbswheelsus.shop/terms-conditions">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="https://www.elitebbswheelsus.shop/terms-conditions">
    <meta property="og:site_name"   content="Elite BBS Rims">
    <meta property="og:title"       content="Terms &amp; Conditions — Elite BBS Rims">
    <meta property="og:description" content="Review the terms and conditions for purchasing BBS wheels at Elite BBS Rims, including order policies, shipping terms, and liability.">
    <meta property="og:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">
    <meta property="og:locale"      content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="Terms &amp; Conditions — Elite BBS Rims">
    <meta name="twitter:description" content="Order policies, shipping terms, and liability for Elite BBS Rims purchases.">
    <meta name="twitter:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">

    <!-- JSON-LD: BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home",               "item": "https://www.elitebbswheelsus.shop/"},
        {"@type": "ListItem", "position": 2, "name": "Terms & Conditions", "item": "https://www.elitebbswheelsus.shop/terms-conditions"}
      ]
    }
    </script>

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
        .last-updated { color: #777; font-style: italic; margin-bottom: 35px; font-size: 15px; }
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

    <div class="page-header"><h1>Terms and Conditions</h1></div>
    <main>
        <div class="content-page">
            <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>
            
            <h2>1. Agreement to Terms</h2>
            <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by these terms, please do not use this site.</p>
            
            <h2>2. Product Information</h2>
            <p>We strive to provide accurate product descriptions and images. However, we cannot guarantee that all product information is completely accurate, current, or error-free. If a product is not as described, please contact us.</p>
            
            <h2>3. Pricing</h2>
            <p>All prices are subject to change without notice. We reserve the right to modify prices at any time. Prices do not include shipping and handling unless otherwise stated.</p>
            
            <h2>4. Orders and Payment</h2>
            <p>By placing an order, you agree to provide accurate payment information. Orders are subject to availability. We reserve the right to refuse or cancel any order for any reason.</p>
            
            <h2>5. Shipping</h2>
            <p>Shipping times are estimates and not guarantees. We are not responsible for delays caused by shipping carriers. Risk of loss passes to you upon delivery to the carrier.</p>
            
            <h2>6. Returns and Refunds</h2>
            <p>Please refer to our Refund and Returns Policy for detailed information on returns and refunds.</p>
            
            <h2>7. Intellectual Property</h2>
            <p>All content on this website is the property of Elite BBS Rims and may not be reproduced, distributed, or modified without permission.</p>
            
            <h2>8. Limitation of Liability</h2>
            <p>We shall not be liable for any indirect, incidental, or consequential damages arising from your use of this website or purchase of products.</p>
            
            <h2>9. Privacy Policy</h2>
            <p>We respect your privacy. Any information you provide to us is kept confidential and will not be sold or shared with third parties, except as necessary to process your order.</p>
            
            <h2>10. Governing Law</h2>
            <p>These terms shall be governed by and construed in accordance with the laws of the United States.</p>
            
            <h2>11. Contact Information</h2>
            <p>If you have any questions about these Terms and Conditions, please contact us at info@elitebbswheels.store</p>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>