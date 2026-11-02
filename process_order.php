<?php
session_start();
require_once 'includes/db.php';

// Strictly enforce POST requests. If someone tries to access this via GET, boot them out.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit;
}

// Ensure the cart isn't empty before processing
if (empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit;
}

// Sanitize inputs to prevent XSS
$full_name = htmlspecialchars($_POST['full_name'] ?? '');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$address = htmlspecialchars($_POST['address'] ?? '');

// Recalculate the total securely on the backend so users can't manipulate prices in the browser
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

try {
    // Begin a database transaction. This ensures that if the item insertion fails, 
    // the main order isn't saved either (preventing incomplete data).
    $pdo->beginTransaction();

    // 1. Insert into the main orders table
    $stmt = $pdo->prepare("INSERT INTO orders (user_name, user_email, shipping_address, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $address, $total_amount]);
    
    // Grab the ID of the order we just created
    $order_id = $pdo->lastInsertId();

    // 2. Insert each cart item into the order_items table
    $stmt_items = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $stmt_items->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }

    // Commit the transaction to save everything permanently
    $pdo->commit();

    // 3. Clear the cart completely now that the order is placed
    unset($_SESSION['cart']);

    // 4. Redirect the user to a success page
    header("Location: order_success.php");
    exit;

} catch (Exception $e) {
    // If anything fails, rollback the database changes
    $pdo->rollBack();
    die("Order Processing Failed: " . $e->getMessage());
}
?>