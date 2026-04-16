<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

require_admin_login();
$admin = get_current_admin();

$success = '';
$error = '';

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $order_id = (int)$_GET['delete'];
    db_query("DELETE FROM orders WHERE id = :id", [':id' => $order_id]);
    $success = 'Order deleted successfully.';
}

if (isset($_POST['update_status']) && is_numeric($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    update_order_status($order_id, $status);
    $success = 'Order status updated.';
}

$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$total_orders = db_get_row("SELECT COUNT(*) as cnt FROM orders")['cnt'];
$orders = get_all_orders($per_page, $offset);
$total_pages = ceil($total_orders / $per_page);

require_once INCLUDES_PATH . '/header.php';
?>
<div class="admin-container">
    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <a href="<?= site_url('admin/') ?>">Dashboard</a>
            <a href="<?= site_url('admin/products.php') ?>">Products</a>
            <a href="<?= site_url('admin/orders.php') ?>" class="active">Orders</a>
            <a href="<?= site_url('admin/settings.php') ?>">Settings</a>
            <a href="<?= site_url('admin/logout.php') ?>">Logout</a>
        </nav>
    </aside>
    
    <main class="admin-main">
        <header class="admin-header">
            <h1>Orders</h1>
            <p>Manage customer orders</p>
        </header>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Vehicle</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="8">No orders yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= sanitize($order['order_number']) ?></td>
                                <td><?= sanitize($order['customer_name']) ?></td>
                                <td><?= sanitize($order['customer_email']) ?></td>
                                <td><?= sanitize($order['vehicle_year'] . ' ' . $order['vehicle_make'] . ' ' . $order['vehicle_model']) ?></td>
                                <td><?= format_price($order['total']) ?></td>
                                <td>
                                    <form method="POST" class="status-form">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="status-select">
                                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="#" onclick="viewOrder(<?= $order['id'] ?>)" class="btn-view">View</a>
                                    <a href="?delete=<?= $order['id'] ?>" class="btn-delete" onclick="return confirm('Delete this order?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">Previous</a>
            <?php endif; ?>
            <span>Page <?= $page ?> of <?= $total_pages ?></span>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div id="orderDetails"></div>
    </div>
</div>

<style>
.admin-container { display: flex; min-height: 100vh; }
.admin-sidebar { width: 250px; background: #1a1a1a; padding: 20px; color: #fff; }
.admin-sidebar h2 { font-size: 20px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #333; }
.admin-sidebar nav a { display: block; padding: 12px 15px; color: #aaa; text-decoration: none; border-radius: 4px; margin-bottom: 5px; }
.admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: #e63946; color: #fff; }
.admin-main { flex: 1; padding: 30px; background: #f5f5f5; }
.admin-header { margin-bottom: 30px; }
.admin-header h1 { font-size: 28px; margin-bottom: 5px; }
.admin-header p { color: #666; }
.alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; }
.alert-success { background: #d4edda; color: #155724; }
.orders-table { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.orders-table table { width: 100%; border-collapse: collapse; }
.orders-table th, .orders-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
.orders-table th { font-weight: 600; color: #666; font-size: 13px; background: #fafafa; }
.status-form { display: inline; }
.status-select { padding: 6px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px; cursor: pointer; }
.btn-view, .btn-delete { margin-right: 10px; color: #e63946; font-size: 13px; }
.btn-delete { color: #dc3545; }
.pagination { margin-top: 20px; }
.pagination a { padding: 8px 16px; background: #e63946; color: #fff; text-decoration: none; border-radius: 4px; margin-right: 10px; }
.pagination span { color: #666; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.modal-content { background: #fff; margin: 5% auto; padding: 30px; border-radius: 8px; width: 90%; max-width: 600px; }
.modal-close { float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.modal-close:hover { color: #e63946; }
</style>

<script>
function viewOrder(id) {
    fetch('<?= site_url('admin/order_ajax.php') ?>?id=' + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById('orderDetails').innerHTML = html;
            document.getElementById('orderModal').style.display = 'block';
        });
}
document.querySelector('.modal-close').onclick = () => document.getElementById('orderModal').style.display = 'none';
window.onclick = e => { if (e.target.id === 'orderModal') document.getElementById('orderModal').style.display = 'none'; };
</script>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>