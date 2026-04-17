<?php
/**
 * Contact Page - Elite BBS Rims
 */

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

$page = 'contact';
$page_title = "Contact Us - " . SITE_NAME;
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Send email via SMTP mailer
        require_once INCLUDES_PATH . '/mailer.php';
        if (send_contact_notification($name, $email, $phone, $subject, $message)) {
            $success = 'Thank you! Your message has been sent. We will respond within 24 hours.';
        } else {
            $error = 'An error occurred sending your message. Please try again or email us directly.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Contact Elite BBS Rims in Katy, TX for authentic BBS wheel inquiries, fitment advice, and order support. Call +1(617)708-2284 or email Sales@elitebbswheelsus.shop.">
    <link rel="canonical" href="https://www.elitebbswheelsus.shop/contact">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="https://www.elitebbswheelsus.shop/contact">
    <meta property="og:site_name"   content="Elite BBS Rims">
    <meta property="og:title"       content="Contact Elite BBS Rims — Katy, TX">
    <meta property="og:description" content="Get in touch with Elite BBS Rims for BBS wheel inquiries, fitment guidance, or order support. Call +1(617)708-2284.">
    <meta property="og:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">
    <meta property="og:locale"      content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="Contact Elite BBS Rims">
    <meta name="twitter:description" content="Get in touch with Elite BBS Rims for BBS wheel inquiries, fitment guidance, or order support.">
    <meta name="twitter:image"       content="https://www.elitebbswheelsus.shop/wp-content/uploads/2026/02/bbs.png">

    <!-- JSON-LD: BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home",    "item": "https://www.elitebbswheelsus.shop/"},
        {"@type": "ListItem", "position": 2, "name": "Contact", "item": "https://www.elitebbswheelsus.shop/contact"}
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
            color: #444;
        }

        .content-page h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 26px;
            color: #1a1a1a;
            margin: 40px 0 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .section-intro {
            text-align: center;
            font-size: 20px;
            color: #555;
            margin-bottom: 50px;
            line-height: 1.7;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .contact-info h2 {
            color: #008cb2;
            margin-top: 0;
        }
        
        .contact-info p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 20px;
        }
        
        .contact-details {
            margin: 30px 0;
        }
        
        .contact-details h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .contact-details p {
            margin: 8px 0;
        }
        
        .contact-details strong {
            color: #008cb2;
        }
        
        .contact-form-wrap {
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e8e8e8;
        }
        
        .contact-form-wrap h2 {
            margin-top: 0;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 14px;
            color: #333;
        }
        
        .form-group label .required {
            color: #b20000;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #d1d1d1;
            border-radius: 6px;
            font-family: 'Lato', sans-serif;
            font-size: 15px;
            background: #fafafa;
            transition: all 0.25s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #008cb2;
        }
        
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .submit-btn {
            padding: 15px 40px;
            background: #008cb2;
            color: #fff;
            border: none;
            border-radius: 99px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #006f8f;
        }
        
                
                
                
                
                
                
                
        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header h1 {
                font-size: 32px;
            }
        }

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
        <h1>Contact Us</h1>
    </div>

    <main>
        <div class="content-page">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <strong>✓</strong> <?php echo $success; ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-error">
                    <strong>✗</strong> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p>Have questions about our products? Need help with fitment? We'd love to hear from you!</p>
                    <p>Our team of wheel experts is ready to help you find the perfect wheels for your vehicle.</p>
                    
                    <div class="contact-details">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong> info@elitebbswheels.store</p>
                        <p><strong>Response Time:</strong> Within 24 hours</p>
                        <p><strong>Business Hours:</strong> Monday - Friday, 9AM - 6PM EST</p>
                    </div>
                    
                    <div class="contact-details">
                        <h3>What we can help with:</h3>
                        <p>• Product inquiries and availability</p>
                        <p>• Wheel fitment recommendations</p>
                        <p>• Order status updates</p>
                        <p>• Shipping quotes</p>
                        <p>• General questions</p>
                    </div>
                </div>
                
                <div class="contact-form-wrap">
                    <h2>Send us a Message</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" name="name" required value="<?php echo $_POST['name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" required value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo $_POST['phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Subject</label>
                            <select name="subject">
                                <option value="">Select a topic</option>
                                <option value="product_inquiry" <?php echo ($_POST['subject'] ?? '') === 'product_inquiry' ? 'selected' : ''; ?>>Product Inquiry</option>
                                <option value="fitment_help" <?php echo ($_POST['subject'] ?? '') === 'fitment_help' ? 'selected' : ''; ?>>Fitment Help</option>
                                <option value="order_status" <?php echo ($_POST['subject'] ?? '') === 'order_status' ? 'selected' : ''; ?>>Order Status</option>
                                <option value="general" <?php echo ($_POST['subject'] ?? '') === 'general' ? 'selected' : ''; ?>>General Question</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Message <span class="required">*</span></label>
                            <textarea name="message" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                        </div>
                        
                        <button type="submit" name="submit_contact" class="submit-btn" style="background:#008cb2;color:#fff;border:none;padding:14px 35px;border-radius:6px;font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:1px;cursor:pointer;transition:background 0.3s;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php require INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>