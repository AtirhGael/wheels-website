<?php
/**
 * About Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'about';
$page_title = "About Us - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Elite BBS Rims is a premium wheel boutique in Katy, TX specializing in authentic BBS forged wheels. Discover our story and commitment to genuine BBS rims.">
    <link rel="canonical" href="https://www.elitebbswheelsus.shop/about">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="https://www.elitebbswheelsus.shop/about">
    <meta property="og:site_name"   content="Elite BBS Rims">
    <meta property="og:title"       content="About Elite BBS Rims — Authentic BBS Wheel Specialists, Katy TX">
    <meta property="og:description" content="Elite BBS Rims is a premium wheel boutique in Katy, TX specializing in authentic BBS forged wheels and rims with decades of German motorsport heritage.">
    <meta property="og:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">
    <meta property="og:locale"      content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="About Elite BBS Rims — Authentic BBS Wheel Specialists">
    <meta name="twitter:description" content="Premium wheel boutique in Katy, TX. Authentic BBS forged wheels with German motorsport heritage.">
    <meta name="twitter:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">

    <!-- JSON-LD: BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home",     "item": "https://www.elitebbswheelsus.shop/"},
        {"@type": "ListItem", "position": 2, "name": "About Us", "item": "https://www.elitebbswheelsus.shop/about"}
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
                
                
                
                
                
                
                
                
                
                
        .page-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/bbss-800x800.png') center/cover;
            padding: 80px 20px;
            text-align: center;
            color: #fff;
        }
        
        .page-header h1 {
            font-size: 48px;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
        }
        
        .content-page {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
            font-family: 'Lato', sans-serif;
        }

        .content-page p {
            font-size: 17px;
            line-height: 1.85;
            margin-bottom: 22px;
            color: #444;
        }

        .content-page h2 {
            color: #1a1a1a;
            font-size: 30px;
            margin: 50px 0 25px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .content-page h3 {
            color: #1a1a1a;
            font-size: 22px;
            margin: 35px 0 18px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }

        .content-page ul {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }

        .content-page li {
            padding: 14px 0 14px 38px;
            position: relative;
            color: #444;
            line-height: 1.7;
            font-size: 16px;
        }
        
        .content-page li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #008cb2;
            font-weight: 700;
            font-size: 18px;
        }
        
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 40px 0;
        }
        
        .about-image {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .cta-section {
            background: #f9f9f9;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-section h2 {
            margin-top: 0;
        }
        
        .cta-btn {
            display: inline-block;
            padding: 15px 40px;
            background: #008cb2;
            color: #fff;
            text-decoration: none;
            border-radius: 99px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .cta-btn:hover {
            background: #006f8f;
        }
        
                
                
                
                
                
                
                
                
        @media (max-width: 768px) {
            .about-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header h1 {
                font-size: 32px;
            }
        }

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


    <div class="page-header">
        <h1>About Us</h1>
    </div>

    <main>
        <div class="content-page">
            <h2>Elite BBS Rims - Timeless Performance. German Precision. Pure Icon.</h2>
            
            <p>For over five decades, <strong>BBS Wheels</strong> has been the benchmark of excellence — forged in the fires of motorsport and perfected for the world's most demanding drivers. From legendary three-piece forged classics to modern flow-formed masterpieces, every BBS rim combines race-proven technology, featherweight construction, and unmistakable design elegance.</p>
            
            <p>At <strong>Elite BBS Rims</strong>, we bring you hand-selected, authentic BBS wheels — the same name trusted by champions, supercar builders, and discerning enthusiasts across the globe.</p>
            
            <div class="about-grid">
                <div class="about-content">
                    <h3>Why Choose Us?</h3>
                    <ul>
                        <li><strong>Authentic Products</strong> - Every wheel we sell is 100% genuine BBS and premium brands</li>
                        <li><strong>Expert Knowledge</strong> - We understand fitment, offsets, and vehicle compatibility</li>
                        <li><strong>Quality Assurance</strong> - Each wheel inspected before shipping</li>
                        <li><strong>Customer Support</strong> - Pre and post-sale support from enthusiasts</li>
                        <li><strong>Competitive Pricing</strong> - Best prices on authentic BBS wheels in the USA</li>
                        <li><strong>Fast Shipping</strong> - Quick delivery across the United States</li>
                    </ul>
                </div>
                <div class="about-image">
                    <img src="<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/bbss-800x800.png" alt="BBS Wheels" style="width:100%;max-width:500px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                </div>
            </div>
            
            <h2>Our Commitment</h2>
            
            <p>We are passionate about automotive excellence. Every set we offer is hand-selected for perfect fitment, superior craftsmanship, and the soul of true enthusiasts. Whether you're elevating a classic Porsche, dominating in a modern BMW M, or building the ultimate street sleeper, Elite BBS Rims delivers the gold standard—uncompromising, authentic, and eternally dominant.</p>
            
            <p>Our team has decades of combined experience in the automotive wheel industry. We don't just sell wheels — we understand them. From the engineering behind flow-forming technology to the nuances of offset and bolt pattern compatibility, we're here to help you find the perfect wheels for your vehicle.</p>
            
            <div class="cta-section">
                <h2>Ready to Upgrade Your Ride?</h2>
                <p>Browse our selection of premium wheels or contact us for personalized fitment advice.</p>
                <a href="<?php echo SITE_URL; ?>/shop" class="cta-btn">Shop Now</a>
            </div>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>