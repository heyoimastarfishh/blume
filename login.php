
<?php
session_start();

// Paste your actual keys between the quote marks below
$client_id = '896553599539-ef1bkent9hlnqraihp2fj8nlp2t5l44d.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost:8000/blume/login.php';

// This generates the secure Google link
$google_login_url = "https://accounts.google.com/o/oauth2/v2/auth?scope=" . urlencode("email profile") . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&client_id=" . $client_id . "&access_type=online";
// If Google sends back a code, process the login
if (isset($_GET['code'])) {
    // 1. Trade the code for an access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $data = [
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    
    // TEMPORARY FIX FOR LOCALHOST: Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    // 2. Use the token to get the user's email and name
    if (isset($token_data['access_token'])) {
        $profile_url = "https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . $token_data['access_token'];
        
        $ch2 = curl_init($profile_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        // TEMPORARY FIX FOR LOCALHOST: Disable SSL verification
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        
        $profile_response = curl_exec($ch2);
        curl_close($ch2);

        $profile_data = json_decode($profile_response, true);

        // 3. Save their info in the session to keep them logged in
        $_SESSION['user_email'] = $profile_data['email'];
        $_SESSION['user_name'] = $profile_data['name'];

        // 4. Send them to the shop page
        header("Location: shop.php");
        exit;
    } else {
        // If it fails, stop the page and print the exact error message
        die("Google Login Error: " . print_r($token_data, true));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Blume</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-gray-900 antialiased pt-24 h-screen flex flex-col">

    <?php include 'includes/navbar.php'; ?>

    <main class="flex-grow flex items-center justify-center px-6">
        <div class="max-w-md w-full text-center">
            <h1 class="serif-font text-4xl mb-8">Login</h1>
            
            <form class="space-y-4 mb-6">
                <input type="email" placeholder="Email" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black" required>
                <input type="password" placeholder="Password" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black" required>
                <div class="text-left">
                    <a href="#" class="text-xs text-gray-500 underline">Forgot your password?</a>
                </div>
                <button type="submit" class="w-full bg-black text-white py-3 text-sm tracking-widest hover:bg-gray-800 transition">SIGN IN</button>
            </form>

            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">OR</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            <!-- Google Login Button -->
            <a href="<?php echo $google_login_url; ?>" class="w-full flex items-center justify-center py-3 border-2 border-stone-400 text-gray-900 rounded hover:bg-stone-50 transition cursor-pointer">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-3" alt="Google">
    Continue with Google
</a>

            <p class="mt-8 text-xs text-gray-500">Don't have an account? <a href="<?php echo $google_login_url; ?>" class="text-black underline">Create one</a></p>
        </div>
    </main>

    <script src="js/main.js"></script>
</body>
</html>