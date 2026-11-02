<?php 
require_once 'includes/db.php'; 

// Get the product ID from the web address
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch that specific product from your database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If someone types a random ID that doesn't exist, send them back to the shop
if (!$product) {
    header("Location: shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Blume</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased pt-24">

    <?php include 'includes/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Left Side: Image Gallery -->
            <div class="flex flex-col space-y-4">
                <?php if(!empty($product['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="w-full bg-gray-100 object-cover">
                <?php endif; ?>
                
                <?php if(!empty($product['image_url_2'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url_2']); ?>" class="w-full bg-gray-100 object-cover">
                <?php endif; ?>

                <?php if(!empty($product['image_url_3'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url_3']); ?>" class="w-full bg-gray-100 object-cover">
                <?php endif; ?>
            </div>

            <!-- Right Side: Product Details -->
            <div class="md:sticky md:top-32 h-fit">
                <h1 class="serif-font text-4xl mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-lg mb-8">$<?php echo htmlspecialchars($product['price']); ?></p>
                
                <div class="mb-8">
                    <p class="text-sm text-gray-600 leading-relaxed"><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                
                <!-- NEW ADD TO CART FORM -->
                <form action="cart_action.php" method="POST" class="w-full">
                    <!-- Hidden inputs to securely send the product data to the cart -->
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image_url']); ?>">
                    
                    <button type="submit" name="add_to_cart" class="w-full bg-black text-white py-4 text-sm tracking-widest hover:bg-gray-800 transition mb-4">
                        ADD TO CART
                    </button>
                </form>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>