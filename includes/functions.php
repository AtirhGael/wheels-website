<?php
/**
 * Helper Functions - Elite BBS Rims
 */

// Sanitize input
function sanitize($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Get all featured products
function get_featured_products($limit = 4) {
    return db_get_all("SELECT * FROM products WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT " . intval($limit));
}

// Get all active products
function get_all_products($limit = null, $offset = 0) {
    $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    }
    return db_get_all($sql);
}

// Get product by slug
function get_product_by_slug($slug) {
    return db_get_row("SELECT * FROM products WHERE slug = :slug AND status = 'active'", [':slug' => $slug]);
}

// Get product by ID
function get_product_by_id($id) {
    return db_get_row("SELECT * FROM products WHERE id = :id", [':id' => $id]);
}

// Get products by category
function get_products_by_category($category, $limit = null) {
    $category = sanitize($category);
    $sql = "SELECT * FROM products WHERE status = 'active' AND category = :category ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
        return db_get_all($sql, [':category' => $category]);
    }
    return db_get_all($sql, [':category' => $category]);
}

// Search products
function search_products($query, $limit = 20) {
    $search = '%' . sanitize($query) . '%';
    return db_get_all("SELECT * FROM products WHERE status = 'active' AND (name LIKE :search OR description LIKE :search OR category LIKE :search OR brand LIKE :search OR sku LIKE :search) ORDER BY name LIMIT " . intval($limit), [':search' => $search]);
}

// Get all categories
function get_all_categories() {
    return db_get_all("SELECT DISTINCT category FROM products WHERE status = 'active' AND category IS NOT NULL ORDER BY category");
}

// Get all brands
function get_all_brands() {
    return db_get_all("SELECT DISTINCT brand FROM products WHERE status = 'active' AND brand IS NOT NULL ORDER BY brand");
}

// Format price
function format_price($price) {
    return '$' . number_format($price, 2);
}

// Get sale price or regular price
function get_display_price($product) {
    if ($product['sale_price'] && $product['sale_price'] < $product['price']) {
        return [
            'price' => $product['sale_price'],
            'regular' => $product['price'],
            'on_sale' => true
        ];
    }
    return [
        'price' => $product['price'],
        'regular' => null,
        'on_sale' => false
    ];
}

// Get product images
function get_product_images($product) {
    $images = json_decode($product['images'] ?? '[]', true);
    if (empty($images)) {
        return [asset_url('images/placeholder.png')];
    }
    return $images;
}

// Get first product image
function get_product_image($product) {
    $images = get_product_images($product);
    return $images[0];
}

// Check if product is in stock
function is_in_stock($product) {
    return $product['stock'] > 0;
}


// Generate order number
function generate_order_number() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

// Get order by number
function get_order_by_number($order_number) {
    return db_get_row("SELECT * FROM orders WHERE order_number = :order_number", [':order_number' => $order_number]);
}

// Get all orders (for admin)
function get_all_orders($limit = 50, $offset = 0) {
    return db_get_all("SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit OFFSET :offset", [':limit' => $limit, ':offset' => $offset]);
}

// Update order status
function update_order_status($order_id, $status) {
    $sql = "UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id";
    return db_query($sql, [':status' => $status, ':id' => $order_id]);
}

// Get recent orders
function get_recent_orders($days = 7) {
    return db_get_all("SELECT * FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY) ORDER BY created_at DESC", [':days' => $days]);
}

// Pagination
function pagination($total, $per_page, $current_page, $base_url) {
    $total_pages = ceil($total / $per_page);
    if ($total_pages <= 1) return '';
    
    $html = '<div class="pagination">';
    
    if ($current_page > 1) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page - 1) . '">&laquo; Previous</a>';
    }
    
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $html .= '<span class="current">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $base_url . '?page=' . $i . '">' . $i . '</a>';
        }
    }
    
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page + 1) . '">Next &raquo;</a>';
    }
    
    $html .= '</div>';
    return $html;
}

// Clean slug for URL
function clean_slug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

// Get page SEO data
function get_seo_data($page) {
    $seo = [
        'home' => [
            'title' => SITE_NAME . ' - Premium BBS Wheels',
            'description' => 'Every set we offer is hand-selected for perfect fitment, superior craftsmanship, and the soul of true enthusiasts.'
        ],
        'shop' => [
            'title' => 'Shop - ' . SITE_NAME,
            'description' => 'Browse our selection of premium BBS wheels. Find the perfect fitment for your vehicle.'
        ],
        'about' => [
            'title' => 'About Us - ' . SITE_NAME,
            'description' => 'Learn about Elite BBS Rims and our commitment to providing authentic BBS wheels.'
        ],
        'contact' => [
            'title' => 'Contact Us - ' . SITE_NAME,
            'description' => 'Contact us for product inquiries, fitment help, or general questions.'
        ],
        'faq' => [
            'title' => 'FAQ - ' . SITE_NAME,
            'description' => 'Frequently asked questions about BBS wheels, fitment, shipping, and returns.'
        ]
    ];
    
    return isset($seo[$page]) ? $seo[$page] : $seo['home'];
}

// Check if current page
function is_current_page($page) {
    global $page_name;
    return $page_name === $page;
}

// Breadcrumb
function breadcrumb($items) {
    $html = '<div class="breadcrumb">';
    $html .= '<a href="' . site_url('') . '">Home</a>';
    
    foreach ($items as $url => $label) {
        $html .= ' &raquo; ';
        if ($url === '#') {
            $html .= '<span>' . $label . '</span>';
        } else {
            $html .= '<a href="' . site_url($url) . '">' . $label . '</a>';
        }
    }
    
    $html .= '</div>';
    return $html;
}

// Admin Functions
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function get_current_admin() {
    if (!is_admin_logged_in()) return null;
    return db_get_row("SELECT * FROM admins WHERE id = :id AND status = 'active'", [':id' => $_SESSION['admin_id']]);
}

function admin_login($username, $password) {
    $admin = db_get_row("SELECT * FROM admins WHERE username = :username AND status = 'active'", [':username' => $username]);
    if (!$admin) return false;
    
    if (password_verify($password, $admin['password'])) {
        db_query("UPDATE admins SET last_login = NOW() WHERE id = :id", [':id' => $admin['id']]);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_role'] = $admin['role'];
        return true;
    }
    return false;
}

function require_admin_login() {
    if (!is_admin_logged_in()) {
        header('Location: ' . site_url('admin/login.php'));
        exit;
    }
}

function get_site_settings() {
    $settings = db_get_all("SELECT * FROM site_settings");
    $result = [];
    foreach ($settings as $s) {
        $result[$s['setting_key']] = $s['setting_value'];
    }
    return $result;
}

function update_site_setting($key, $value) {
    $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
    return db_query($sql, [':key' => $key, ':value' => $value]);
}

function get_site_setting($key, $default = '') {
    $setting = db_get_row("SELECT setting_value FROM site_settings WHERE setting_key = :key", [':key' => $key]);
    return $setting ? $setting['setting_value'] : $default;
}