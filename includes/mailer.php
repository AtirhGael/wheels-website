<?php
/**
 * Central SMTP mailer service — Elite BBS Rims
 * Wraps PHPMailer with Hostinger SMTP credentials.
 * All outgoing mail (orders, contact form) routes through this file.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

require_once BASE_PATH . '/vendor/autoload.php';

/**
 * Send an HTML email via Hostinger SMTP.
 *
 * @param string $to        Recipient email address
 * @param string $subject   Email subject line
 * @param string $html_body Full HTML body
 * @param string $reply_to  Optional Reply-To address (e.g. customer's email)
 * @return bool  true on success, false on failure (error logged)
 */
function send_mail(string $to, string $subject, string $html_body, string $reply_to = ''): bool
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;      // smtp.hostinger.com
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;      // info@elitebbswheels.store
        $mail->Password   = SMTP_PASS;      // Bamenda@2026
        $mail->SMTPSecure = SMTP_SECURE;    // 'ssl'
        $mail->Port       = SMTP_PORT;      // 465

        // Sender
        $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);

        // Recipient
        $mail->addAddress($to);

        // Reply-To (e.g. customer email so admin can reply directly)
        if ($reply_to && filter_var($reply_to, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($reply_to);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html_body));
        $mail->CharSet = 'UTF-8';

        $mail->send();
        return true;
    } catch (MailerException $e) {
        error_log('[Elite BBS Mailer] Failed to send "' . $subject . '" to ' . $to . ': ' . $mail->ErrorInfo);
        return false;
    }
}


/**
 * Build and send the order-received notification to the admin.
 *
 * @param string $order_number
 * @param array  $customer     POST data with customer_name, customer_email, etc.
 * @param array  $items        Cart items array
 * @param float  $total        Grand total charged
 * @param float  $discount     Coupon discount amount
 * @param array|null $coupon   Active coupon array (needs 'code' key)
 * @param float  $btc_discount Bitcoin 10% discount amount
 * @param string $payment_method
 * @return bool
 */
function send_order_notification(
    string $order_number,
    array  $customer,
    array  $items,
    float  $total,
    float  $discount     = 0,
    ?array $coupon       = null,
    float  $btc_discount = 0,
    string $payment_method = ''
): bool {
    $admin_email = get_site_setting('site_email') ?: EMAIL_TO;
    $subject     = 'New Order ' . $order_number . ' — ' . SITE_NAME;

    $method_labels = [
        'bitcoin'       => '&#8383; Bitcoin',
        'bank'          => '&#127981; Bank Transfer',
        'paypal'        => 'PayPal',
        'email_transfer'=> 'To be arranged',
    ];
    $method_label = $method_labels[$payment_method] ?? htmlspecialchars($payment_method);

    // ── Items rows ────────────────────────────────────────────────────────────
    $items_html = '';
    foreach ($items as $item) {
        $items_html .= '
        <tr>
            <td style="padding:10px 16px;border-bottom:1px solid #f0f0f0;font-size:14px;color:#333;">'
                . htmlspecialchars($item['name'])
                . '<br><span style="font-size:12px;color:#999;">SKU: ' . htmlspecialchars($item['sku'] ?: 'N/A') . '</span></td>
            <td style="padding:10px 16px;border-bottom:1px solid #f0f0f0;font-size:14px;color:#555;text-align:center;">'
                . (int)$item['quantity'] . '</td>
            <td style="padding:10px 16px;border-bottom:1px solid #f0f0f0;font-size:14px;color:#555;text-align:right;">$'
                . number_format($item['price'], 2) . '</td>
            <td style="padding:10px 16px;border-bottom:1px solid #f0f0f0;font-size:14px;font-weight:700;color:#1a1a1a;text-align:right;">$'
                . number_format($item['total'], 2) . '</td>
        </tr>';
    }

    // ── Discount rows ─────────────────────────────────────────────────────────
    $discount_html = '';
    if ($discount > 0 && $coupon) {
        $discount_html .= '<tr><td colspan="3" style="padding:8px 16px;text-align:right;font-size:13px;color:#555;">Coupon (' . htmlspecialchars($coupon['code']) . ')</td><td style="padding:8px 16px;text-align:right;font-size:13px;color:#e63946;">-$' . number_format($discount, 2) . '</td></tr>';
    }
    if ($btc_discount > 0) {
        $discount_html .= '<tr><td colspan="3" style="padding:8px 16px;text-align:right;font-size:13px;color:#555;">Bitcoin discount (10%)</td><td style="padding:8px 16px;text-align:right;font-size:13px;color:#f7931a;">-$' . number_format($btc_discount, 2) . '</td></tr>';
    }

    // ── Addresses ─────────────────────────────────────────────────────────────
    $addr_html = '';
    if (!empty($customer['billing_address'])) {
        $addr_html .= '<tr><td style="padding:6px 0;font-size:13px;color:#888;width:120px;">Billing</td><td style="padding:6px 0;font-size:13px;color:#333;">' . nl2br(htmlspecialchars($customer['billing_address'])) . '</td></tr>';
    }
    if (!empty($customer['shipping_address'])) {
        $addr_html .= '<tr><td style="padding:6px 0;font-size:13px;color:#888;">Shipping</td><td style="padding:6px 0;font-size:13px;color:#333;">' . nl2br(htmlspecialchars($customer['shipping_address'])) . '</td></tr>';
    }

    // ── Notes ─────────────────────────────────────────────────────────────────
    $notes_html = '';
    if (!empty($customer['notes'])) {
        $notes_html = '
        <div style="margin:24px 32px 0;padding:16px 20px;background:#fffbe6;border-left:4px solid #f7c948;border-radius:4px;">
            <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#888;">Customer Notes</p>
            <p style="margin:0;font-size:14px;color:#555;">' . nl2br(htmlspecialchars($customer['notes'])) . '</p>
        </div>';
    }

    // ── Full HTML email ───────────────────────────────────────────────────────
    $html = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">

<div style="max-width:620px;margin:32px auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.07);">

    <!-- Header -->
    <div style="background:linear-gradient(135deg,#0d0f13,#1a2030);padding:28px 32px;text-align:center;">
        <p style="margin:0 0 4px;font-size:11px;font-weight:700;letter-spacing:4px;color:rgba(255,255,255,0.4);text-transform:uppercase;">Elite BBS</p>
        <h1 style="margin:0;font-size:22px;font-weight:900;color:#fff;letter-spacing:1px;text-transform:uppercase;">New Order Received</h1>
        <p style="margin:8px 0 0;font-size:18px;font-weight:700;color:#00b4e0;">' . htmlspecialchars($order_number) . '</p>
    </div>

    <!-- Order meta bar -->
    <div style="background:#f8f9fb;border-bottom:1px solid #eef0f3;padding:14px 32px;display:flex;">
        <table width="100%" cellpadding="0" cellspacing="0"><tr>
            <td style="font-size:12px;color:#999;"><strong style="color:#555;">Date</strong><br>' . date('M j, Y — g:i A T') . '</td>
            <td style="font-size:12px;color:#999;text-align:center;"><strong style="color:#555;">Payment</strong><br>' . $method_label . '</td>
            <td style="font-size:12px;color:#999;text-align:right;"><strong style="color:#555;">Status</strong><br><span style="color:#d97706;font-weight:700;">Pending</span></td>
        </tr></table>
    </div>

    <!-- Customer info -->
    <div style="padding:24px 32px 0;">
        <p style="margin:0 0 12px;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#aaa;">Customer</p>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding:4px 0;font-size:13px;color:#888;width:80px;">Name</td>
                <td style="padding:4px 0;font-size:14px;color:#1a1a1a;font-weight:700;">' . htmlspecialchars($customer['customer_name']) . '</td>
            </tr>
            <tr>
                <td style="padding:4px 0;font-size:13px;color:#888;">Email</td>
                <td style="padding:4px 0;font-size:14px;color:#008cb2;"><a href="mailto:' . htmlspecialchars($customer['customer_email']) . '" style="color:#008cb2;">' . htmlspecialchars($customer['customer_email']) . '</a></td>
            </tr>
            <tr>
                <td style="padding:4px 0;font-size:13px;color:#888;">Phone</td>
                <td style="padding:4px 0;font-size:14px;color:#333;">' . htmlspecialchars($customer['customer_phone']) . '</td>
            </tr>
            ' . $addr_html . '
        </table>
    </div>

    <!-- Items -->
    <div style="padding:24px 32px 0;">
        <p style="margin:0 0 12px;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#aaa;">Order Items</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #eef0f3;border-radius:8px;overflow:hidden;">
            <thead>
                <tr style="background:#f8f9fb;">
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#999;border-bottom:1px solid #eef0f3;">Product</th>
                    <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#999;border-bottom:1px solid #eef0f3;">Qty</th>
                    <th style="padding:10px 16px;text-align:right;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#999;border-bottom:1px solid #eef0f3;">Unit</th>
                    <th style="padding:10px 16px;text-align:right;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#999;border-bottom:1px solid #eef0f3;">Total</th>
                </tr>
            </thead>
            <tbody>' . $items_html . '</tbody>
            <tfoot>
                ' . $discount_html . '
                <tr style="background:#f8f9fb;">
                    <td colspan="3" style="padding:12px 16px;text-align:right;font-size:14px;font-weight:900;color:#1a1a1a;letter-spacing:0.5px;">Grand Total</td>
                    <td style="padding:12px 16px;text-align:right;font-size:18px;font-weight:900;color:#008cb2;">$' . number_format($total, 2) . '</td>
                </tr>
            </tfoot>
        </table>
    </div>

    ' . $notes_html . '

    <!-- CTA -->
    <div style="padding:24px 32px 32px;text-align:center;">
        <a href="' . site_url('admin/orders.php') . '" style="display:inline-block;padding:13px 32px;background:linear-gradient(135deg,#008cb2,#00b4e0);color:#fff;text-decoration:none;border-radius:7px;font-size:13px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">View in Admin Panel</a>
    </div>

    <!-- Footer -->
    <div style="background:#f8f9fb;border-top:1px solid #eef0f3;padding:16px 32px;text-align:center;">
        <p style="margin:0;font-size:12px;color:#bbb;">' . SITE_NAME . ' &mdash; Automated Order Notification</p>
    </div>

</div>
</body>
</html>';

    return send_mail($admin_email, $subject, $html, $customer['customer_email'] ?? '');
}


/**
 * Build and send the contact-form notification to the admin.
 *
 * @param string $name
 * @param string $email
 * @param string $phone
 * @param string $subject_key  The subject dropdown value (e.g. 'product_inquiry')
 * @param string $message
 * @return bool
 */
function send_contact_notification(
    string $name,
    string $email,
    string $phone,
    string $subject_key,
    string $message
): bool {
    $admin_email = get_site_setting('site_email') ?: EMAIL_TO;

    $subject_labels = [
        'product_inquiry' => 'Product Inquiry',
        'fitment_help'    => 'Fitment Help',
        'order_status'    => 'Order Status',
        'general'         => 'General Question',
    ];
    $subject_label = $subject_labels[$subject_key] ?? ($subject_key ?: 'General Inquiry');
    $subject       = '[Contact] ' . $subject_label . ' — ' . htmlspecialchars($name);

    $phone_row = $phone
        ? '<tr><td style="padding:6px 0;font-size:13px;color:#888;width:80px;">Phone</td><td style="padding:6px 0;font-size:14px;color:#333;">' . htmlspecialchars($phone) . '</td></tr>'
        : '';

    $html = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">

<div style="max-width:580px;margin:32px auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.07);">

    <!-- Header -->
    <div style="background:linear-gradient(135deg,#0d0f13,#1a2030);padding:24px 32px;">
        <p style="margin:0 0 3px;font-size:10px;font-weight:700;letter-spacing:4px;color:rgba(255,255,255,0.35);text-transform:uppercase;">Elite BBS</p>
        <h1 style="margin:0;font-size:20px;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:1px;">Contact Form Message</h1>
        <p style="margin:6px 0 0;font-size:13px;color:#00b4e0;font-weight:700;">' . htmlspecialchars($subject_label) . '</p>
    </div>

    <!-- Sender info -->
    <div style="padding:24px 32px 0;">
        <p style="margin:0 0 12px;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#aaa;">From</p>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#888;width:80px;">Name</td>
                <td style="padding:6px 0;font-size:14px;color:#1a1a1a;font-weight:700;">' . htmlspecialchars($name) . '</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#888;">Email</td>
                <td style="padding:6px 0;font-size:14px;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#008cb2;">' . htmlspecialchars($email) . '</a></td>
            </tr>
            ' . $phone_row . '
        </table>
    </div>

    <!-- Message -->
    <div style="padding:24px 32px;">
        <p style="margin:0 0 12px;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#aaa;">Message</p>
        <div style="background:#f8f9fb;border:1px solid #eef0f3;border-radius:8px;padding:18px 20px;font-size:14px;color:#444;line-height:1.7;">'
            . nl2br(htmlspecialchars($message)) . '
        </div>
    </div>

    <!-- Reply CTA -->
    <div style="padding:0 32px 32px;text-align:center;">
        <a href="mailto:' . htmlspecialchars($email) . '" style="display:inline-block;padding:13px 32px;background:linear-gradient(135deg,#008cb2,#00b4e0);color:#fff;text-decoration:none;border-radius:7px;font-size:13px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Reply to ' . htmlspecialchars($name) . '</a>
    </div>

    <!-- Footer -->
    <div style="background:#f8f9fb;border-top:1px solid #eef0f3;padding:14px 32px;text-align:center;">
        <p style="margin:0;font-size:12px;color:#bbb;">' . SITE_NAME . ' &mdash; Contact Form Notification &mdash; ' . date('M j, Y g:i A') . '</p>
    </div>

</div>
</body>
</html>';

    return send_mail($admin_email, $subject, $html, $email);
}
