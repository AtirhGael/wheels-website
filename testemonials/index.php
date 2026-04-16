<?php
/**
 * Testimonials/Reviews Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'testemonials';
$page_title = "Customer Reviews - " . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Customer reviews and testimonials for Elite BBS Rims.">
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
        .intro { text-align: center; margin-bottom: 50px; }
        .intro p { font-size: 20px; color: #555; line-height: 1.8; font-weight: 400; }
        .review-item { background: #fff; padding: 30px 35px; margin-bottom: 25px; border-radius: 12px; border-left: 4px solid #008cb2; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eaeaea; transition: box-shadow 0.3s ease; }
        .review-item:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .review-author { font-weight: 700; font-size: 18px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; letter-spacing: 0.3px; }
        .review-rating { color: #ffc107; font-size: 18px; letter-spacing: 3px; }
        .review-vehicle { color: #008cb2; font-style: italic; margin-bottom: 15px; font-size: 14px; font-weight: 500; }
        .review-text { line-height: 1.85; color: #444; font-size: 16px; }
        .review-item:nth-child(even) { border-left-color: #4ad8ff; }
        .cta-section { text-align: center; margin-top: 50px; padding: 40px; background: #f0f7ff; border-radius: 10px; }
        .cta-section h2 { color: #008cb2; margin-top: 0; }
                                                                @media (max-width: 768px) { .page-header h1 { font-size: 32px; } .review-header { flex-direction: column; align-items: flex-start; gap: 10px; } }

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

    <div class="page-header"><h1>Customer Reviews</h1></div>
    <main>
        <div class="content-page">
            <div class="intro">
                <p>Don't just take our word for it — hear from our satisfied customers.</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Mike T.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2021 BMW M3</p>
                <p class="review-text">Got a set of BBS LM wheels from Elite BBS Rims and couldn't be happier. The fitment was perfect and the quality is outstanding. These wheels transformed the look of my M3. Highly recommend!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Sarah L.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2019 Porsche 911 Carrera</p>
                <p class="review-text">Excellent service from start to finish. The team helped me find the right offset and size for my 911. The BBS Super RS wheels look incredible on the car. Fast shipping too!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">James R.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2020 Toyota Supra</p>
                <p class="review-text">Been looking for authentic BBS wheels for months. Finally found them here at a great price. The customer service was top-notch — they answered all my fitment questions. Will buy again!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">David K.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2018 Mercedes-AMG GT</p>
                <p class="review-text">Bought a set of BBS FI-R wheels. The gunmetal finish is exactly as shown on the website. Very happy with the quality. These guys really know their wheels!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Alex M.</span>
                    <span class="review-rating">★★★★☆</span>
                </div>
                <p class="review-vehicle">Vehicle: 2022 Honda Civic Type R</p>
                <p class="review-text">Great selection of wheels and good prices. The only reason for 4 stars is the slightly longer shipping time, but the wheels arrived in perfect condition. Very happy with my purchase!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Jennifer W.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2017 Nissan GT-R</p>
                <p class="review-text">Ordered the Work VSMX wheels for my GT-R. The team provided excellent fitment advice and the wheels look amazing. True enthusiasts who know their products!</p>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Robert H.</span>
                    <span class="review-rating">★★★★★</span>
                </div>
                <p class="review-vehicle">Vehicle: 2023 Ford Mustang GT</p>
                <p class="review-text">First time buying wheels online and the experience was great. They even helped me pick the right size for my Mustang. The BBS wheels arrived brand new in box. Highly recommend!</p>
            </div>
            
            <div class="cta-section">
                <h2>Have you purchased from us?</h2>
                <p>We'd love to hear your feedback! Contact us to share your experience.</p>
                <a href="<?php echo SITE_URL; ?>/contact" style="display: inline-block; padding: 15px 40px; background: #008cb2; color: #fff; text-decoration: none; border-radius: 99px; font-weight: 700; margin-top: 15px;">Share Your Review</a>
            </div>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>