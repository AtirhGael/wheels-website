<?php
/**
 * FAQ Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'faq';
$page_title = "FAQ - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Frequently asked questions about BBS wheels, fitment, shipping, and returns.">
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
        .faq-item { background: #fff; border-radius: 12px; padding: 28px 30px; margin-bottom: 22px; border-left: 5px solid #008cb2; box-shadow: 0 2px 14px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; transition: box-shadow 0.3s ease; }
        .faq-item:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .faq-item h3 { color: #1a1a1a; margin: 0 0 18px; font-size: 20px; font-family: 'Montserrat', sans-serif; font-weight: 600; }
        .faq-item p { line-height: 1.85; color: #444; margin: 0; font-size: 16px; }
        .faq-item ul { margin: 12px 0 0 22px; }
        .faq-item li { margin-bottom: 10px; color: #444; line-height: 1.7; }
        .contact-link { text-align: center; margin-top: 45px; padding: 28px; background: linear-gradient(135deg, #f0f7ff 0%, #e6f4ff 100%); border-radius: 12px; box-shadow: 0 2px 12px rgba(0,140,178,0.1); }
        .contact-link a { color: #008cb2; font-weight: 700; font-size: 17px; }
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

    <div class="page-header"><h1>Frequently Asked Questions</h1></div>
    <main>
        <div class="content-page">
            <div class="faq-item">
                <h3>Are your BBS wheels authentic?</h3>
                <p>Yes! We only sell 100% genuine BBS wheels and other premium brands. Each wheel comes with manufacturer authentication. We source directly from authorized dealers to ensure authenticity.</p>
            </div>
            <div class="faq-item">
                <h3>How do I know if a wheel will fit my car?</h3>
                <p>We offer expert fitment advice. Please provide your vehicle's make, model, and year when ordering, or contact us before purchase. We can help you determine the correct size, offset, and bolt pattern.</p>
            </div>
            <div class="faq-item">
                <h3>What payment methods do you accept?</h3>
                <p>We accept major credit cards and bank transfers. For orders, we can arrange payment plans for high-value purchases.</p>
            </div>
            <div class="faq-item">
                <h3>Do you offer international shipping?</h3>
                <p>Yes, we ship worldwide. Shipping costs vary by location. Contact us for a shipping quote to your country.</p>
            </div>
            <div class="faq-item">
                <h3>What is your return policy?</h3>
                <p>We want you to be completely satisfied with your purchase. Please see our Refund and Returns Policy for detailed information. We offer a 30-day return window for unused items in original packaging.</p>
            </div>
            <div class="faq-item">
                <h3>How long does shipping take?</h3>
                <p>Shipping times vary based on wheel availability and your location. Typically, in-stock wheels ship within 3-5 business days. Custom orders may take 2-4 weeks.</p>
            </div>
            <div class="faq-item">
                <h3>Do you offer wheel mounting and balancing?</h3>
                <p>We can recommend local authorized installers. Please contact us for recommendations in your area.</p>
            </div>
            <div class="faq-item">
                <h3>What warranty do you offer?</h3>
                <p>All BBS wheels come with the manufacturer's warranty. We also provide a 30-day inspection period upon delivery to ensure your complete satisfaction.</p>
            </div>
            <div class="faq-item">
                <h3>Can I cancel or modify my order?</h3>
                <p>Orders can be cancelled or modified within 24 hours of placement, provided they haven't been shipped. Contact us immediately to make changes.</p>
            </div>
            <div class="faq-item">
                <h3>How do I track my order?</h3>
                <p>Once your order ships, you'll receive a tracking number via email. You can also contact us for order status updates.</p>
            </div>
            <div class="contact-link">
                <p>Can't find the answer you're looking for? <a href="<?php echo SITE_URL; ?>/contact">Contact us</a>!</p>
            </div>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>