<?php
/**
 * Cart Functions - Elite BBS Rims
 */

// Initialize cart session
function init_cart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

// Add item to cart
function cart_add($product_id, $quantity = 1) {
    init_cart();
    
    $product = get_product_by_id($product_id);
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }
    
    $price = get_display_price($product);
    $actual_price = $price['price'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'slug' => $product['slug'],
            'price' => $actual_price,
            'quantity' => $quantity,
            'sku' => $product['sku'],
            'image' => get_product_image($product)
        ];
    }
    
    return ['success' => true, 'message' => 'Added to cart', 'count' => cart_count()];
}

// Remove item from cart
function cart_remove($product_id) {
    init_cart();
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return ['success' => true, 'message' => 'Removed from cart', 'count' => cart_count()];
    }
    
    return ['success' => false, 'message' => 'Item not found'];
}

// Update cart item quantity
function cart_update($product_id, $quantity) {
    init_cart();
    
    if ($quantity <= 0) {
        return cart_remove($product_id);
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        return ['success' => true, 'message' => 'Updated', 'count' => cart_count()];
    }
    
    return ['success' => false, 'message' => 'Item not found'];
}

// Get cart count
function cart_count() {
    init_cart();
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

// Get cart total
function cart_total() {
    init_cart();
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Get cart items
function cart_items() {
    init_cart();
    return $_SESSION['cart'];
}

// Check if cart is empty
function cart_is_empty() {
    init_cart();
    return empty($_SESSION['cart']);
}

// Clear cart
function cart_clear() {
    init_cart();
    $_SESSION['cart'] = [];
    return ['success' => true, 'message' => 'Cart cleared'];
}

// Format cart for order
function cart_format_for_order() {
    $items = cart_items();
    $formatted = [];
    
    foreach ($items as $id => $item) {
        $formatted[] = [
            'product_id' => $item['id'],
            'name' => $item['name'],
            'sku' => $item['sku'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'total' => $item['price'] * $item['quantity']
        ];
    }
    
    return $formatted;
}

// AJAX cart response
function cart_ajax_response($result) {
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}