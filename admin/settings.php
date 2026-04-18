<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

require_admin_login();
$admin = get_current_admin();

$settings = get_site_settings();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit_general'])) {
        $updates = [
            'site_name'       => sanitize($_POST['site_name']       ?? ''),
            'site_email'      => sanitize($_POST['site_email']       ?? ''),
            'contact_phone'   => sanitize($_POST['contact_phone']    ?? ''),
            'contact_address' => sanitize($_POST['contact_address']  ?? ''),
            'business_hours'  => sanitize($_POST['business_hours']   ?? ''),
        ];
    } else {
        $updates = [
            'payment_bitcoin_enabled' => isset($_POST['payment_bitcoin_enabled']) ? '1' : '0',
            'payment_bitcoin_wallet'  => sanitize($_POST['payment_bitcoin_wallet']  ?? ''),
            'payment_bank_enabled'    => isset($_POST['payment_bank_enabled'])    ? '1' : '0',
            'payment_bank_name'       => sanitize($_POST['payment_bank_name']       ?? ''),
            'payment_bank_account'    => sanitize($_POST['payment_bank_account']    ?? ''),
            'payment_bank_routing'    => sanitize($_POST['payment_bank_routing']    ?? ''),
            'payment_bank_details'    => sanitize($_POST['payment_bank_details']    ?? ''),
            'payment_paypal_enabled'  => isset($_POST['payment_paypal_enabled'])  ? '1' : '0',
            'payment_paypal_email'    => sanitize($_POST['payment_paypal_email']    ?? ''),
            'payment_paypal_link'     => sanitize($_POST['payment_paypal_link']     ?? ''),
        ];
    }

    foreach ($updates as $key => $value) {
        update_site_setting($key, $value);
    }
    $success = 'Settings saved successfully.';
    $settings = get_site_settings();
}

$admin_page = 'settings';
$page_title = 'Settings';
require_once INCLUDES_PATH . '/admin_header.php';
?>

<div class="adm-page-header">
    <div>
        <div class="adm-page-title">Settings</div>
        <div class="adm-page-sub">Manage site info and payment methods</div>
    </div>
</div>

<?php if ($success): ?>
    <div class="adm-alert success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="adm-alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
        
        <form method="POST" class="settings-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="<?= sanitize($settings['site_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="site_email">Contact Email</label>
                    <input type="email" id="site_email" name="site_email" value="<?= sanitize($settings['site_email'] ?? '') ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contact_phone">Phone Number</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="<?= sanitize($settings['contact_phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="business_hours">Business Hours</label>
                    <input type="text" id="business_hours" name="business_hours" value="<?= sanitize($settings['business_hours'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="contact_address">Address</label>
                <textarea id="contact_address" name="contact_address" rows="3"><?= sanitize($settings['contact_address'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" name="submit_general" class="adm-btn primary">Save Settings</button>
        </form>

        <!-- ── Payment Methods ── -->
        <div style="font-family:'Barlow',sans-serif;font-size:13px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#aaa;margin:32px 0 16px;">Payment Methods</div>

        <form method="POST" class="settings-form">
            <!-- Bitcoin -->
            <div class="payment-card">
                <div class="payment-card-header">
                    <div class="payment-card-left">
                        <span class="payment-icon btc">&#8383;</span>
                        <div>
                            <strong>Bitcoin</strong>
                            <span class="payment-badge">10% discount for customers</span>
                        </div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="payment_bitcoin_enabled" value="1"
                            <?= ($settings['payment_bitcoin_enabled'] ?? '0') ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="payment-card-body">
                    <div class="form-group">
                        <label>Bitcoin Wallet Address</label>
                        <input type="text" name="payment_bitcoin_wallet"
                               value="<?= htmlspecialchars($settings['payment_bitcoin_wallet'] ?? '') ?>"
                               placeholder="bc1q... or 1A1zP1...">
                    </div>
                </div>
            </div>

            <!-- Bank Transfer -->
            <div class="payment-card">
                <div class="payment-card-header">
                    <div class="payment-card-left">
                        <span class="payment-icon bank">&#127981;</span>
                        <div><strong>Bank Transfer</strong></div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="payment_bank_enabled" value="1"
                            <?= ($settings['payment_bank_enabled'] ?? '0') ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="payment-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Bank Name</label>
                            <input type="text" name="payment_bank_name"
                                   value="<?= htmlspecialchars($settings['payment_bank_name'] ?? '') ?>"
                                   placeholder="e.g. Chase Bank">
                        </div>
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" name="payment_bank_account"
                                   value="<?= htmlspecialchars($settings['payment_bank_account'] ?? '') ?>"
                                   placeholder="000123456789">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Routing Number</label>
                            <input type="text" name="payment_bank_routing"
                                   value="<?= htmlspecialchars($settings['payment_bank_routing'] ?? '') ?>"
                                   placeholder="021000021">
                        </div>
                        <div class="form-group">
                            <label>Additional Details</label>
                            <input type="text" name="payment_bank_details"
                                   value="<?= htmlspecialchars($settings['payment_bank_details'] ?? '') ?>"
                                   placeholder="SWIFT, IBAN, branch info...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="payment-card">
                <div class="payment-card-header">
                    <div class="payment-card-left">
                        <span class="payment-icon paypal">&#80;</span>
                        <div><strong>PayPal</strong></div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="payment_paypal_enabled" value="1"
                            <?= ($settings['payment_paypal_enabled'] ?? '0') ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="payment-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>PayPal Email</label>
                            <input type="email" name="payment_paypal_email"
                                   value="<?= htmlspecialchars($settings['payment_paypal_email'] ?? '') ?>"
                                   placeholder="payments@yourdomain.com">
                        </div>
                        <div class="form-group">
                            <label>PayPal.me Link <span style="font-weight:400;color:#999;">(optional)</span></label>
                            <input type="url" name="payment_paypal_link"
                                   value="<?= htmlspecialchars($settings['payment_paypal_link'] ?? '') ?>"
                                   placeholder="https://paypal.me/yourname">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit_payment" class="adm-btn primary" style="margin-top:10px;">Save Payment Settings</button>
        </form>

<style>
.settings-form {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: 12px;
    padding: 28px;
    max-width: 800px;
    margin-bottom: 24px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    display: block;
    margin-bottom: 7px;
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #888;
}
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #dde1e7;
    border-radius: 7px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}
.form-group input:focus,
.form-group textarea:focus {
    border-color: #008cb2;
    box-shadow: 0 0 0 3px rgba(0,140,178,0.1);
}

/* ── Payment settings ── */
.settings-section-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 40px 0 16px;
}
.payment-card {
    background: #fff;
    border: 1px solid #e5e9ee;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 16px;
    max-width: 800px;
}
.payment-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #f8f9fb;
    border-bottom: 1px solid #eef0f3;
}
.payment-card-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.payment-icon {
    width: 38px; height: 38px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 900; color: #fff;
}
.payment-icon.btc  { background: #f7931a; }
.payment-icon.bank { background: #1a6b3e; }
.payment-icon.paypal { background: #003087; }
.payment-card-left strong { font-size: 15px; color: #1a1a1a; display: block; }
.payment-badge {
    display: inline-block;
    background: #e8f8f0;
    color: #1a6b3e;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 10px;
    margin-top: 2px;
}
.payment-card-body {
    padding: 18px 20px;
}

/* Toggle switch */
.toggle-switch { position: relative; display: inline-block; width: 46px; height: 26px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; cursor: pointer;
    inset: 0; background: #ccc; border-radius: 26px;
    transition: .3s;
}
.toggle-slider::before {
    content: ''; position: absolute;
    width: 20px; height: 20px; left: 3px; bottom: 3px;
    background: #fff; border-radius: 50%; transition: .3s;
}
.toggle-switch input:checked + .toggle-slider { background: #e63946; }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }
</style>

<?php require_once INCLUDES_PATH . '/admin_footer.php'; ?>