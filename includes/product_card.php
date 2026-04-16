<?php
/**
 * Product Card Component
 * 
 * Usage: include 'product_card.php' with $product variable
 * Or call: echo product_card($product);
 */

function product_card($product, $echo = true) {
    $images = json_decode($product['images'] ?? '[]', true);
    $img = !empty($images[0]) ? $images[0] : asset_url('images/placeholder.png');
    
    $price_info = get_display_price($product);
    $price = format_price($price_info['price']);
    $regular_price = $price_info['on_sale'] ? format_price($price_info['regular']) : '';
    
    $is_sale = $price_info['on_sale'];
    $is_featured = $product['featured'];
    
    $badge = '';
    if ($is_sale) {
        $badge = '<span class="badge sale">Sale</span>';
    } elseif ($is_featured) {
        $badge = '<span class="badge">Featured</span>';
    }
    
    $html = '<div class="product-card">';
    $html .= '<div class="box-image">';
    $html .= '<a href="' . site_url('product/' . $product['slug']) . '">';
    $html .= '<img src="' . $img . '" alt="' . htmlspecialchars($product['name']) . '">';
    $html .= '</a>';
    $html .= $badge;
    $html .= '</div>';
    
    $html .= '<div class="box-content">';
    
    if ($product['category']) {
        $html .= '<p class="category">' . htmlspecialchars($product['category']) . '</p>';
    }
    
    $html .= '<h3><a href="' . site_url('product/' . $product['slug']) . '">' . htmlspecialchars($product['name']) . '</a></h3>';
    
    if ($product['short_description']) {
        $html .= '<p class="short-description">' . htmlspecialchars($product['short_description']) . '</p>';
    }
    
    $html .= '<p class="price">';
    $html .= $price;
    if ($regular_price) {
        $html .= '<span class="regular-price">' . $regular_price . '</span>';
    }
    $html .= '</p>';
    
    $html .= '<a href="' . site_url('product/' . $product['slug']) . '" class="button primary is-outline">View Details</a>';
    $html .= '</div>';
    $html .= '</div>';
    
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Render multiple product cards
 */
function product_cards($products, $echo = true) {
    $html = '<div class="product-grid">';
    
    foreach ($products as $product) {
        $html .= product_card($product, false);
    }
    
    $html .= '</div>';
    
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Featured Products Section
 */
function featured_products_section($limit = 4) {
    $products = get_featured_products($limit);
    
    if (empty($products)) {
        return '';
    }
    
    $html = '<section class="section products-featured">';
    $html .= '<div class="container">';
    $html .= '<h2 class="section-title">Featured Products</h2>';
    $html .= product_cards($products, false);
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/**
 * Products Grid Section
 */
function products_grid_section($title, $products) {
    if (empty($products)) {
        return '';
    }
    
    $html = '<section class="section products-section">';
    $html .= '<div class="container">';
    
    if ($title) {
        $html .= '<h2 class="section-title">' . $title . '</h2>';
    }
    
    $html .= product_cards($products, false);
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}