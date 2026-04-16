<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

header('Content-Type: text/html; charset=utf-8');

$order_id = (int)($_GET['id'] ?? 0);
if (!$order_id) {
    echo '<p>Order not found.</p>';
    return;
}

$order = db_get_row("SELECT * FROM orders WHERE id = :id", [':id' => $order_id]);
if (!$order) {
    echo '<p>Order not found.</p>';
    return;
}

$items = json_decode($order['items_json'], true) ?? [];
?>
<h2>Order #<?= sanitize($order['order_number']) ?></h2>

<div class="order-info">
    <div class="info-group">
        <label>Customer:</label>
        <span><?= sanitize($order['customer_name']) ?></span>
    </div>
    <div class="info-group">
        <label>Email:</label>
        <span><?= sanitize($order['customer_email']) ?></span>
    </div>
    <div class="info-group">
        <label>Phone:</label>
        <span><?= sanitize($order['customer_phone'] ?? 'N/A') ?></span>
    </div>
    <div class="info-group">
        <label>Vehicle:</label>
        <span><?= sanitize($order['vehicle_year'] . ' ' . $order['vehicle_make'] . ' ' . $order['vehicle_model']) ?></span>
    </div>
    <div class="info-group">
        <label>Billing Address:</label>
        <span><?= nl2br(sanitize($order['billing_address'])) ?></span>
    </div>
    <div class="info-group">
        <label>Shipping Address:</label>
        <span><?= nl2br(sanitize($order['shipping_address'])) ?></span>
    </div>
    <div class="info-group">
        <label>Status:</label>
        <span><?= ucfirst($order['status']) ?></span>
    </div>
    <div class="info-group">
        <label>Date:</label>
        <span><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></span>
    </div>
</div>

<h3>Order Items</h3>
<table class="items-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>SKU</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= sanitize($item['name'] ?? $item['product_name']) ?></td>
            <td><?= sanitize($item['sku'] ?? 'N/A') ?></td>
            <td><?= (int)$item['qty'] ?></td>
            <td><?= format_price($item['price']) ?></td>
            <td><?= format_price($item['price'] * $item['qty']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">Subtotal</td>
            <td><?= format_price($order['subtotal']) ?></td>
        </tr>
        <tr>
            <td colspan="4">Shipping</td>
            <td><?= format_price($order['shipping_cost']) ?></td>
        </tr>
        <tr>
            <td colspan="4"><strong>Total</strong></td>
            <td><strong><?= format_price($order['total']) ?></strong></td>
        </tr>
    </tfoot>
</table>

<?php if ($order['notes']): ?>
<h3>Notes</h3>
<p><?= nl2br(sanitize($order['notes'])) ?></p>
<?php endif; ?>

<style>
.order-info { margin-bottom: 20px; }
.info-group { margin-bottom: 10px; }
.info-group label { font-weight: 600; color: #666; display: inline-block; width: 120px; }
.info-group span { color: #333; }
.items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.items-table th, .items-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
.items-table th { background: #fafafa; font-size: 13px; }
.items-table tfoot td { border-top: 2px solid #333; }
</style>