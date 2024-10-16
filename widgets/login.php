<?php
require '../vendor/autoload.php'; // Ensure this path is correct


use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;

// Load your Firebase service account credentials
$serviceAccount = '../flutterdbit11-firebase-adminsdk-5hzyk-a909625ead.json';  // Added missing slash

// Initialize Firebase with the service account
$firebase = (new Factory())->withServiceAccount($serviceAccount);

// Create instances of Auth and Database
$auth = $firebase->createAuth(); // For user authentication
$database = $firebase->createDatabase(); // For Realtime Database access
header("Access-Control-Allow-Origin: *");  // Allow from any origin (insecure, replace with specific domain in production)
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);  // Preflight request should return without any response
}

// Read the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];

try {
    // Verify the token with Firebase
    $verifiedIdToken = $auth->verifyIdToken($token); // Ensure $auth is initialized properly

    // Get user ID from the verified token's claims
    $uid = $verifiedIdToken->claims()->get('sub'); // Use claims() method to access claims

    // You can now use the user ID to fetch user information or create a new user in your database
    $user = $auth->getUser($uid);
    
    // Prepare a success response with user information
    echo json_encode([
        'success' => true,
        'user' => [
            'uid' => $uid,           // Include UID if needed
            'email' => $user->email  // Returning user's email
        ]
    ]);
} catch (FirebaseException $e) {
    // Catch Firebase-specific errors
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage() // Error message for debugging
    ]);
} catch (Exception $e) {
    // Catch any other errors
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage() // Error message for debugging
    ]);
}
?>
