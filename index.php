<?php require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blume | Official Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased relative">

    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Video Section -->
    <header class="relative w-full h-screen overflow-hidden bg-gray-200">
        <!-- Replace src with an actual video URL or local file path like 'assets/hero.mp4' -->
        <video autoplay loop muted playsinline class="absolute w-full h-full object-cover">
            <source src="assets/hero.mp4" type="video/mp4">
        </video>
        <!-- Dark overlay to make text readable -->
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 flex flex-col justify-end items-center pb-24 text-white">
            <h1 class="serif-font text-5xl mb-6 tracking-wide">A Summer Place</h1>
            <a href="shop.php" class="border border-white px-8 py-3 text-xs tracking-widest hover:bg-white hover:text-black transition">DISCOVER</a>
        </div>
    </header>

    <!-- Just Arrived Preview -->
    <main class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="serif-font text-3xl mb-8 italic">Just Arrived</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Mock Product 1 -->
            <div class="group cursor-pointer">
                <div class="bg-gray-200 aspect-[3/4] mb-2 overflow-hidden">
                    <img src="https://via.placeholder.com/400x600?text=Soft+Casual+Top" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="flex justify-between text-xs">
                    <span>Linen Wrap Top - Cream</span>
                    <span>$45.00</span>
                </div>
            </div>
            <!-- Add more mock products here if desired -->
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>