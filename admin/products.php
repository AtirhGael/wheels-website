<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

require_admin_login();
$admin = get_current_admin();

$success = '';
$error = '';

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    db_query("DELETE FROM products WHERE id = :id", [':id' => $product_id]);
    $success = 'Product deleted successfully.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $slug = clean_slug($_POST['slug'] ?? $name);
    $description = sanitize($_POST['description'] ?? '');
    $short_description = sanitize($_POST['short_description'] ?? '');
    $price = (float)$_POST['price'];
    $sale_price = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
    $sku = sanitize($_POST['sku'] ?? '');
    $stock = (int)$_POST['stock'];
    $category = sanitize($_POST['category'] ?? '');
    $brand = sanitize($_POST['brand'] ?? '');
    $size = sanitize($_POST['size'] ?? '');
    $finish = sanitize($_POST['finish'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if (!$name || !$price) {
        $error = 'Name and price are required.';
    } else {
        if ($product_id > 0) {
            $sql = "UPDATE products SET name=:name, slug=:slug, description=:description, short_description=:short_description, 
                    price=:price, sale_price=:sale_price, sku=:sku, stock=:stock, category=:category, brand=:brand, 
                    size=:size, finish=:finish, featured=:featured, status=:status WHERE id=:id";
            db_query($sql, [':name'=>$name, ':slug'=>$slug, ':description'=>$description, ':short_description'=>$short_description,
                ':price'=>$price, ':sale_price'=>$sale_price, ':sku'=>$sku, ':stock'=>$stock, ':category'=>$category,
                ':brand'=>$brand, ':size'=>$size, ':finish'=>$finish, ':featured'=>$featured, ':status'=>$status, ':id'=>$product_id]);
            $success = 'Product updated successfully.';
        } else {
            $existing = db_get_row("SELECT id FROM products WHERE slug = :slug", [':slug' => $slug]);
            if ($existing) {
                $error = 'A product with this slug already exists.';
            } else {
                $sql = "INSERT INTO products (name, slug, description, short_description, price, sale_price, sku, stock, category, brand, size, finish, featured, status) 
                        VALUES (:name, :slug, :description, :short_description, :price, :sale_price, :sku, :stock, :category, :brand, :size, :finish, :featured, :status)";
                db_query($sql, [':name'=>$name, ':slug'=>$slug, ':description'=>$description, ':short_description'=>$short_description,
                    ':price'=>$price, ':sale_price'=>$sale_price, ':sku'=>$sku, ':stock'=>$stock, ':category'=>$category,
                    ':brand'=>$brand, ':size'=>$size, ':finish'=>$finish, ':featured'=>$featured, ':status'=>$status]);
                $success = 'Product added successfully.';
            }
        }
    }
}

$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$total_products = db_get_row("SELECT COUNT(*) as cnt FROM products")['cnt'];
$products = db_get_all("SELECT * FROM products ORDER BY created_at DESC LIMIT :limit OFFSET :offset", [':limit'=>$per_page, ':offset'=>$offset]);
$total_pages = ceil($total_products / $per_page);

$edit_product = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_product = get_product_by_id((int)$_GET['edit']);
}

$admin_page = 'products';
$page_title = 'Products';
require_once INCLUDES_PATH . '/admin_header.php';
?>

<div class="adm-page-header">
    <div>
        <div class="adm-page-title">Products</div>
        <div class="adm-page-sub">Manage your product catalog</div>
    </div>
</div>

<?php if ($success): ?>
    <div class="adm-alert success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="adm-alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="adm-card" style="margin-bottom:28px;">
    <div class="adm-card-header">
        <span class="adm-card-title"><?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></span>
    </div>
    <div class="adm-card-body">
        <form method="POST" class="product-form">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" value="<?= sanitize($edit_product['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" value="<?= sanitize($edit_product['slug'] ?? '') ?>" placeholder="auto-generated">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price *</label>
                        <input type="number" name="price" step="0.01" value="<?= $edit_product['price'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sale Price</label>
                        <input type="number" name="sale_price" step="0.01" value="<?= $edit_product['sale_price'] ?? '' ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" value="<?= sanitize($edit_product['sku'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" value="<?= $edit_product['stock'] ?? 0 ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select category</option>
                            <option value="BBS" <?= ($edit_product['category'] ?? '') === 'BBS' ? 'selected' : '' ?>>BBS</option>
                            <option value="Konig" <?= ($edit_product['category'] ?? '') === 'Konig' ? 'selected' : '' ?>>Konig</option>
                            <option value="Work Wheels" <?= ($edit_product['category'] ?? '') === 'Work Wheels' ? 'selected' : '' ?>>Work Wheels</option>
                            <option value="Volk Racing" <?= ($edit_product['category'] ?? '') === 'Volk Racing' ? 'selected' : '' ?>>Volk Racing</option>
                            <option value="SSR" <?= ($edit_product['category'] ?? '') === 'SSR' ? 'selected' : '' ?>>SSR</option>
                            <option value="Weds/Kranze" <?= ($edit_product['category'] ?? '') === 'Weds/Kranze' ? 'selected' : '' ?>>Weds/Kranze</option>
                            <option value="Leon Hardiritt" <?= ($edit_product['category'] ?? '') === 'Leon Hardiritt' ? 'selected' : '' ?>>Leon Hardiritt</option>
                            <option value="Blitz" <?= ($edit_product['category'] ?? '') === 'Blitz' ? 'selected' : '' ?>>Blitz</option>
                            <option value="RiverSide" <?= ($edit_product['category'] ?? '') === 'RiverSide' ? 'selected' : '' ?>>RiverSide</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand">
                            <option value="">Select brand</option>
                            <option value="BBS" <?= ($edit_product['brand'] ?? '') === 'BBS' ? 'selected' : '' ?>>BBS</option>
                            <option value="SSR" <?= ($edit_product['brand'] ?? '') === 'SSR' ? 'selected' : '' ?>>SSR</option>
                            <option value="Work" <?= ($edit_product['brand'] ?? '') === 'Work' ? 'selected' : '' ?>>Work</option>
                            <option value="Advan" <?= ($edit_product['brand'] ?? '') === 'Advan' ? 'selected' : '' ?>>Advan</option>
                            <option value="Volk Racing" <?= ($edit_product['brand'] ?? '') === 'Volk Racing' ? 'selected' : '' ?>>Volk Racing</option>
                            <option value="Enkei" <?= ($edit_product['brand'] ?? '') === 'Enkei' ? 'selected' : '' ?>>Enkei</option>
                            <option value="Weds" <?= ($edit_product['brand'] ?? '') === 'Weds' ? 'selected' : '' ?>>Weds</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Size</label>
                        <input type="text" name="size" value="<?= sanitize($edit_product['size'] ?? '') ?>" placeholder="18x9.5">
                    </div>
                    <div class="form-group">
                        <label>Finish</label>
                        <select name="finish">
                            <option value="">Select finish</option>
                            <option value="Silver" <?= ($edit_product['finish'] ?? '') === 'Silver' ? 'selected' : '' ?>>Silver</option>
                            <option value="Bronze" <?= ($edit_product['finish'] ?? '') === 'Bronze' ? 'selected' : '' ?>>Bronze</option>
                            <option value="Black" <?= ($edit_product['finish'] ?? '') === 'Black' ? 'selected' : '' ?>>Black</option>
                            <option value="Gunmetal" <?= ($edit_product['finish'] ?? '') === 'Gunmetal' ? 'selected' : '' ?>>Gunmetal</option>
                            <option value="Chrome" <?= ($edit_product['finish'] ?? '') === 'Chrome' ? 'selected' : '' ?>>Chrome</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_description" value="<?= sanitize($edit_product['short_description'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Full Description</label>
                    <textarea name="description" rows="4"><?= sanitize($edit_product['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="featured" value="1" <?= ($edit_product['featured'] ?? 0) ? 'checked' : '' ?>>
                            Featured
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active" <?= ($edit_product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="draft" <?= ($edit_product['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="adm-btn primary"><?php echo $edit_product ? 'Update Product' : 'Add Product'; ?></button>
                <?php if ($edit_product): ?>
                    <a href="<?php echo site_url('admin/products.php'); ?>" class="adm-btn ghost" style="margin-left:8px;">Cancel</a>
                <?php endif; ?>
            </form>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-header">
        <span class="adm-card-title">All Products</span>
        <span style="font-size:12px;color:#aaa;"><?php echo $total_products; ?> total</span>
    </div>
    <table class="adm-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="7" style="text-align:center;color:#aaa;padding:32px;">No products yet.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td style="color:#888;"><?php echo htmlspecialchars($product['brand'] ?? '-'); ?></td>
                        <td style="color:#888;"><?php echo htmlspecialchars($product['category'] ?? '-'); ?></td>
                        <td style="font-weight:700;"><?php echo format_price($product['price']); ?></td>
                        <td><?php echo (int)$product['stock']; ?></td>
                        <td><span class="adm-badge <?php echo $product['status'] === 'active' ? 'completed' : 'cancelled'; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                        <td style="white-space:nowrap;">
                            <a href="?edit=<?php echo $product['id']; ?>" class="adm-btn ghost" style="font-size:11px;padding:5px 12px;margin-right:6px;">Edit</a>
                            <a href="?delete=<?php echo $product['id']; ?>" class="adm-btn danger" style="font-size:11px;padding:5px 12px;" onclick="return confirm('Delete this product?')">Delete</a>
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

<style>
.product-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.product-form .form-group { margin-bottom: 15px; }
.product-form label { display: block; margin-bottom: 6px; font-family: 'Barlow', sans-serif; font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #888; }
.product-form input, .product-form select, .product-form textarea { width: 100%; padding: 10px 14px; border: 1.5px solid #dde1e7; border-radius: 7px; font-size: 14px; outline: none; transition: border-color 0.2s; }
.product-form input:focus, .product-form textarea:focus, .product-form select:focus { border-color: #008cb2; box-shadow: 0 0 0 3px rgba(0,140,178,0.1); }
.checkbox-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; text-transform: none; letter-spacing: 0; font-weight: 600; color: #444; }
.checkbox-group input { width: auto; }
</style>

<?php require_once INCLUDES_PATH . '/admin_footer.php'; ?>