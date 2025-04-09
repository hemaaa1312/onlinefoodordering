<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) {
    $_SESSION['login_required'] = 'Please login to add items to cart';
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add_to_cart'])) {
        $product_id = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['qty'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
        $user_id = $_SESSION['user_id'];
        
        if(!$product_id || !$quantity) {
            $_SESSION['cart_error'] = 'Invalid product or quantity';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        // Check if product exists
        $product_stmt = $conn->prepare("SELECT * FROM dishes WHERE id = ? AND status = 'active'");
        $product_stmt->execute([$product_id]);
        $product = $product_stmt->fetch();
        
        if(!$product) {
            $_SESSION['cart_error'] = 'Product not available';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        // Check if item already in cart
        $cart_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $cart_stmt->execute([$user_id, $product_id]);
        $existing_item = $cart_stmt->fetch();
        
        if($existing_item) {
            // Update quantity
            $new_quantity = $existing_item['quantity'] + $quantity;
            $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update_stmt->execute([$new_quantity, $existing_item['id']]);
        } else {
            // Add new item
            $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())");
            $insert_stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        $_SESSION['cart_success'] = 'Item added to cart successfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // Add other cart actions here (update, remove, etc.)
} else {
    header('Location: index.php');
    exit;
}