<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

require_admin_login();
$admin = get_current_admin();

$stats = [
    'products' => db_get_row("SELECT COUNT(*) as cnt FROM products")['cnt'],
    'orders' => db_get_row("SELECT COUNT(*) as cnt FROM orders")['cnt'],
    'pending' => db_get_row("SELECT COUNT(*) as cnt FROM orders WHERE status = 'pending'")['cnt'],
    'revenue' => db_get_row("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE status != 'cancelled'")['total']
];

$recent_orders = get_recent_orders(5);

require_once INCLUDES_PATH . '/header.php';
?>
<div class="admin-container">
    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <a href="<?= site_url('admin/') ?>" class="active">Dashboard</a>
            <a href="<?= site_url('admin/products.php') ?>">Products</a>
            <a href="<?= site_url('admin/orders.php') ?>">Orders</a>
            <a href="<?= site_url('admin/settings.php') ?>">Settings</a>
            <a href="<?= site_url('admin/logout.php') ?>">Logout</a>
        </nav>
    </aside>
    
    <main class="admin-main">
        <header class="admin-header">
            <h1>Dashboard</h1>
            <p>Welcome, <?= sanitize($admin['full_name'] ?? $admin['username']) ?></p>
        </header>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Products</h3>
                <p class="stat-number"><?= $stats['products'] ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p class="stat-number"><?= $stats['orders'] ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Orders</h3>
                <p class="stat-number"><?= $stats['pending'] ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number"><?= format_price($stats['revenue']) ?></p>
            </div>
        </div>
        
        <section class="recent-orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_orders)): ?>
                        <tr><td colspan="6">No orders yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?= sanitize($order['order_number']) ?></td>
                                <td><?= sanitize($order['customer_name']) ?></td>
                                <td><?= format_price($order['total']) ?></td>
                                <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td><a href="<?= site_url('admin/orders.php?view=' . $order['id']) ?>">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<style>
.admin-container {
    display: flex;
    min-height: 100vh;
}
.admin-sidebar {
    width: 250px;
    background: #1a1a1a;
    padding: 20px;
    color: #fff;
}
.admin-sidebar h2 {
    font-size: 20px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #333;
}
.admin-sidebar nav a {
    display: block;
    padding: 12px 15px;
    color: #aaa;
    text-decoration: none;
    border-radius: 4px;
    margin-bottom: 5px;
}
.admin-sidebar nav a:hover,
.admin-sidebar nav a.active {
    background: #e63946;
    color: #fff;
}
.admin-main {
    flex: 1;
    padding: 30px;
    background: #f5f5f5;
}
.admin-header {
    margin-bottom: 30px;
}
.admin-header h1 {
    font-size: 28px;
    margin-bottom: 5px;
}
.admin-header p {
    color: #666;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.stat-card {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.stat-card h3 {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}
.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
}
.recent-orders {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.recent-orders h2 {
    font-size: 20px;
    margin-bottom: 20px;
}
.recent-orders table {
    width: 100%;
    border-collapse: collapse;
}
.recent-orders th,
.recent-orders td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.recent-orders th {
    font-weight: 600;
    color: #666;
    font-size: 13px;
}
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d4edda; color: #155724; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.recent-orders a {
    color: #e63946;
}
</style>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>