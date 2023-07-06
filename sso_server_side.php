<?php
// Start the session
session_start();

// Initialize the Google API client (Use the Client_secret.json to fill it up)
require_once 'path/to/vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('https://example.com/login.php');
$client->setScopes(['email', 'profile']);

// Handle the Google Sign-In response
function handleGoogleSignIn($client) {
    if (isset($_POST['idtoken'])) {
        try {
            $payload = $client->verifyIdToken($_POST['idtoken']);
            if ($payload) {
                $client->setAccessToken($_POST['idtoken']);
                $_SESSION['google_access_token'] = $_POST['idtoken'];
                $oauth2 = new Google_Service_Oauth2($client);
                $user_info = $oauth2->userinfo->get();
                $_SESSION['user_id'] = $user_info->id;
                // Redirect to the protected page
                header('Location: https://example.com/protected.php');
                exit;
            } else {
                // Invalid token
                echo 'Invalid token';
            }
        } catch (Exception $e) {
            // Error
            echo $e->getMessage();
        }
    }
}

// Check if the user is already signed in
if (isset($_SESSION['google_access_token'])) {
    $client->setAccessToken($_SESSION['google_access_token']);
    $oauth2 = new Google_Service_Oauth2($client);
    $user_info = $oauth2->userinfo->get();
    $_SESSION['user_id'] = $user_info->id;
    // Redirect to the protected page
    header('Location: https://example.com/protected.php');
    exit;
}

// Call the handleGoogleSignIn function when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handleGoogleSignIn($client);
}
?>
