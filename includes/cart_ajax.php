<?php
/**
 * Cart AJAX Handler - Elite BBS Rims
 * Handles cart operations via AJAX
 */

ob_start(); // buffer any stray PHP warnings so they don't corrupt JSON

require_once __DIR__ . '/../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/cart_functions.php';

ob_end_clean(); // discard any warning output before sending JSON
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
    case 'add':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            break;
        }
        
        $result = cart_add($product_id, $quantity);
        echo json_encode($result);
        break;
    
    case 'remove':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            break;
        }
        
        $result = cart_remove($product_id);
        echo json_encode($result);
        break;
    
    case 'update':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            break;
        }
        
        $result = cart_update($product_id, $quantity);
        $result['total'] = cart_total();
        echo json_encode($result);
        break;
    
    case 'clear':
        $result = cart_clear();
        echo json_encode($result);
        break;
    
    case 'get':
    default:
        echo json_encode([
            'count' => cart_count(),
            'total' => cart_total(),
            'items' => cart_items()
        ]);
        break;
}