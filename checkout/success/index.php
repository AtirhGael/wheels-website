<?php
/**
 * Checkout Success / Payment Instructions — Elite BBS Rims
 */

require_once __DIR__ . '/../../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/cart_functions.php';

$page         = 'checkout';
$page_title   = "Order Confirmed - " . SITE_NAME;
$order_number = sanitize($_GET['order'] ?? '');
$method       = sanitize($_GET['method'] ?? 'email_transfer');

/* ── Fetch order from DB so we have the real total ── */
$order = null;
if ($order_number) {
    $order = db_get_row("SELECT * FROM orders WHERE order_number = :n", [':n' => $order_number]);
    if ($order) $method = $order['payment_method'] ?: $method;
}

/* ── Fetch live payment credentials from settings ── */
$btc_wallet    = get_site_setting('payment_bitcoin_wallet', '');
$bank_name     = get_site_setting('payment_bank_name',     '');
$bank_account  = get_site_setting('payment_bank_account',  '');
$bank_routing  = get_site_setting('payment_bank_routing',  '');
$bank_details  = get_site_setting('payment_bank_details',  '');
$pp_email      = get_site_setting('payment_paypal_email',  '');
$pp_link       = get_site_setting('payment_paypal_link',   '');

$method_labels = [
    'bitcoin'        => 'Bitcoin',
    'bank'           => 'Bank Transfer',
    'paypal'         => 'PayPal',
    'email_transfer' => 'Pending',
];
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
        .footer-1 { background-color: #222; }
        .footer-2 { background-color: #111; }
        .absolute-footer, html { background-color: #000; }

        body { background: #f4f6f8; }

        /* ── Hero ── */
        .success-hero {
            background: linear-gradient(135deg, #0d0f13 0%, #1a1f2e 100%);
            padding: 50px 24px 44px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .success-check {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            font-size: 30px; color: #fff; font-weight: 900;
            box-shadow: 0 0 30px rgba(34,197,94,0.4);
        }
        .success-hero h1 {
            font-family: 'Barlow', sans-serif;
            font-size: clamp(26px, 4vw, 42px);
            font-weight: 900;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 10px;
        }
        .success-hero .order-ref {
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 1px;
        }
        .success-hero .order-ref strong {
            color: #00b4e0;
            font-size: 16px;
            font-weight: 800;
        }

        /* ── Progress steps ── */
        .co-steps {
            display: flex; align-items: center; justify-content: center;
            gap: 0; margin-top: 28px;
        }
        .co-step {
            display: flex; align-items: center; gap: 8px;
            font-family: 'Barlow', sans-serif; font-size: 11px;
            font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }
        .co-step.done   { color: #22c55e; }
        .co-step.active { color: #fff; }
        .co-step-num {
            width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 900;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
        }
        .co-step.done   .co-step-num { background: #16a34a; border-color: #16a34a; color: #fff; }
        .co-step.active .co-step-num { background: #008cb2; border-color: #008cb2; color: #fff; }
        .co-step-line { width: 48px; height: 1px; background: rgba(255,255,255,0.12); margin: 0 4px; }

        /* ── Page body ── */
        .success-wrap {
            max-width: 760px;
            margin: 40px auto;
            padding: 0 20px 80px;
        }

        /* ── Payment instructions card ── */
        .pay-instructions {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid #e8ecf0;
            margin-bottom: 24px;
        }
        .pay-instr-header {
            padding: 18px 24px;
            display: flex; align-items: center; gap: 14px;
        }
        .pay-instr-header.btc   { background: linear-gradient(135deg, #f7931a, #ffb347); }
        .pay-instr-header.bank  { background: linear-gradient(135deg, #1a6b3e, #22a85c); }
        .pay-instr-header.pp    { background: linear-gradient(135deg, #003087, #0070ba); }
        .pay-instr-header.other { background: linear-gradient(135deg, #1a1a1a, #333); }
        .pay-instr-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 900; color: #fff; flex-shrink: 0;
        }
        .pay-instr-title { font-family: 'Barlow', sans-serif; font-size: 16px; font-weight: 900; color: #fff; letter-spacing: 0.5px; }
        .pay-instr-subtitle { font-family: 'Barlow', sans-serif; font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 2px; }
        .pay-instr-body { padding: 24px; }

        /* Amount due banner */
        .pay-amount-banner {
            display: flex; align-items: center; justify-content: space-between;
            background: #f8f9fb; border: 1px solid #e5e9ee;
            border-radius: 10px; padding: 14px 20px; margin-bottom: 20px;
        }
        .pay-amount-label { font-family: 'Barlow', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #888; }
        .pay-amount-val   { font-family: 'Barlow', sans-serif; font-size: 24px; font-weight: 900; color: #1a1a1a; }

        /* Bitcoin wallet copy box */
        .wallet-box {
            background: #0d0f13;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 16px;
        }
        .wallet-label {
            font-family: 'Barlow', sans-serif; font-size: 10px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.4);
            margin-bottom: 10px;
        }
        .wallet-row {
            display: flex; align-items: center; gap: 10px;
        }
        .wallet-addr {
            flex: 1; font-family: 'Courier New', monospace; font-size: 13px;
            color: #f7931a; word-break: break-all; line-height: 1.5;
            background: rgba(255,255,255,0.04);
            padding: 10px 12px; border-radius: 6px;
            border: 1px solid rgba(247,147,26,0.25);
        }
        .copy-btn {
            flex-shrink: 0;
            padding: 10px 18px;
            background: #f7931a;
            color: #fff;
            border: none; border-radius: 8px;
            font-family: 'Barlow', sans-serif; font-size: 12px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            cursor: pointer; transition: all 0.2s; white-space: nowrap;
        }
        .copy-btn:hover { background: #e8820a; transform: translateY(-1px); }
        .copy-btn.copied { background: #16a34a; }
        .wallet-note {
            font-size: 12px; color: #888; margin-top: 12px;
            font-family: 'Lato', sans-serif; line-height: 1.6;
        }

        /* Bank detail rows */
        .bank-row {
            display: flex; align-items: flex-start;
            padding: 12px 0; border-bottom: 1px solid #f0f2f5;
        }
        .bank-row:last-of-type { border-bottom: none; }
        .bank-row-lbl {
            width: 140px; flex-shrink: 0;
            font-family: 'Barlow', sans-serif; font-size: 11px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase; color: #aaa;
            padding-top: 1px;
        }
        .bank-row-val {
            font-family: 'Barlow', sans-serif; font-size: 14px;
            font-weight: 700; color: #1a1a1a;
        }
        .bank-ref {
            margin-top: 16px; padding: 12px 16px;
            background: #fffbe6; border: 1px solid #fde68a; border-radius: 8px;
            font-size: 13px; color: #92400e; line-height: 1.6;
        }

        /* PayPal */
        .pp-email-row {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px; background: #f8f9fb;
            border: 1px solid #e5e9ee; border-radius: 10px; margin-bottom: 16px;
        }
        .pp-email-row .lbl { font-family: 'Barlow', sans-serif; font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #aaa; flex-shrink: 0; }
        .pp-email-row .val { font-family: 'Lato', sans-serif; font-size: 14px; font-weight: 700; color: #1a1a1a; }
        .pp-pay-btn {
            display: block; width: 100%; padding: 15px;
            background: #0070ba; color: #fff; text-align: center;
            text-decoration: none; border-radius: 8px;
            font-family: 'Barlow', sans-serif; font-size: 14px; font-weight: 900;
            letter-spacing: 1.5px; text-transform: uppercase;
            transition: all 0.25s; margin-bottom: 12px;
        }
        .pp-pay-btn:hover { background: #005ea6; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,112,186,0.35); color: #fff; }
        .instr-note {
            font-size: 13px; color: #888; line-height: 1.7;
            font-family: 'Lato', sans-serif;
            padding: 12px 16px; background: #f8f9fb;
            border-radius: 8px; border-left: 3px solid #008cb2;
        }

        /* ── Confirmation card ── */
        .confirm-card {
            background: #fff; border-radius: 14px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid #e8ecf0; padding: 28px;
            margin-bottom: 24px;
        }
        .confirm-card-title {
            font-family: 'Barlow', sans-serif; font-size: 13px; font-weight: 900;
            letter-spacing: 2px; text-transform: uppercase; color: #1a1a1a;
            margin-bottom: 16px; padding-bottom: 12px;
            border-bottom: 1px solid #f0f2f5;
        }
        .next-steps { list-style: none; padding: 0; margin: 0; }
        .next-steps li {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f5f5f5;
            font-size: 14px; color: #555; font-family: 'Lato', sans-serif;
            line-height: 1.5;
        }
        .next-steps li:last-child { border-bottom: none; }
        .step-num {
            width: 24px; height: 24px; flex-shrink: 0;
            background: #008cb2; color: #fff; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Barlow', sans-serif; font-size: 11px; font-weight: 900;
            margin-top: 1px;
        }

        /* ── Action buttons ── */
        .success-actions {
            display: flex; gap: 14px; flex-wrap: wrap; justify-content: center;
            margin-top: 8px;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #008cb2, #00b4e0); color: #fff;
            padding: 14px 34px; border-radius: 8px;
            font-family: 'Barlow', sans-serif; font-size: 13px; font-weight: 900;
            letter-spacing: 2px; text-transform: uppercase; text-decoration: none;
            transition: all 0.25s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,140,178,0.35); color: #fff; }
        .btn-outline {
            display: inline-block;
            background: transparent; color: #555;
            padding: 14px 34px; border-radius: 8px; border: 1.5px solid #ccc;
            font-family: 'Barlow', sans-serif; font-size: 13px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase; text-decoration: none;
            transition: all 0.25s;
        }
        .btn-outline:hover { border-color: #008cb2; color: #008cb2; }

        .contact-note {
            text-align: center; margin-top: 24px;
            font-size: 13px; color: #999; font-family: 'Lato', sans-serif;
        }
        .contact-note a { color: #008cb2; text-decoration: none; }
    </style>
</head>
<body class="wp-theme-flatsome nav-dropdown-has-arrow nav-dropdown-has-shadow nav-dropdown-has-border">
<div id="wrapper">

    <?php require INCLUDES_PATH . '/header.php'; ?>

    <!-- Hero -->
    <div class="success-hero">
        <div class="success-check">&#10003;</div>
        <h1>Order Confirmed!</h1>
        <p class="order-ref">Order reference: <strong><?php echo htmlspecialchars($order_number); ?></strong></p>

        <div class="co-steps">
            <div class="co-step done"><span class="co-step-num">&#10003;</span><span>Cart</span></div>
            <div class="co-step-line"></div>
            <div class="co-step done"><span class="co-step-num">&#10003;</span><span>Details</span></div>
            <div class="co-step-line"></div>
            <div class="co-step active"><span class="co-step-num">3</span><span>Confirm</span></div>
        </div>
    </div>

    <main>
        <div class="success-wrap">

            <?php
            /* ════════════════════════════════════════
               PAYMENT INSTRUCTIONS
            ════════════════════════════════════════ */

            if ($method === 'bitcoin'): ?>

            <div class="pay-instructions">
                <div class="pay-instr-header btc">
                    <div class="pay-instr-icon">&#8383;</div>
                    <div>
                        <div class="pay-instr-title">Pay with Bitcoin</div>
                        <div class="pay-instr-subtitle">Send the exact amount to the wallet address below</div>
                    </div>
                </div>
                <div class="pay-instr-body">
                    <?php if ($order): ?>
                    <div class="pay-amount-banner">
                        <span class="pay-amount-label">Amount Due (USD)</span>
                        <span class="pay-amount-val"><?php echo format_price($order['total']); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if ($btc_wallet): ?>
                    <div class="wallet-box">
                        <div class="wallet-label">Bitcoin Wallet Address</div>
                        <div class="wallet-row">
                            <span class="wallet-addr" id="btc-wallet-addr"><?php echo htmlspecialchars($btc_wallet); ?></span>
                            <button class="copy-btn" id="copy-btn" onclick="copyWallet()">Copy</button>
                        </div>
                    </div>
                    <?php else: ?>
                    <p style="color:#888;font-size:14px;">Bitcoin wallet address will be sent to your email.</p>
                    <?php endif; ?>

                    <div class="instr-note">
                        Please include your order number <strong><?php echo htmlspecialchars($order_number); ?></strong> in the transaction memo/note. Bitcoin payments typically confirm within 10–60 minutes.
                    </div>
                </div>
            </div>

            <?php elseif ($method === 'bank'): ?>

            <div class="pay-instructions">
                <div class="pay-instr-header bank">
                    <div class="pay-instr-icon">&#127981;</div>
                    <div>
                        <div class="pay-instr-title">Bank Transfer Details</div>
                        <div class="pay-instr-subtitle">Transfer the order total to the account below</div>
                    </div>
                </div>
                <div class="pay-instr-body">
                    <?php if ($order): ?>
                    <div class="pay-amount-banner">
                        <span class="pay-amount-label">Amount Due</span>
                        <span class="pay-amount-val"><?php echo format_price($order['total']); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if ($bank_name):    ?><div class="bank-row"><span class="bank-row-lbl">Bank Name</span>      <span class="bank-row-val"><?php echo htmlspecialchars($bank_name);    ?></span></div><?php endif; ?>
                    <?php if ($bank_account): ?><div class="bank-row"><span class="bank-row-lbl">Account No.</span>    <span class="bank-row-val"><?php echo htmlspecialchars($bank_account); ?></span></div><?php endif; ?>
                    <?php if ($bank_routing): ?><div class="bank-row"><span class="bank-row-lbl">Routing No.</span>    <span class="bank-row-val"><?php echo htmlspecialchars($bank_routing); ?></span></div><?php endif; ?>
                    <?php if ($bank_details): ?><div class="bank-row"><span class="bank-row-lbl">Additional</span>     <span class="bank-row-val"><?php echo htmlspecialchars($bank_details); ?></span></div><?php endif; ?>

                    <div class="bank-ref">
                        &#9888; Please use <strong><?php echo htmlspecialchars($order_number); ?></strong> as the payment reference / memo so we can match your transfer.
                    </div>
                </div>
            </div>

            <?php elseif ($method === 'paypal'): ?>

            <div class="pay-instructions">
                <div class="pay-instr-header pp">
                    <div class="pay-instr-icon">P</div>
                    <div>
                        <div class="pay-instr-title">Pay with PayPal</div>
                        <div class="pay-instr-subtitle">Send payment to the PayPal address below</div>
                    </div>
                </div>
                <div class="pay-instr-body">
                    <?php if ($order): ?>
                    <div class="pay-amount-banner">
                        <span class="pay-amount-label">Amount Due</span>
                        <span class="pay-amount-val"><?php echo format_price($order['total']); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if ($pp_email): ?>
                    <div class="pp-email-row">
                        <span class="lbl">PayPal Email</span>
                        <span class="val"><?php echo htmlspecialchars($pp_email); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if ($pp_link): ?>
                    <a href="<?php echo htmlspecialchars($pp_link); ?>" target="_blank" rel="noopener" class="pp-pay-btn">Pay Now via PayPal &rarr;</a>
                    <?php endif; ?>

                    <div class="instr-note">
                        Please include your order number <strong><?php echo htmlspecialchars($order_number); ?></strong> in the PayPal payment note so we can identify your order.
                    </div>
                </div>
            </div>

            <?php else: /* email_transfer fallback */ ?>

            <div class="pay-instructions">
                <div class="pay-instr-header other">
                    <div class="pay-instr-icon">&#9993;</div>
                    <div>
                        <div class="pay-instr-title">Payment Details Coming Soon</div>
                        <div class="pay-instr-subtitle">We will contact you within 24 hours</div>
                    </div>
                </div>
                <div class="pay-instr-body">
                    <div class="instr-note">
                        Our team will reach out to you at your registered email address with payment options, shipping costs, and next steps within 24 hours.
                    </div>
                </div>
            </div>

            <?php endif; ?>

            <!-- What happens next -->
            <div class="confirm-card">
                <div class="confirm-card-title">What happens next?</div>
                <ul class="next-steps">
                    <li><span class="step-num">1</span> We review your order and confirm product availability</li>
                    <li><span class="step-num">2</span> Once payment is received we prepare your wheels for shipment</li>
                    <li><span class="step-num">3</span> We confirm shipping costs and dispatch your order</li>
                    <li><span class="step-num">4</span> You receive tracking information via email</li>
                </ul>
            </div>

            <div class="success-actions">
                <a href="<?php echo SITE_URL; ?>/" class="btn-primary">Back to Home</a>
                <a href="<?php echo SITE_URL; ?>/shop" class="btn-outline">Continue Shopping</a>
            </div>

            <p class="contact-note">
                Questions? Email us at <a href="mailto:<?php echo EMAIL_TO; ?>"><?php echo EMAIL_TO; ?></a>
            </p>

        </div><!-- .success-wrap -->
    </main>

    <?php require INCLUDES_PATH . '/footer.php'; ?>

</div><!-- #wrapper -->

<script src="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/css/../../../themes/flatsome/assets/js/flatsomed02f.js"></script>
<script>var siteUrl = '<?php echo SITE_URL; ?>';</script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

function copyWallet() {
    var addr = document.getElementById('btc-wallet-addr');
    var btn  = document.getElementById('copy-btn');
    if (!addr || !btn) return;

    var text = addr.textContent.trim();
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            btn.textContent = 'Copied!';
            btn.classList.add('copied');
            setTimeout(function() {
                btn.textContent = 'Copy';
                btn.classList.remove('copied');
            }, 2500);
        });
    } else {
        /* fallback for non-HTTPS */
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity  = '0';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(function() {
            btn.textContent = 'Copy';
            btn.classList.remove('copied');
        }, 2500);
    }
}
</script>
</body>
</html>
