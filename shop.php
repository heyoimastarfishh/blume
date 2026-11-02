<?php 
require_once 'includes/db.php'; 
// Fetch products from database
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop All | Blume</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased pt-24">

    <?php include 'includes/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <h1 class="serif-font text-5xl mb-12 text-center">Products</h1>
        
        <div class="flex justify-between items-center mb-8 border-b pb-4 text-xs">
            <span><?php echo count($products); ?> PRODUCTS</span>
            <select class="bg-transparent outline-none">
                <option>SORT BY: FEATURED</option>
                <option>PRICE: LOW TO HIGH</option>
                <option>PRICE: HIGH TO LOW</option>
            </select>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-12">
            <?php if(count($products) > 0): ?>
                <?php foreach($products as $product): ?>
                    <!-- THIS IS THE NEW LINKED BLOCK -->
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="group cursor-pointer block">
                        <div class="bg-gray-200 aspect-[3/4] mb-3 relative overflow-hidden group">
                            <!-- Primary Image -->
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 <?php echo !empty($product['image_url_2']) ? 'group-hover:opacity-0' : 'group-hover:scale-105'; ?>">
                            
                            <!-- Secondary Image (Shows on Hover) -->
                            <?php if(!empty($product['image_url_2'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url_2']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col text-xs space-y-1">
                            <span class="font-medium"><?php echo htmlspecialchars($product['name']); ?></span>
                            <span class="text-gray-500">$<?php echo htmlspecialchars($product['price']); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Dummy Data for display if DB is empty -->
                <?php for($i=1; $i<=8; $i++): ?>
                <div class="group cursor-pointer">
                    <div class="bg-gray-200 aspect-[3/4] mb-3 relative overflow-hidden">
                        <img src="https://via.placeholder.com/400x600?text=Apparel+<?php echo $i; ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col text-xs space-y-1">
                        <span class="font-medium">Signature Pastel Blouse</span>
                        <span class="text-gray-500">$59.00</span>
                    </div>
                </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>