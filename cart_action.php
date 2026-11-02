<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Create an array of the product details being added
    $new_item = [
        'id' => $product_id,
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'image' => $_POST['product_image'],
        'quantity' => 1
    ];

    // If the cart doesn't exist yet, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if this exact product is already in the cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1; // Just increase quantity
            $item_exists = true;
            break;
        }
    }

    // If it's a new product, add it to the cart array
    if (!$item_exists) {
        $_SESSION['cart'][] = $new_item;
    }

    // Send the user seamlessly back to the page they were just on
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
// Handle item quantity updates and removal via POST request for better security
if (isset($_POST['action']) && isset($_POST['id'])) {
    $action = $_POST['action'];
    $update_id = $_POST['id'];
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $update_id) {
            if ($action == 'increase') {
                $item['quantity'] += 1;
            } elseif ($action == 'decrease') {
                $item['quantity'] -= 1;
                if ($item['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
            } elseif ($action == 'remove') {
                unset($_SESSION['cart'][$key]);
            }
            break; 
        }
    }
    
    // Re-index the array so there are no empty gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    $_SESSION['keep_cart_open'] = true; //keep cart open
    
    // Send them seamlessly back to the page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>