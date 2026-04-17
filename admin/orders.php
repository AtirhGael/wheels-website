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

$admin_page = 'orders';
$page_title = 'Orders';
require_once INCLUDES_PATH . '/admin_header.php';
?>

<div class="adm-page-header">
    <div>
        <div class="adm-page-title">Orders</div>
        <div class="adm-page-sub">Manage customer orders</div>
    </div>
</div>

<?php if ($success): ?>
    <div class="adm-alert success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-header">
        <span class="adm-card-title">All Orders</span>
        <span style="font-size:12px;color:#aaa;"><?php echo $total_orders; ?> total</span>
    </div>
    <table class="adm-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="7" style="text-align:center;color:#aaa;padding:32px;">No orders yet.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong style="font-family:'Barlow',sans-serif;"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td style="color:#888;"><?php echo htmlspecialchars($order['customer_email']); ?></td>
                        <td style="font-weight:700;"><?php echo format_price($order['total']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="update_status" value="1">
                                <select name="status" onchange="this.form.submit()" style="padding:5px 8px;border:1.5px solid #dde1e7;border-radius:6px;font-size:12px;font-family:'Barlow',sans-serif;font-weight:700;cursor:pointer;background:#fff;">
                                    <?php foreach (['pending','processing','shipped','completed','cancelled'] as $s): ?>
                                        <option value="<?php echo $s; ?>" <?php echo $order['status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td style="color:#888;"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td style="white-space:nowrap;">
                            <a href="#" onclick="viewOrder(<?php echo $order['id']; ?>)" class="adm-btn ghost" style="font-size:11px;padding:5px 12px;margin-right:6px;">View</a>
                            <a href="?delete=<?php echo $order['id']; ?>" class="adm-btn danger" style="font-size:11px;padding:5px 12px;" onclick="return confirm('Delete this order?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($total_pages > 1): ?>
<div style="margin-top:20px;display:flex;align-items:center;gap:12px;">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="adm-btn ghost">&#8592; Prev</a>
    <?php endif; ?>
    <span style="font-size:13px;color:#888;">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="adm-btn ghost">Next &#8594;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Order detail modal -->
<div id="orderModal" style="display:none;position:fixed;z-index:2000;inset:0;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px;width:90%;max-width:560px;max-height:80vh;overflow-y:auto;position:relative;">
        <button onclick="document.getElementById('orderModal').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:#999;">&times;</button>
        <div id="orderDetails"></div>
    </div>
</div>

<script>
function viewOrder(id) {
    fetch('<?php echo site_url('admin/order_ajax.php'); ?>?id=' + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById('orderDetails').innerHTML = html;
            document.getElementById('orderModal').style.display = 'flex';
        });
}
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>

<?php require_once INCLUDES_PATH . '/admin_footer.php'; ?>