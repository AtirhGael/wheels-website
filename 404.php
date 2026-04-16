<?php
/**
 * 404 Error Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = '404';
$page_title = "Page Not Found - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Page not found - Elite BBS Rims">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/404">
    
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <style>
        .error-page { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh; text-align: center; padding: 40px 20px; }
        .error-code { font-size: 180px; font-weight: 800; color: #008cb2; line-height: 1; font-family: 'Montserrat', sans-serif; text-shadow: 3px 3px 0 #e0e0e0; }
        .error-title { font-size: 36px; color: #1a1a1a; margin: 20px 0 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .error-message { font-size: 18px; color: #555; max-width: 500px; line-height: 1.7; margin-bottom: 40px; }
        .error-search { width: 100%; max-width: 400px; margin-bottom: 30px; }
        .error-search form { display: flex; gap: 10px; }
        .error-search input { flex: 1; padding: 14px 20px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; }
        .error-search input:focus { outline: none; border-color: #008cb2; }
        .error-search button { padding: 14px 30px; background: #008cb2; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.3s; }
        .error-search button:hover { background: #006f8f; }
        .error-links { display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 20px; }
        .error-links a { padding: 12px 25px; background: #fff; color: #333; border: 2px solid #ddd; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; }
        .error-links a:hover { background: #008cb2; color: #fff; border-color: #008cb2; }
        .error-cta { margin-top: 50px; padding: 40px; background: linear-gradient(135deg, #f0f7ff 0%, #e6f4ff 100%); border-radius: 16px; text-align: center; }
        .error-cta h2 { color: #008cb2; font-size: 28px; margin: 0 0 15px; }
        .error-cta p { color: #555; margin-bottom: 25px; font-size: 17px; }
        .error-cta a { display: inline-block; background: #008cb2; color: #fff; padding: 14px 35px; border-radius: 8px; text-decoration: none; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .error-cta a:hover { background: #006f8f; }

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

        @media (max-width: 768px) {
            .error-code { font-size: 100px; }
            .error-title { font-size: 28px; }
            .error-search form { flex-direction: column; }
        }
    </style>
</head>
<body class="page wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">
    <?php require INCLUDES_PATH . '/header.php'; ?>

    <main>
        <div class="error-page">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-message">Oops! The page you're looking for doesn't exist or has been moved. Try searching below or explore our main pages.</p>
            
            <div class="error-search">
                <form action="<?php echo SITE_URL; ?>/shop" method="get">
                    <input type="search" name="search" placeholder="Search for BBS wheels...">
                    <button type="submit">Search</button>
                </form>
            </div>
            
            <div class="error-links">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <a href="<?php echo SITE_URL; ?>/shop">Shop</a>
                <a href="<?php echo SITE_URL; ?>/blog">Blog</a>
                <a href="<?php echo SITE_URL; ?>/contact">Contact</a>
            </div>
            
            <div class="error-cta">
                <h2>Looking for BBS Wheels?</h2>
                <p>Browse our premium collection of genuine BBS wheels</p>
                <a href="<?php echo SITE_URL; ?>/shop">Shop Now</a>
            </div>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>