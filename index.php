
<?php
/**
 * Main Index Page - Elite BBS Rims
 * Homepage with hero, vehicle search, featured products, and all sections
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'home';
$page_title = SITE_NAME . " - Premium BBS Wheels";
$page_description = "Shop authentic BBS wheels and forged BBS rims in the USA. Elite BBS Rims in Katy, TX offers hand-selected genuine BBS wheels with expert fitment advice and free USA shipping.";

$featured_products = get_featured_products(8);
$all_products = get_all_products();
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="canonical" href="https://www.elitebbswheelsus.shop/">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="https://www.elitebbswheelsus.shop/">
    <meta property="og:site_name"   content="Elite BBS Rims">
    <meta property="og:title"       content="Elite BBS Rims — Authentic Forged BBS Wheels USA">
    <meta property="og:description" content="Shop authentic BBS wheels and forged BBS rims in the USA. Hand-selected genuine BBS wheels with expert fitment advice and free USA shipping.">
    <meta property="og:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">
    <meta property="og:locale"      content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="Elite BBS Rims — Authentic Forged BBS Wheels USA">
    <meta name="twitter:description" content="Shop authentic BBS wheels and forged BBS rims in the USA. Hand-selected genuine BBS wheels with expert fitment advice and free USA shipping.">
    <meta name="twitter:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">

    <!-- JSON-LD: LocalBusiness + WebSite + SearchAction -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "LocalBusiness",
          "@id": "https://www.elitebbswheelsus.shop/#business",
          "name": "Elite BBS Rims",
          "url": "https://www.elitebbswheelsus.shop/",
          "logo": "https://www.elitebbswheelsus.shop/assets/images/logo.png",
          "image": "https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png",
          "description": "Premium authentic BBS wheels and forged rims retailer in Katy, TX. Hand-selected genuine BBS wheels for the USA market.",
          "telephone": "+16177082284",
          "email": "Sales@elitebbswheelsus.shop",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "20802 Highland Knolls Drive",
            "addressLocality": "Katy",
            "addressRegion": "TX",
            "postalCode": "77450",
            "addressCountry": "US"
          },
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
              "opens": "09:00",
              "closes": "17:00"
            }
          ],
          "priceRange": "$$$",
          "currenciesAccepted": "USD",
          "areaServed": "US"
        },
        {
          "@type": "WebSite",
          "@id": "https://www.elitebbswheelsus.shop/#website",
          "url": "https://www.elitebbswheelsus.shop/",
          "name": "Elite BBS Rims",
          "description": "Authentic BBS wheels and forged rims for the USA market",
          "publisher": {
            "@id": "https://www.elitebbswheelsus.shop/#business"
          },
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "https://www.elitebbswheelsus.shop/shop?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "https://www.elitebbswheelsus.shop/"
            }
          ]
        }
      ]
    }
    </script>

    <link rel='dns-prefetch' href='https://fonts.googleapis.com'>
    <link rel='dns-prefetch' href='https://fonts.gstatic.com'>
    <link rel='dns-prefetch' href='https://code.jquery.com'>
    
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800&family=Dancing+Script&family=Lato:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- Flatsome theme CSS (local, downloaded by HTTrack) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsomeaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/flatsome-shopaad7.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style5152.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/wp-content/themes/flatsome-child/style6aec.css">

    <!-- Theme customizations matching original WordPress custom-css -->
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
    #logo { width: auto !important; }
    #logo img { display: none; }
    .site-logo-link { display:flex; align-items:center; gap:10px; text-decoration:none !important; user-select:none; }
    .logo-mark { position:relative; width:36px; height:36px; flex-shrink:0; }
    .logo-mark::before { content:''; position:absolute; inset:0; border-radius:50%; border:2.5px solid rgba(255,255,255,0.55); }
    .logo-mark::after  { content:''; position:absolute; inset:6px; border-radius:50%; background:linear-gradient(135deg,#008cb2,#00d4ff); box-shadow:0 0 10px rgba(0,180,224,0.5); }
    .logo-text { display:flex; flex-direction:column; line-height:1; gap:2px; }
    .logo-elite { font-family:'Barlow','Montserrat',sans-serif; font-size:10px; font-weight:600; letter-spacing:5px; color:rgba(255,255,255,0.55); text-transform:uppercase; }
    .logo-bbs   { font-family:'Barlow','Montserrat',sans-serif; font-size:24px; font-weight:900; letter-spacing:2px; color:#fff; text-transform:uppercase; line-height:0.95; }
    .logo-sub   { font-family:'Barlow','Montserrat',sans-serif; font-size:8px; font-weight:600; letter-spacing:3.5px; color:#008cb2; text-transform:uppercase; margin-top:3px; }
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

    /* ── Mobile menu drawer ── */
    #main-menu {
        position: fixed; top: 0; left: 0;
        width: 280px; height: 100%;
        background: #111318; z-index: 99999;
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
        overflow-y: auto;
        box-shadow: 4px 0 30px rgba(0,0,0,0.5);
    }
    #main-menu.is-open { transform: translateX(0); }
    #mobile-menu-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.6); z-index: 99998;
        backdrop-filter: blur(2px);
    }
    #mobile-menu-overlay.is-open { display: block; }
    .mobile-menu-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .mobile-menu-logo {
        font-family: 'Barlow', sans-serif; font-size: 14px; font-weight: 800;
        letter-spacing: 2px; color: #fff; text-transform: uppercase;
    }
    .mobile-menu-close {
        width: 36px; height: 36px; background: rgba(255,255,255,0.07);
        border: none; border-radius: 6px; cursor: pointer;
        color: #fff; font-size: 20px; display: flex;
        align-items: center; justify-content: center; transition: background 0.2s;
    }
    .mobile-menu-close:hover { background: rgba(255,255,255,0.14); }
    .mobile-menu-search {
        padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .mobile-menu-search form {
        display: flex; background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden;
    }
    .mobile-menu-search input {
        flex: 1; border: none; background: transparent;
        padding: 10px 14px; color: #fff; font-size: 14px; outline: none;
    }
    .mobile-menu-search input::placeholder { color: rgba(255,255,255,0.35); }
    .mobile-menu-search button {
        border: none; background: transparent; color: rgba(255,255,255,0.5);
        padding: 10px 14px; cursor: pointer; font-size: 16px;
    }
    .mobile-menu-nav a {
        display: block; padding: 13px 20px; color: rgba(255,255,255,0.8);
        font-family: 'Barlow', sans-serif; font-size: 14px; font-weight: 600;
        letter-spacing: 1px; text-transform: uppercase; text-decoration: none;
        border-left: 3px solid transparent;
        transition: color 0.2s, background 0.2s, border-color 0.2s;
    }
    .mobile-menu-nav a:hover, .mobile-menu-nav a.active {
        color: #fff; background: rgba(0,140,178,0.12); border-left-color: #008cb2;
    }
    .mobile-menu-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.07); }
    .mobile-menu-footer a {
        display: block; padding: 12px 16px;
        background: linear-gradient(135deg, #008cb2, #00b4e0);
        color: #fff; font-family: 'Barlow', sans-serif; font-size: 13px;
        font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
        text-decoration: none; border-radius: 8px; text-align: center;
    }
    </style>

    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
</head>
<body class="home page">

<a class="skip-link screen-reader-text" href="#main">Skip to content</a>

<div id="wrapper">

<!-- HEADER -->
<header id="header" class="header has-transparent header-full-width has-sticky sticky-jump">
    <div class="header-wrapper">
        <div id="masthead" class="header-main nav-dark">
            <div class="header-inner flex-row container logo-left medium-logo-center" role="navigation">
                
                <!-- Logo -->
                <div id="logo" class="flex-col logo">
                    <a href="<?php echo SITE_URL; ?>/" title="<?php echo htmlspecialchars(SITE_NAME); ?> - ELITE BBS RIMS" rel="home" class="site-logo-link">
                        <span class="logo-mark" aria-hidden="true"></span>
                        <span class="logo-text">
                            <span class="logo-elite">ELITE</span><span class="logo-bbs">BBS</span>
                            <span class="logo-sub">WHEELS &amp; RIMS</span>
                        </span>
                    </a>
                </div>

                <!-- Mobile Left Elements -->
                <div class="flex-col show-for-medium flex-left">
                    <ul class="mobile-nav nav nav-left">
                        <li class="nav-icon has-icon">
                            <a href="#" data-open="#main-menu" data-pos="left" class="is-small" aria-label="Menu">
                                <i class="icon-menu"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Left Elements - Navigation -->
                <div class="flex-col hide-for-medium flex-left flex-grow">
                    <ul class="header-nav header-nav-main nav nav-left nav-uppercase">
                        <li class="header-search header-search-dropdown has-icon has-dropdown menu-item-has-children">
                            <a href="#" aria-label="Search" class="is-small"><i class="icon-search"></i></a>
                            <ul class="nav-dropdown nav-dropdown-default dark dropdown-uppercase">
                                <li class="header-search-form search-form html relative has-icon">
                                    <div class="header-search-form-wrapper">
                                        <form role="search" method="get" action="<?php echo SITE_URL; ?>/shop" class="searchform">
                                            <div class="flex-row relative">
                                                <div class="flex-col flex-grow">
                                                    <input type="search" class="search-field mb-0" placeholder="Search..." value="" name="s" />
                                                </div>
                                                <div class="flex-col">
                                                    <button type="submit" value="Search" class="ux-search-submit submit-button secondary button icon mb-0">
                                                        <i class="icon-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li><a href="<?php echo SITE_URL; ?>/" class="nav-top-link active">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/shop" class="nav-top-link">Shop</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about" class="nav-top-link">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact" class="nav-top-link">Contact Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/testemonials" class="nav-top-link">Reviews</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/refund_returns" class="nav-top-link">Refund and Returns Policy</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/faq" class="nav-top-link">FAQ</a></li>
                    </ul>
                </div>

                <!-- Right Elements - Account & Cart -->
                <div class="flex-col hide-for-medium flex-right">
                    <ul class="header-nav header-nav-main nav nav-right nav-uppercase">
                        <li class="account-item has-icon">
                            <a href="<?php echo SITE_URL; ?>/my-account" class="nav-top-link nav-top-not-logged-in">
                                <span>Login</span>
                            </a>
                        </li>
                        <li class="header-divider"></li>
                        <li class="cart-item has-icon has-dropdown">
                            <a href="<?php echo SITE_URL; ?>/cart" class="header-cart-link is-small" title="Cart">
                                <span class="header-cart-title">
                                    Cart / <span class="cart-price">$0.00</span>
                                </span>
                                <span class="cart-icon image-icon">
                                    <strong>0</strong>
                                </span>
                            </a>
                            <ul class="nav-dropdown nav-dropdown-default dark dropdown-uppercase">
                                <li class="html widget_shopping_cart">
                                    <div class="widget_shopping_cart_content">
                                        <div class="ux-mini-cart-empty flex flex-row-col text-center pt pb">
                                            <div class="ux-mini-cart-empty-icon">
                                                <p>No products in the cart.</p>
                                            </div>
                                            <p class="return-to-shop">
                                                <a class="button primary wc-backward" href="<?php echo SITE_URL; ?>/shop">Return to shop</a>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Mobile Right Elements -->
                <div class="flex-col show-for-medium flex-right">
                    <ul class="mobile-nav nav nav-right">
                        <li class="cart-item has-icon">
                            <a href="<?php echo SITE_URL; ?>/cart" class="header-cart-link is-small off-canvas-toggle nav-top-link">
                                <span class="cart-icon image-icon">
                                    <strong>0</strong>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="container"><div class="top-divider full-width"></div></div>
        </div>
        <div class="header-bg-container fill"><div class="header-bg-image fill"></div><div class="header-bg-color fill"></div></div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main id="main">
    <div id="content" role="main">

        <!-- HERO SLIDER SECTION -->
        <div class="slider-wrapper relative" style="background-color:rgb(0, 0, 0);">
            <div class="slider slider-nav-circle slider-nav-large slider-nav-light slider-style-normal">
                
                <div class="banner has-hover is-full-height" id="banner-hero">
                    <div class="banner-inner fill">
                        <div class="banner-bg fill">
                            <div class="bg fill bg-loaded" style="background-image: url('<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/bbs.png'); background-position: 44% 34%;"></div>
                            <div class="overlay" style="background: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.35) 50%, rgba(0,0,0,0.65) 100%);"></div>
                        </div>
                    </div>
                    
                    <div class="banner-layers container">
                        <div class="fill banner-link"></div>

                        <div class="text-box banner-layer x50 md-x50 lg-x50 y50 md-y50 lg-y50 res-text">
                            <div class="text-box-content text dark">
                                <div class="text-inner text-center">

                                    <div class="hero-eyebrow">
                                        <span class="hero-eyebrow-dot"></span>
                                        German Engineering · Since 1970
                                        <span class="hero-eyebrow-dot"></span>
                                    </div>

                                    <h1 class="hero-main-title">
                                        The Art of the
                                        <span class="hero-title-accent">Perfect Wheel</span>
                                    </h1>

                                    <div class="hero-divider"></div>

                                    <p class="hero-sub">Hand-selected BBS wheels for drivers who demand the finest. Authentic forged performance, flawless fitment — delivered to your door.</p>

                                    <div class="hero-ctas">
                                        <a href="<?php echo SITE_URL; ?>/shop" class="hero-btn-primary">
                                            <svg viewBox="0 0 20 20" fill="none" width="16" height="16"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.4 3M17 13l1.4 3M9 18a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Shop Now
                                        </a>
                                        <a href="<?php echo SITE_URL; ?>/about" class="hero-btn-secondary">
                                            Our Story
                                            <svg viewBox="0 0 16 16" fill="none" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    </div>

                                    <div class="hero-badges">
                                        <span class="hero-badge">
                                            <svg viewBox="0 0 20 20" fill="none"><path d="M10 2l1.8 5.4H17l-4.6 3.4 1.8 5.4L10 13l-4.2 3.2 1.8-5.4L3 7.4h5.2L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                                            100% Authentic BBS
                                        </span>
                                        <span class="hero-badge">
                                            <svg viewBox="0 0 20 20" fill="none"><path d="M16 7s-.5-3.5-6-3.5S4 7 4 7l-1 8h14l-1-8z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M8 10h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                                            Free USA Shipping
                                        </span>
                                        <span class="hero-badge">
                                            <svg viewBox="0 0 20 20" fill="none"><path d="M10 2C6 2 3 5 3 9c0 5 7 9 7 9s7-4 7-9c0-4-3-7-7-7z" stroke="currentColor" stroke-width="1.4"/><path d="M10 7v2l1.5 1.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                                            Expert Fitment Advice
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading-spin dark large centered"></div>
        </div>

        <!-- VEHICLE FITMENT SEARCH SECTION -->
        <section id="section-vehicle-search">
            <div class="vsearch-bg">
                <div class="vsearch-overlay"></div>
                <div class="vsearch-content">
                    <div class="vsearch-eyebrow">
                        <span class="vsearch-dot"></span>
                        <span>Precision Fitment Technology</span>
                        <span class="vsearch-dot"></span>
                    </div>
                    <h2 class="vsearch-heading">Find Wheels For Your Vehicle</h2>
                    <p class="vsearch-subheading">Select your year, make &amp; model — we'll show you every BBS wheel that fits perfectly.</p>

                    <div class="vsearch-card">
                        <div class="vsearch-fields">
                            <div class="vsearch-field" id="vsf-year">
                                <label class="vsearch-label">
                                    <svg class="vsearch-icon" viewBox="0 0 20 20" fill="none"><rect x="3" y="4" width="14" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M3 8h14" stroke="currentColor" stroke-width="1.5"/><path d="M7 2v2M13 2v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Year
                                </label>
                                <div class="vsearch-select-wrap">
                                    <select id="vf2-year">
                                        <option value="">Select Year</option>
                                        <?php for ($yr = 2025; $yr >= 1985; $yr--): ?>
                                        <option value="<?php echo $yr; ?>"><?php echo $yr; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <svg class="vsearch-arrow" viewBox="0 0 12 7"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                </div>
                            </div>

                            <div class="vsearch-divider"></div>

                            <div class="vsearch-field" id="vsf-make">
                                <label class="vsearch-label">
                                    <svg class="vsearch-icon" viewBox="0 0 20 20" fill="none"><path d="M3 14l3-7h8l3 7" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><circle cx="6.5" cy="15" r="1.5" stroke="currentColor" stroke-width="1.5"/><circle cx="13.5" cy="15" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M3 14H2M18 14h-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Make
                                </label>
                                <div class="vsearch-select-wrap">
                                    <select id="vf2-make">
                                        <option value="">Select Make</option>
                                        <?php
                                        $makes = ['Acura','Alfa Romeo','Aston Martin','Audi','Bentley','BMW','Buick','Cadillac','Chevrolet','Chrysler','Dodge','Ferrari','Ford','Genesis','GMC','Honda','Hyundai','Infiniti','Jeep','Kia','Lamborghini','Lexus','Lincoln','Maserati','Mazda','Mercedes-Benz','Mitsubishi','Nissan','Pontiac','Porsche','Rolls-Royce','Scion','Subaru','Suzuki','Toyota','Volkswagen'];
                                        foreach ($makes as $mk): ?>
                                        <option value="<?php echo $mk; ?>"><?php echo $mk; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <svg class="vsearch-arrow" viewBox="0 0 12 7"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                </div>
                            </div>

                            <div class="vsearch-divider"></div>

                            <div class="vsearch-field" id="vsf-model">
                                <label class="vsearch-label">
                                    <svg class="vsearch-icon" viewBox="0 0 20 20" fill="none"><path d="M10 3v14M3 10h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Model
                                </label>
                                <div class="vsearch-select-wrap">
                                    <select id="vf2-model" disabled>
                                        <option value="">Select Make First</option>
                                    </select>
                                    <svg class="vsearch-arrow" viewBox="0 0 12 7"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                </div>
                            </div>

                            <button class="vsearch-btn" onclick="doSearch()">
                                <span class="vsearch-btn-text">
                                    <svg viewBox="0 0 22 22" fill="none"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="2"/><path d="M15.5 15.5L20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                    Search Wheels
                                </span>
                                <span class="vsearch-btn-shine"></span>
                            </button>
                        </div>
                    </div>

                    <div class="vsearch-stats">
                        <div class="vsearch-stat">
                            <strong>35+</strong><span>Makes</span>
                        </div>
                        <div class="vsearch-stat-divider"></div>
                        <div class="vsearch-stat">
                            <strong>300+</strong><span>Models</span>
                        </div>
                        <div class="vsearch-stat-divider"></div>
                        <div class="vsearch-stat">
                            <strong>40 Yrs</strong><span>Of Data</span>
                        </div>
                        <div class="vsearch-stat-divider"></div>
                        <div class="vsearch-stat">
                            <strong>100%</strong><span>Genuine BBS</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION 1 -->
        <section class="section dark" id="section-about-1" style="padding-top: 30px; padding-bottom: 30px; background-color: rgb(0,0,0);">
            <div class="section-content relative">
                <div class="row align-middle">
                    <div class="col medium-6 small-12 large-6">
                        <div class="col-inner">
                            <div class="text" style="text-align: center;">
                                <p><strong>Elite BBS Rims</strong> <strong>Timeless Performance. German Precision. Pure Icon.</strong></p>
                                <p>For over five decades, <strong>BBS Wheels</strong> has been the benchmark of excellence — forged in the fires of motorsport and perfected for the world's most demanding drivers. From legendary three-piece forged classics to modern flow-formed masterpieces, every BBS rim combines race-proven technology, featherweight construction, and unmistakable design elegance.</p>
                                <p>At <strong>Elite BBS Rims</strong>, we bring you hand-selected, authentic BBS wheels — the same name trusted by champions, supercar builders, and discerning enthusiasts across the globe.</p>
                                <ul style="color: var(--fs-color-secondary);">
                                    <li style="margin-top: 0.5rem; margin-bottom: 0.5rem;">Lightweight forged strength born from racing DNA</li>
                                    <li style="margin-bottom: 0.5rem;">Vintage-inspired designs with modern refinement</li>
                                    <li style="margin-bottom: 0.75rem;">Unrivaled fitment and finish for the USA market</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col medium-6 small-12 large-6">
                        <div class="col-inner">
                            <div class="img has-hover">
                                <div class="img-inner dark">
                                    <img src="<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/spec-1_sp-24y_18x8-1403-256-00-lay-1000-800x800.png" alt="BBS Wheel Spec" style="width: 100%;">
                                </div>
                            </div>
                            <a href="<?php echo SITE_URL; ?>/shop" class="button primary is-outline" style="display: block; width: fit-content; margin: 20px auto; border-radius: 99px;">
                                <span>Order yours</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION 2 -->
        <section class="section dark" id="section-about-2" style="padding-top: 30px; padding-bottom: 30px; background-color: rgb(0,0,0);">
            <div class="section-content relative">
                <div class="row align-middle">
                    <div class="col medium-6 small-12 large-6">
                        <div class="col-inner">
                            <div class="img has-hover">
                                <div class="img-inner dark">
                                    <img src="<?php echo SITE_URL; ?>/wp-content/uploads/2026/02/bbss-800x800.png" alt="BBS Wheels" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col medium-6 small-12 large-6">
                        <div class="col-inner">
                            <div class="text" style="text-align: center;">
                                <p><strong>The Pinnacle of Wheel Craftsmanship</strong></p>
                                <p>BBS is more than a wheel — it is a statement. Born in Germany's Black Forest in 1970, BBS Wheels have dominated racetracks, graced OEM supercars, and defined automotive culture for generations. Today, they remain the ultimate fusion of <strong>motorsport technology</strong> and <strong>vintage sophistication</strong>.</p>
                                <p>We curate the finest selection of authentic BBS rims — timeless designs that blend classic cross-spoke elegance with cutting-edge forged and flow-formed performance. Each wheel is an investment in heritage, precision, and prestige.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURED PRODUCTS -->
        <section id="section-products-1">
            <div class="fp-section">

                <!-- Section header -->
                <div class="fp-header">
                    <div class="fp-eyebrow">
                        <span class="fp-eyebrow-line"></span>
                        <span>Curated Collection</span>
                        <span class="fp-eyebrow-line"></span>
                    </div>
                    <h2 class="fp-heading">Featured Products</h2>
                    <p class="fp-subheading">Hand-picked wheels for the most discerning drivers</p>
                </div>

                <!-- Product grid -->
                <div class="fp-grid">
                    <?php foreach ($featured_products as $i => $product):
                        $images     = json_decode($product['images'] ?? '[]', true);
                        $img        = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
                        $price_info = get_display_price($product);
                        $is_new     = (strtotime($product['created_at']) > strtotime('-30 days'));
                    ?>
                    <article class="fp-card" style="animation-delay: <?php echo $i * 0.07; ?>s">
                        <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>" class="fp-card-link" tabindex="-1" aria-hidden="true"></a>

                        <!-- Image -->
                        <div class="fp-card-img-wrap">
                            <img src="<?php echo htmlspecialchars($img); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 class="fp-card-img" loading="lazy">

                            <!-- Hover overlay -->
                            <div class="fp-card-overlay">
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>" class="fp-overlay-btn">
                                    <svg viewBox="0 0 20 20" fill="none" width="16" height="16"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.8"/><path d="M14 14l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 6v6M6 9h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                    Quick View
                                </a>
                            </div>

                            <!-- Badges -->
                            <div class="fp-badges">
                                <?php if ($price_info['on_sale']): ?>
                                    <span class="fp-badge fp-badge--sale">SALE</span>
                                <?php elseif ($is_new): ?>
                                    <span class="fp-badge fp-badge--new">NEW</span>
                                <?php endif; ?>
                                <?php if (!is_in_stock($product)): ?>
                                    <span class="fp-badge fp-badge--sold">SOLD OUT</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="fp-card-info">
                            <p class="fp-card-cat"><?php echo htmlspecialchars($product['category'] ?? 'BBS Wheels'); ?></p>
                            <h3 class="fp-card-name">
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            <div class="fp-card-footer">
                                <div class="fp-card-price">
                                    <?php if ($price_info['on_sale']): ?>
                                        <span class="fp-price-old">$<?php echo number_format($price_info['regular'], 2); ?></span>
                                        <span class="fp-price-now">$<?php echo number_format($price_info['price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="fp-price-now">$<?php echo number_format($price_info['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>" class="fp-card-cta" aria-label="View <?php echo htmlspecialchars($product['name']); ?>">
                                    <svg viewBox="0 0 16 16" fill="none" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- View all -->
                <div class="fp-footer">
                    <a href="<?php echo SITE_URL; ?>/shop" class="fp-view-all">
                        View All Products
                        <svg viewBox="0 0 20 20" fill="none" width="16" height="16"><path d="M4 10h12M12 5l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>

            </div>
        </section>

        <!-- NEWS / BLOG SECTION -->
        <section class="section dark" id="section-blog" style="padding-top: 60px; padding-bottom: 60px; background-color: rgb(27, 27, 27);">
            <div class="section-content relative">
                
                <div class="section-title-container">
                    <h3 class="section-title section-title-center"><b></b><span class="section-title-main">Latest News</span><b></b></h3>
                </div>
                
                <div class="row large-columns-4 medium-columns-3 small-columns-1 has-shadow row-box-shadow-1 slider row-slider slider-nav-reveal">
                    
                    <div class="col post-item">
                        <div class="col-inner">
                            <div class="box box-overlay dark box-text-bottom box-blog-post has-hover">
                                <div class="box-image">
                                    <div class="image-cover" style="padding-top: 300px;">
                                        <a href="#" class="plain">
                                            <?php $ni=json_decode($featured_products[0]['images']??'[]',true); ?>
                                            <img src="<?php echo htmlspecialchars($ni[0]??''); ?>" alt="Elite BBS Rims Guide">
                                        </a>
                                        <div class="overlay" style="background-color: rgba(0,0,0,.25)"></div>
                                    </div>
                                </div>
                                <div class="box-text text-center">
                                    <div class="box-text-inner blog-post-inner">
                                        <h5 class="post-title is-large uppercase">
                                            <a href="#" class="plain">Elite BBS Rims Guide: Authentic Forged Wheels That Turn Heads</a>
                                        </h5>
                                        <div class="is-divider"></div>
                                        <p class="from_the_blog_excerpt">Elite BBS Rims Guide: Authentic Forged Wheels That Turn Heads and Drop Weight</p>
                                    </div>
                                </div>
                                <div class="badge absolute top post-date badge-square">
                                    <div class="badge-inner">
                                        <span class="post-date-day">03</span><br>
                                        <span class="post-date-month is-xsmall">Feb</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col post-item">
                        <div class="col-inner">
                            <div class="box box-overlay dark box-text-bottom box-blog-post has-hover">
                                <div class="box-image">
                                    <div class="image-cover" style="padding-top: 300px;">
                                        <a href="#" class="plain">
                                            <?php $ni=json_decode($featured_products[1]['images']??'[]',true); ?>
                                            <img src="<?php echo htmlspecialchars($ni[0]??''); ?>" alt="Why Enthusiasts Choose Elite BBS Rims">
                                        </a>
                                        <div class="overlay" style="background-color: rgba(0,0,0,.25)"></div>
                                    </div>
                                </div>
                                <div class="box-text text-center">
                                    <div class="box-text-inner blog-post-inner">
                                        <h5 class="post-title is-large uppercase">
                                            <a href="#" class="plain">Why Enthusiasts Choose Elite BBS Rims</a>
                                        </h5>
                                        <div class="is-divider"></div>
                                        <p class="from_the_blog_excerpt">In the world of aftermarket wheels, trends come and go faster than a quarter-mile run.</p>
                                    </div>
                                </div>
                                <div class="badge absolute top post-date badge-square">
                                    <div class="badge-inner">
                                        <span class="post-date-day">03</span><br>
                                        <span class="post-date-month is-xsmall">Feb</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col post-item">
                        <div class="col-inner">
                            <div class="box box-overlay dark box-text-bottom box-blog-post has-hover">
                                <div class="box-image">
                                    <div class="image-cover" style="padding-top: 300px;">
                                        <a href="#" class="plain">
                                            <?php $ni=json_decode($featured_products[2]['images']??'[]',true); ?>
                                            <img src="<?php echo htmlspecialchars($ni[0]??''); ?>" alt="Top 5 Must-Have BBS Wheels">
                                        </a>
                                        <div class="overlay" style="background-color: rgba(0,0,0,.25)"></div>
                                    </div>
                                </div>
                                <div class="box-text text-center">
                                    <div class="box-text-inner blog-post-inner">
                                        <h5 class="post-title is-large uppercase">
                                            <a href="#" class="plain">Top 5 Must-Have BBS Wheels from Elite BBS Rims</a>
                                        </h5>
                                        <div class="is-divider"></div>
                                        <p class="from_the_blog_excerpt">At Elite BBS Rims, we hand-pick the absolute cream of the crop from BBS's legendary lineup.</p>
                                    </div>
                                </div>
                                <div class="badge absolute top post-date badge-square">
                                    <div class="badge-inner">
                                        <span class="post-date-day">03</span><br>
                                        <span class="post-date-month is-xsmall">Feb</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col post-item">
                        <div class="col-inner">
                            <div class="box box-overlay dark box-text-bottom box-blog-post has-hover">
                                <div class="box-image">
                                    <div class="image-cover" style="padding-top: 300px;">
                                        <a href="#" class="plain">
                                            <?php $ni=json_decode($featured_products[3]['images']??'[]',true); ?>
                                            <img src="<?php echo htmlspecialchars($ni[0]??''); ?>" alt="Where to Buy Elite BBS Rims">
                                        </a>
                                        <div class="overlay" style="background-color: rgba(0,0,0,.25)"></div>
                                    </div>
                                </div>
                                <div class="box-text text-center">
                                    <div class="box-text-inner blog-post-inner">
                                        <h5 class="post-title is-large uppercase">
                                            <a href="#" class="plain">Where to Buy Elite BBS Rims: Your Guide</a>
                                        </h5>
                                        <div class="is-divider"></div>
                                        <p class="from_the_blog_excerpt">If you're hunting for Elite BBS Rims — those premium, hand-curated selections of genuine BBS wheels.</p>
                                    </div>
                                </div>
                                <div class="badge absolute top post-date badge-square">
                                    <div class="badge-inner">
                                        <span class="post-date-day">03</span><br>
                                        <span class="post-date-month is-xsmall">Feb</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <!-- NEWSLETTER SECTION -->
        <section class="section dark" id="section-newsletter" style="padding-top: 30px; padding-bottom: 30px; background-color: rgb(40, 40, 40);">
            <div class="section-content relative">
                <div class="text" style="text-align: center;">
                    <h2>Stay Connected to Elite BBS Rims</h2>
                    <p class="lead">Subscribe to be the first to know about new wheels and promotions.</p>
                </div>
            </div>
        </section>
        
        <section class="section dark" id="section-newsletter-form" style="padding-top: 30px; padding-bottom: 30px; background-color: rgb(40, 40, 40);">
            <div class="section-content relative">
                <div class="newsletter-form-wrapper" style="max-width: 600px; margin: 0 auto;">
                    <form class="newsletter-form" action="#" method="post">
                        <div class="flex-row form-flat medium-flex-wrap" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: center;">
                            <div class="flex-col flex-grow" style="flex: 1; min-width: 250px;">
                                <input type="email" name="email" placeholder="Your Email (required)" required style="width: 100%; padding: 15px 20px; border: none; border-radius: 4px; font-size: 14px;">
                            </div>
                            <div class="flex-col" style="flex-shrink: 0;">
                                <button type="submit" class="button primary" style="padding: 15px 30px; border-radius: 4px;">Sign Up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>
</main>

<!-- FOOTER -->
<footer id="footer" class="footer-wrapper">
    
    <!-- FOOTER WIDGETS 1 -->
    <div class="footer-widgets footer footer-1">
        <div class="row dark large-columns-3 mb-0" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            <div class="col pb-0 widget woocommerce widget_products">
                <span class="widget-title">Latest</span>
                <div class="is-divider small"></div>
                <ul class="product_list_widget">
                    <?php foreach (array_slice($all_products, 0, 4) as $product): ?>
                        <?php
                        $images = json_decode($product['images'] ?? '[]', true);
                        $img = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
                        $price_info = get_display_price($product);
                        ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <span class="product-title"><?php echo htmlspecialchars($product['name']); ?></span>
                            </a>
                            <?php if ($price_info['on_sale']): ?>
                                <del><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['regular'], 2); ?></span></del>
                                <ins><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span></ins>
                            <?php else: ?>
                                <span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="col pb-0 widget woocommerce widget_products">
                <span class="widget-title">Best Selling</span>
                <div class="is-divider small"></div>
                <ul class="product_list_widget">
                    <?php foreach (array_slice($all_products, 0, 4) as $product): ?>
                        <?php
                        $images = json_decode($product['images'] ?? '[]', true);
                        $img = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
                        $price_info = get_display_price($product);
                        ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <span class="product-title"><?php echo htmlspecialchars($product['name']); ?></span>
                            </a>
                            <?php if ($price_info['on_sale']): ?>
                                <del><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['regular'], 2); ?></span></del>
                                <ins><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span></ins>
                            <?php else: ?>
                                <span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="col pb-0 widget woocommerce widget_products">
                <span class="widget-title">Top Rated</span>
                <div class="is-divider small"></div>
                <ul class="product_list_widget">
                    <?php foreach (array_slice($all_products, 0, 4) as $product): ?>
                        <?php
                        $images = json_decode($product['images'] ?? '[]', true);
                        $img = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
                        $price_info = get_display_price($product);
                        ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <span class="product-title"><?php echo htmlspecialchars($product['name']); ?></span>
                            </a>
                            <?php if ($price_info['on_sale']): ?>
                                <del><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['regular'], 2); ?></span></del>
                                <ins><span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span></ins>
                            <?php else: ?>
                                <span class="woocommerce-Price-amount amount">$<?php echo number_format($price_info['price'], 2); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
        </div>
    </div>

    <!-- FOOTER WIDGETS 2 -->
    <div class="footer-widgets footer footer-2 dark">
        <div class="row dark large-columns-3 mb-0" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            <div class="col pb-0 widget block_widget footer-about">
                <span class="widget-title">About us</span>
                <div class="is-divider small"></div>
                <p>Welcome to Elite BBS Wheels, your premier boutique destination for genuine BBS forged and performance wheels in America. Based in the heart of Michigan, we specialize in hand-selecting and offering the most iconic, lightweight, and timeless BBS rims that blend German motorsport heritage with street-dominating style.</p>
                <div class="social-icons follow-icons">
                    <a href="#" target="_blank" class="icon button circle is-outline facebook" title="Follow on Facebook"><i class="icon-facebook"></i></a>
                    <a href="#" target="_blank" class="icon button circle is-outline instagram" title="Follow on Instagram"><i class="icon-instagram"></i></a>
                    <a href="#" target="_blank" class="icon button circle is-outline twitter" title="Follow on Twitter"><i class="icon-twitter"></i></a>
                    <a href="mailto:info@elitebbswheels.store" class="icon button circle is-outline email" title="Send us an email"><i class="icon-envelop"></i></a>
                    <a href="#" target="_blank" class="icon button circle is-outline pinterest" title="Follow on Pinterest"><i class="icon-pinterest"></i></a>
                </div>
            </div>
            
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
            
            <div class="col pb-0 widget widget_text footer-contact">
                <span class="widget-title">Contact us</span>
                <div class="is-divider small"></div>
                <p><a href="mailto:info@elitebbswheels.store">info@elitebbswheels.store</a></p>
                <p><a href="tel:+16177082284">+1(617)708-2284</a></p>
                <p>20802 Highland Knolls Drive<br>Katy, TX 77450, USA</p>
                <p>Business Hours:<br>Monday - Friday, 9:00 AM - 5:00 PM</p>
            </div>
            
        </div>
    </div>

    <!-- FOOTER BOTTOM -->
    <div class="absolute-footer dark medium-text-center text-center">
        <div class="container clearfix">
            <div class="footer-secondary pull-right">
                <div class="payment-icons inline-block">
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349221.png" alt="Visa" style="height: 24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349228.png" alt="Mastercard" style="height: 24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349230.png" alt="Amex" style="height: 24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/888/888870.png" alt="Apple Pay" style="height: 24px;"></div>
                </div>
            </div>
            <div class="footer-primary pull-left">
                <div class="links footer-nav uppercase" style="display: flex; justify-content: center; gap: 20px; margin-bottom: 15px; flex-wrap: wrap;">
                    <a href="<?php echo SITE_URL; ?>/refund_returns">Refund and Returns Policy</a>
                    <a href="<?php echo SITE_URL; ?>/privacy-policy">Privacy Policy</a>
                    <a href="<?php echo SITE_URL; ?>/terms-conditions">Terms &amp; Conditions</a>
                    <a href="<?php echo SITE_URL; ?>/shipping-policy">Shipping Policy</a>
                    <a href="<?php echo SITE_URL; ?>/faq">FAQ</a>
                </div>
                <div class="copyright-footer">
                    Copyright <?php echo date('Y'); ?> © <strong>ELITE BBS RIMS</strong>
                </div>
            </div>
        </div>
    </div>

</footer>

<a href="#top" class="back-to-top button icon invert plain fixed bottom z-1 is-outline hide-for-medium circle" id="top-link" aria-label="Go to top">
    <i class="icon-angle-up"></i>
</a>

</div>

<!-- Mobile Menu Sidebar -->
<div id="mobile-menu-overlay"></div>

<div id="main-menu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-logo">Elite BBS Rims</span>
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Close menu">&times;</button>
    </div>
    <div class="mobile-menu-search">
        <form method="get" action="<?php echo SITE_URL; ?>/shop">
            <input type="search" name="search" placeholder="Search wheels...">
            <button type="submit"><i class="icon-search"></i></button>
        </form>
    </div>
    <nav class="mobile-menu-nav">
        <a href="<?php echo SITE_URL; ?>/" class="active">Home</a>
        <a href="<?php echo SITE_URL; ?>/shop">Shop</a>
        <a href="<?php echo SITE_URL; ?>/about">About Us</a>
        <a href="<?php echo SITE_URL; ?>/contact">Contact</a>
        <a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a>
        <a href="<?php echo SITE_URL; ?>/refund_returns">Returns</a>
        <a href="<?php echo SITE_URL; ?>/faq">FAQ</a>
    </nav>
    <div class="mobile-menu-footer">
        <a href="<?php echo SITE_URL; ?>/shop">Shop All Wheels</a>
    </div>
</div>

<script>
(function() {
    var btn     = document.querySelector('[data-open="#main-menu"]');
    var menu    = document.getElementById('main-menu');
    var overlay = document.getElementById('mobile-menu-overlay');
    var closeBtn = document.getElementById('mobile-menu-close');
    function openMenu()  { menu.classList.add('is-open'); overlay.classList.add('is-open'); if(btn) btn.closest('li,div')?.querySelector('a,button')?.classList.add('is-open'); document.body.style.overflow='hidden'; }
    function closeMenu() { menu.classList.remove('is-open'); overlay.classList.remove('is-open'); document.body.style.overflow=''; }
    if (btn)      btn.addEventListener('click', function(e){ e.preventDefault(); openMenu(); });
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (overlay)  overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeMenu(); });
})();
</script>

<script src="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/js/flatsomed02f.js"></script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
(function() {
    var vehicleData = {
        'Toyota': ['Camry','Corolla','RAV4','Hilux','Land Cruiser','Yaris','Prius','HiAce','Supra','86','GR86','Tundra','4Runner','Sequoia'],
        'Honda': ['Civic','Accord','CR-V','HR-V','Jazz','City','Pilot','Odyssey','S2000','NSX','Type R','Fit','Passport'],
        'Ford': ['F-150','F-250','F-350','Mustang','Mustang GT500','Explorer','Ranger','Escape','Edge','Bronco','Transit','GT'],
        'Chevrolet': ['Silverado','Malibu','Equinox','Tahoe','Suburban','Camaro','Camaro ZL1','Traverse','Corvette','Colorado','Blazer'],
        'BMW': ['2 Series','3 Series','4 Series','5 Series','7 Series','8 Series','X3','X5','X6','X7','M2','M3','M4','M5','M8','i4','iX'],
        'Mercedes-Benz': ['C-Class','E-Class','S-Class','GLE','GLC','GLS','A-Class','AMG GT','AMG C63','AMG E63','AMG G63','G-Class','SL','CLA'],
        'Nissan': ['Altima','Sentra','Rogue','Pathfinder','Frontier','Titan','Murano','370Z','GT-R','Skyline','Maxima','Armada'],
        'Hyundai': ['Elantra','Sonata','Tucson','Santa Fe','Palisade','Kona','Ioniq','Ioniq 5','Ioniq 6','Veloster N','i30 N'],
        'Kia': ['Optima','Sportage','Sorento','Telluride','Soul','Stinger','EV6','Carnival','K5'],
        'Volkswagen': ['Golf','Golf R','Passat','Tiguan','Jetta','Atlas','Polo','Arteon','GTI','R32'],
        'Audi': ['A3','A4','A6','A8','Q3','Q5','Q7','Q8','TT','TTS','R8','RS3','RS4','RS6','RS7','e-tron','e-tron GT'],
        'Dodge': ['Charger','Charger Hellcat','Challenger','Challenger Hellcat','Durango','Ram 1500','Journey','Viper'],
        'Jeep': ['Wrangler','Cherokee','Grand Cherokee','Grand Cherokee L','Compass','Renegade','Gladiator'],
        'Subaru': ['Outback','Forester','Impreza','Legacy','Crosstrek','WRX','WRX STI','BRZ'],
        'Mazda': ['Mazda3','Mazda6','CX-5','CX-9','MX-5 Miata','CX-30','CX-50','RX-7','RX-8'],
        'Porsche': ['911','911 GT3','911 Turbo','Cayenne','Macan','Panamera','Taycan','718 Cayman','718 Boxster'],
        'Ferrari': ['488','F8 Tributo','SF90','Roma','Portofino','296 GTB','812 Superfast','F40','F50','Enzo','LaFerrari'],
        'Lamborghini': ['Huracan','Huracan EVO','Urus','Aventador','Aventador SVJ','Gallardo','Murcielago'],
        'Bentley': ['Continental GT','Continental GTC','Flying Spur','Bentayga','Mulsanne'],
        'Rolls-Royce': ['Ghost','Phantom','Wraith','Dawn','Cullinan','Spectre'],
        'Maserati': ['Ghibli','Quattroporte','GranTurismo','GranCabrio','Levante','MC20'],
        'Alfa Romeo': ['Giulia','Stelvio','4C','Giulia Quadrifoglio','Tonale'],
        'Aston Martin': ['DB11','Vantage','DBS','DBX','Valkyrie'],
        'Lexus': ['IS','ES','GS','LS','RC','RC F','LC','LC 500','NX','RX','LX','GX','UX'],
        'Infiniti': ['Q50','Q60','Q70','QX50','QX55','QX60','QX80','G35','G37'],
        'Acura': ['ILX','TLX','RLX','MDX','RDX','NSX','Integra','RSX'],
        'Mitsubishi': ['Lancer','Lancer Evolution','Eclipse','Eclipse Cross','Outlander','Galant','3000GT'],
        'Cadillac': ['CT4','CT5','CT6','XT4','XT5','XT6','Escalade','CTS-V','ATS-V'],
        'Lincoln': ['Navigator','Aviator','Corsair','Nautilus','Continental','MKZ'],
        'Buick': ['Enclave','Envision','Encore','LaCrosse','Regal GS'],
        'GMC': ['Sierra','Yukon','Yukon XL','Canyon','Terrain','Acadia','Envoy'],
        'Pontiac': ['GTO','Firebird','Trans Am','G8','Solstice'],
        'Chrysler': ['300','300C','300 SRT8','Pacifica','Sebring'],
        'Genesis': ['G70','G80','G90','GV70','GV80','GV60'],
        'Scion': ['FR-S','tC','xB','iQ'],
        'Suzuki': ['Swift','Jimny','Vitara','Grand Vitara','SX4']
    };

    var makeEl  = document.getElementById('vf2-make');
    var modelEl = document.getElementById('vf2-model');
    var yearEl  = document.getElementById('vf2-year');

    if (makeEl && modelEl) {
        makeEl.addEventListener('change', function() {
            var models = vehicleData[this.value] || [];
            if (models.length) {
                modelEl.innerHTML = '<option value="">Select Model</option>';
                models.forEach(function(m) {
                    var opt = document.createElement('option');
                    opt.value = opt.textContent = m;
                    modelEl.appendChild(opt);
                });
                modelEl.disabled = false;
                document.getElementById('vsf-model').classList.add('vsearch-field--active');
            } else {
                modelEl.innerHTML = '<option value="">Select Model</option>';
                modelEl.disabled = true;
                document.getElementById('vsf-model').classList.remove('vsearch-field--active');
            }
        });
    }

    // Highlight field on selection
    [yearEl, makeEl, modelEl].forEach(function(el) {
        if (!el) return;
        el.addEventListener('change', function() {
            if (this.value) this.closest('.vsearch-field').classList.add('vsearch-field--selected');
            else this.closest('.vsearch-field').classList.remove('vsearch-field--selected');
        });
    });

    window.doSearch = function() {
        var year  = yearEl  ? yearEl.value  : '';
        var make  = makeEl  ? makeEl.value  : '';
        var model = modelEl ? modelEl.value : '';

        if (!make) {
            makeEl.closest('.vsearch-field').classList.add('vsearch-field--error');
            makeEl.focus();
            setTimeout(function() { makeEl.closest('.vsearch-field').classList.remove('vsearch-field--error'); }, 1500);
            return;
        }
        if (!model) {
            modelEl.closest('.vsearch-field').classList.add('vsearch-field--error');
            modelEl.focus();
            setTimeout(function() { modelEl.closest('.vsearch-field').classList.remove('vsearch-field--error'); }, 1500);
            return;
        }

        var query = [year, make, model].filter(Boolean).join(' ');
        window.location.href = '<?php echo SITE_URL; ?>/shop?search=' + encodeURIComponent(query);
    };

    // Back to top button
    var backToTop = document.getElementById('top-link');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
    }
})();
</script>

</body>
</html>
