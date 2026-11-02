<?php 
session_start();

// If the cart is empty, redirect back to shop
if (empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit;
}

// Calculate the total for the summary
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Blume</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased pt-24 bg-gray-50">

    <?php include 'includes/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <h1 class="serif-font text-3xl mb-8">Checkout</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Left Side: Shipping Form -->
            <div class="bg-white p-8 shadow-sm">
                <h2 class="font-medium text-lg mb-6 tracking-wide">SHIPPING DETAILS</h2>
                
                <!-- We will point this form to process_order.php later -->
                <form action="process_order.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Full Name</label>
                        <!-- Auto-fill the name if they logged in with Google -->
                        <input type="text" name="full_name" required value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" class="w-full border border-gray-300 p-3 focus:outline-none focus:border-black text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Email Address</label>
                        <!-- Auto-fill the email if they logged in with Google -->
                        <input type="email" name="email" required value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" class="w-full border border-gray-300 p-3 focus:outline-none focus:border-black text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Shipping Address</label>
                        <textarea name="address" required rows="3" class="w-full border border-gray-300 p-3 focus:outline-none focus:border-black text-sm"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-black text-white py-4 mt-4 text-sm tracking-widest hover:bg-gray-800 transition">
                        PLACE ORDER
                    </button>
                </form>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="bg-white p-8 shadow-sm h-fit">
                <h2 class="font-medium text-lg mb-6 tracking-wide">ORDER SUMMARY</h2>
                
                <div class="space-y-4 mb-6 border-b pb-6">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <?php if(!empty($item['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-16 h-20 object-cover bg-gray-100">
                                <?php else: ?>
                                    <div class="w-16 h-20 bg-gray-200"></div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm"><?php echo htmlspecialchars($item['name']); ?></p>
                                    <p class="text-xs text-gray-500">Qty: <?php echo $item['quantity']; ?></p>
                                </div>
                            </div>
                            <p class="text-sm font-medium">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-between items-center text-lg font-medium">
                    <span>TOTAL</span>
                    <span>$<?php echo number_format($cart_total, 2); ?></span>
                </div>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>