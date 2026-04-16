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

require_once INCLUDES_PATH . '/header.php';
?>
<div class="admin-container">
    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <a href="<?= site_url('admin/') ?>">Dashboard</a>
            <a href="<?= site_url('admin/products.php') ?>" class="active">Products</a>
            <a href="<?= site_url('admin/orders.php') ?>">Orders</a>
            <a href="<?= site_url('admin/settings.php') ?>">Settings</a>
            <a href="<?= site_url('admin/logout.php') ?>">Logout</a>
        </nav>
    </aside>
    
    <main class="admin-main">
        <header class="admin-header">
            <h1>Products</h1>
            <p>Manage your product catalog</p>
        </header>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="product-form-card">
            <h2><?= $edit_product ? 'Edit Product' : 'Add New Product' ?></h2>
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
                            <option value="Wheels" <?= ($edit_product['category'] ?? '') === 'Wheels' ? 'selected' : '' ?>>Wheels</option>
                            <option value="Accessories" <?= ($edit_product['category'] ?? '') === 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                            <option value="Parts" <?= ($edit_product['category'] ?? '') === 'Parts' ? 'selected' : '' ?>>Parts</option>
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
                
                <button type="submit" class="btn btn-primary"><?= $edit_product ? 'Update Product' : 'Add Product' ?></button>
                <?php if ($edit_product): ?>
                    <a href="<?= site_url('admin/products.php') ?>" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="7">No products yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= sanitize($product['name']) ?></td>
                                <td><?= sanitize($product['brand'] ?? '-') ?></td>
                                <td><?= sanitize($product['category'] ?? '-') ?></td>
                                <td><?= format_price($product['price']) ?></td>
                                <td><?= (int)$product['stock'] ?></td>
                                <td><?= ucfirst($product['status']) ?></td>
                                <td>
                                    <a href="?edit=<?= $product['id'] ?>" class="btn-edit">Edit</a>
                                    <a href="?delete=<?= $product['id'] ?>" class="btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
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
.alert-error { background: #f8d7da; color: #721c24; }
.product-form-card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 30px; }
.product-form-card h2 { font-size: 20px; margin-bottom: 20px; }
.product-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.product-form .form-group { margin-bottom: 15px; }
.product-form label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px; }
.product-form input, .product-form select, .product-form textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.product-form input:focus, .product-form textarea:focus { outline: none; border-color: #e63946; }
.checkbox-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.checkbox-group input { width: auto; }
.products-table { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.products-table table { width: 100%; border-collapse: collapse; }
.products-table th, .products-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
.products-table th { font-weight: 600; color: #666; font-size: 13px; background: #fafafa; }
.btn-edit, .btn-delete { margin-right: 10px; color: #e63946; font-size: 13px; }
.btn-delete { color: #dc3545; }
.btn-primary { background: #e63946; color: #fff; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; margin-right: 10px; }
.btn-primary:hover { background: #d32f3f; }
.btn-secondary { background: #6c757d; color: #fff; padding: 12px 24px; border: none; border-radius: 4px; font-size: 14px; text-decoration: none; }
.pagination { margin-top: 20px; }
.pagination a { padding: 8px 16px; background: #e63946; color: #fff; text-decoration: none; border-radius: 4px; margin-right: 10px; }
.pagination span { color: #666; }
</style>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>