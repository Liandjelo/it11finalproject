<?php 

require '../vendor/autoload.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
$serviceAccount = '../flutterdbit11-firebase-adminsdk-5hzyk-a909625ead.json'; 

$firebase = (new Factory())->withServiceAccount($serviceAccount);
$auth = $firebase->createAuth(); // For user authentication
$database = $firebase->createDatabase(); // For Realtime Database access

$data = json_decode(file_get_contents('php://input'), true);
try {
    // Fetch user by uid
    
    $user = $auth->getUser($data['uid']);
    
    // Return user profile data
    echo json_encode([
        'uid' => $user->uid,
        'displayName' => $user->displayName,
        'email' => $user->email,
        'photoUrl' => $user->photoUrl, // This contains the URL to the profile picture
    ]);
} catch (FirebaseException $e) {
    echo 'Error fetching user profile: ' . $e->getMessage();
    return null;
}
?>