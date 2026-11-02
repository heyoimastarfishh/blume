<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | Blume</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased pt-24 bg-gray-50 flex flex-col min-h-screen">

    <?php include 'includes/navbar.php'; ?>

    <main class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="bg-white p-12 shadow-sm text-center max-w-xl w-full">
            <h1 class="serif-font text-3xl mb-4">Thank You, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Customer'; ?>!</h1>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                Your order has been securely placed. We are preparing your items for shipment and will send an update to your email shortly.
            </p>
            
            <a href="shop.php" class="bg-black text-white px-8 py-4 text-sm tracking-widest hover:bg-gray-800 transition inline-block">
                CONTINUE SHOPPING
            </a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>