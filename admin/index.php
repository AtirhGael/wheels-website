<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

require_admin_login();
$admin = get_current_admin();

$stats = [
    'products' => db_get_row("SELECT COUNT(*) as cnt FROM products")['cnt'],
    'orders'   => db_get_row("SELECT COUNT(*) as cnt FROM orders")['cnt'],
    'pending'  => db_get_row("SELECT COUNT(*) as cnt FROM orders WHERE status = 'pending'")['cnt'],
    'revenue'  => db_get_row("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE status != 'cancelled'")['total'],
];

$recent_orders = get_recent_orders(5);

$admin_page = 'dashboard';
$page_title = 'Dashboard';
require_once INCLUDES_PATH . '/admin_header.php';
?>

<!-- Page header -->
<div class="adm-page-header">
    <div>
        <div class="adm-page-title">Dashboard</div>
        <div class="adm-page-sub">Welcome back, <?php echo htmlspecialchars($admin['full_name'] ?? $admin['username']); ?></div>
    </div>
    <a href="<?php echo site_url('admin/orders.php'); ?>" class="adm-btn primary">View All Orders</a>
</div>

<!-- Stat cards -->
<div class="dash-stats">
    <div class="dash-stat-card">
        <div class="dash-stat-icon products">&#9679;</div>
        <div class="dash-stat-body">
            <div class="dash-stat-label">Total Products</div>
            <div class="dash-stat-value"><?php echo $stats['products']; ?></div>
        </div>
    </div>
    <div class="dash-stat-card">
        <div class="dash-stat-icon orders">&#128220;</div>
        <div class="dash-stat-body">
            <div class="dash-stat-label">Total Orders</div>
            <div class="dash-stat-value"><?php echo $stats['orders']; ?></div>
        </div>
    </div>
    <div class="dash-stat-card">
        <div class="dash-stat-icon pending">&#8987;</div>
        <div class="dash-stat-body">
            <div class="dash-stat-label">Pending Orders</div>
            <div class="dash-stat-value"><?php echo $stats['pending']; ?></div>
        </div>
    </div>
    <div class="dash-stat-card">
        <div class="dash-stat-icon revenue">&#36;</div>
        <div class="dash-stat-body">
            <div class="dash-stat-label">Total Revenue</div>
            <div class="dash-stat-value"><?php echo format_price($stats['revenue']); ?></div>
        </div>
    </div>
</div>

<!-- Recent orders table -->
<div class="adm-card" style="margin-top: 28px;">
    <div class="adm-card-header">
        <span class="adm-card-title">Recent Orders</span>
        <a href="<?php echo site_url('admin/orders.php'); ?>" class="adm-btn ghost" style="font-size:11px;padding:6px 14px;">See All</a>
    </div>
    <table class="adm-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recent_orders)): ?>
                <tr><td colspan="6" style="text-align:center;color:#aaa;padding:32px;">No orders yet.</td></tr>
            <?php else: ?>
                <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><strong style="font-family:'Barlow',sans-serif;font-size:13px;"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td style="font-weight:700;"><?php echo format_price($order['total']); ?></td>
                        <td><span class="adm-badge <?php echo htmlspecialchars($order['status']); ?>"><?php echo ucfirst(htmlspecialchars($order['status'])); ?></span></td>
                        <td style="color:#888;"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td><a href="<?php echo site_url('admin/orders.php?view=' . $order['id']); ?>" class="adm-btn ghost" style="font-size:11px;padding:5px 12px;">View</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
/* Stat cards */
.dash-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 1100px) { .dash-stats { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .dash-stats { grid-template-columns: 1fr; } }

.dash-stat-card {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: 12px;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
}
.dash-stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.dash-stat-icon.products { background: rgba(0,140,178,0.1);  color: #008cb2; }
.dash-stat-icon.orders   { background: rgba(124,58,237,0.1); color: #7c3aed; }
.dash-stat-icon.pending  { background: rgba(245,158,11,0.1); color: #d97706; }
.dash-stat-icon.revenue  { background: rgba(16,185,129,0.1); color: #059669; }

.dash-stat-label {
    font-family: 'Barlow', sans-serif;
    font-size: 11px; font-weight: 800;
    letter-spacing: 1px; text-transform: uppercase;
    color: #aaa; margin-bottom: 6px;
}
.dash-stat-value {
    font-family: 'Barlow', sans-serif;
    font-size: 28px; font-weight: 900;
    color: #1a1a1a; line-height: 1;
}
</style>

<?php require_once INCLUDES_PATH . '/admin_footer.php'; ?>
