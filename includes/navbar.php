<?php 
// Check if a session is already running. If not, start one so we can remember the user and their cart data.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the starting number of items in the cart to 0.
$cart_count = 0;

// If the cart exists and has items inside it, count how many total items there are.
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!-- Search Modal (The pop-up box when you click search) -->
<div id="searchModal" class="hidden fixed inset-0 bg-black/50 z-[60] flex justify-center items-start pt-20 transition-opacity">
    <div class="bg-white w-full max-w-3xl p-8 shadow-lg relative">
        <button id="closeSearch" class="absolute top-4 right-4 text-gray-500 hover:text-black">✕</button>
        <h2 class="serif-font text-2xl mb-4">Search</h2>
        <input type="text" placeholder="Search for pastel cardigans, maxi dresses..." class="w-full border-b border-gray-300 py-2 focus:outline-none focus:border-black mb-6">
        <div class="grid grid-cols-3 gap-4">
            <!-- Search Suggestions -->
            <div>
                <div class="bg-gray-100 h-40 w-full mb-2"></div>
                <p class="text-xs">Ribbed Knit Top - $35.00</p>
            </div>
            <div>
                <div class="bg-gray-100 h-40 w-full mb-2"></div>
                <p class="text-xs">Pleated Midi Skirt - $45.00</p>
            </div>
        </div>
    </div>
</div>

<!-- Cart Drawer (The side menu that slides out when you click Cart) -->
<div id="cartDrawer" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 h-full flex flex-col">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h2 class="serif-font text-xl">Your Cart</h2>
            <button id="closeCart" class="text-gray-500 hover:text-black text-xl">✕</button>
        </div>
        
        <!-- Cart Items Area -->
        <div class="flex-grow overflow-y-auto mb-4">
            <?php 
            // Check if the cart is completely empty
            if (empty($_SESSION['cart'])): 
            ?>
                <div class="flex flex-col justify-center items-center h-full text-gray-500 space-y-4">
                    <p class="text-lg">Your cart is empty</p>
                    <?php 
                    // If they are not logged in, show them the login link
                    if(!isset($_SESSION['user_name'])): 
                    ?>
                        <p class="text-sm">Have an account? <a href="login.php" class="underline hover:text-black">Log in</a> to check out faster.</p>
                    <?php endif; ?>
                </div>
            <?php else: 
            // If there are items in the cart, show them here
            ?>
                <div class="flex flex-col space-y-6">
                    <?php 
                    $cart_total = 0;
                    // Loop through each item saved in the cart session
                    foreach ($_SESSION['cart'] as $index => $item): 
                        // Calculate the cost for this specific item (price multiplied by quantity)
                        $item_total = $item['price'] * $item['quantity'];
                        // Add this item's cost to the overall cart total
                        $cart_total += $item_total;
                    ?>
                        <div class="flex space-x-4">
                            <?php 
                            // Show the product image if it exists, otherwise show a gray box
                            if(!empty($item['image'])): 
                            ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-20 h-24 object-cover bg-gray-100" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php else: ?>
                                <div class="w-20 h-24 bg-gray-200"></div>
                            <?php endif; ?>
                            
                            <div class="flex-grow flex flex-col justify-between py-1">
                                <div>
                                    <h3 class="text-sm text-gray-900"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    
                                    <!-- UPDATED QUANTITY AND REMOVE UI -->
                                    <div class="flex items-center justify-between mt-2">
                                        <!-- Quantity Selector Box -->
                                        <div class="flex items-center border border-gray-300 rounded-sm">
                                            
                                            <!-- Form to decrease quantity by 1 -->
                                            <form action="cart_action.php" method="POST" class="m-0 p-0 flex">
                                                <input type="hidden" name="action" value="decrease">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="px-2 py-1 text-gray-500 hover:bg-gray-100 hover:text-black transition cursor-pointer">-</button>
                                            </form>
                                            
                                            <!-- Show the current quantity number -->
                                            <span class="px-3 py-1 text-xs font-medium text-gray-900 border-l border-r border-gray-300 flex items-center"><?php echo $item['quantity']; ?></span>
                                            
                                            <!-- Form to increase quantity by 1 -->
                                            <form action="cart_action.php" method="POST" class="m-0 p-0 flex">
                                                <input type="hidden" name="action" value="increase">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="px-2 py-1 text-gray-500 hover:bg-gray-100 hover:text-black transition cursor-pointer">+</button>
                                            </form>
                                        </div>
                                        
                                        <!-- Form to completely delete the item from the cart -->
                                        <form action="cart_action.php" method="POST" class="m-0 p-0">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition ml-4 cursor-pointer" title="Remove Item">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <!-- END UPDATED UI -->

                                </div>
                                <!-- Display the total price for this specific item -->
                                <p class="text-sm font-medium">$<?php echo number_format($item_total, 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cart Footer (Bottom section with total price and checkout button) -->
        <div class="border-t pt-4">
            <?php 
            // Only show the subtotal and checkout button if the cart has items
            if (!empty($_SESSION['cart'])): 
            ?>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-bold tracking-widest">SUBTOTAL</span>
                    <span class="text-sm">$<?php echo number_format($cart_total, 2); ?></span>
                </div>
                <a href="checkout.php" class="w-full bg-black text-white py-3 text-sm tracking-widest hover:bg-gray-800 transition text-center block">CHECKOUT</a>            <?php else: 
            // Show a continue shopping button if the cart is empty
            ?>
                <button class="w-full bg-black text-white py-3 text-sm tracking-widest hover:bg-gray-800 transition" onclick="document.getElementById('closeCart').click()">CONTINUE SHOPPING</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Overlay for Cart (The dark background when the cart is open) -->
<div id="cartOverlay" class="hidden fixed inset-0 bg-black/20 z-[65]"></div>

<!-- Navigation Bar (The top menu across the whole website) -->
<nav class="w-full absolute top-0 left-0 z-50 px-8 py-6 flex justify-between items-center text-xs font-medium tracking-widest">
    <div class="flex space-x-8">
        <a href="index.php" class="hover:text-gray-500 transition">HOME</a>
        <a href="shop.php" class="hover:text-gray-500 transition">SHOP ALL</a>
        <a href="#" class="hover:text-gray-500 transition">A SUMMER PLACE</a>
    </div>
    
    <div class="absolute left-1/2 transform -translate-x-1/2">
        <a href="index.php"><span class="serif-font text-3xl tracking-widest">BLUME</span></a>
    </div>

    <div class="flex space-x-6 items-center">
        <span>SGD v</span>
        <button id="openSearch" class="hover:text-gray-500 transition">SEARCH</button>
        
        <!-- LOGIN/LOGOUT LOGIC -->
        <?php 
        // If the user's name is saved in the session (meaning they are logged in)
        if(isset($_SESSION['user_name'])): 
        ?>
            <!-- Show their name in uppercase -->
            <span class="hover:text-gray-500 transition">HI, <?php echo strtoupper(htmlspecialchars($_SESSION['user_name'])); ?></span>
            <!-- Give them a link to log out -->
            <a href="logout.php" class="hover:text-gray-500 transition">LOGOUT</a>
        <?php else: 
        // If they are not logged in, just show the standard Account link
        ?>
            <a href="login.php" class="hover:text-gray-500 transition">ACCOUNT</a>
        <?php endif; ?>

        <!-- DYNAMIC CART BUTTON -->
        <!-- Display the word CART next to the number of items we counted at the top of the file -->
        <button id="openCart" class="hover:text-gray-500 transition">CART (<?php echo $cart_count; ?>)</button>
    </div>
    <!-- Auto-open cart script if a cart action was just performed -->
<?php if (isset($_SESSION['keep_cart_open']) && $_SESSION['keep_cart_open']): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartDrawer = document.getElementById('cartDrawer');
            const cartOverlay = document.getElementById('cartOverlay');
            
            if (cartDrawer && cartOverlay) {
                // Remove the Tailwind classes that hide the drawer and overlay
                cartDrawer.classList.remove('translate-x-full');
                cartOverlay.classList.remove('hidden');
            }
        });
    </script>
    <?php 
    // Unset the flag so it doesn't stay open on normal page navigations
    unset($_SESSION['keep_cart_open']); 
    ?>
<?php endif; ?>
</nav>