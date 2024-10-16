import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithRedirect, getRedirectResult } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js";
import { setLogLevel } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
setLogLevel('debug');
const firebaseConfig = {
    apiKey: "AIzaSyAlO3Mx6TmNt5dLOx5H10VOehg0nexzp3w",
    authDomain: "flutterdbit11.firebaseapp.com",
    projectId: "flutterdbit11",
    storageBucket: "flutterdbit11.appspot.com",
    messagingSenderId: "944558548912",
    appId: "1:944558548912:web:bd7a70d3e4d1b46a59d875",
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);
const auth = getAuth(firebaseApp);

const signInWithGoogle = async () => {
    sessionStorage.setItem("redirectURL", window.location.href); // Store current URL
    await signInWithRedirect(auth, new GoogleAuthProvider());
};

// Set up event listener for sign-in button
document.addEventListener('DOMContentLoaded', () => {
    const signinButton = document.getElementById('google-button');
    signinButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent default action
        await signInWithGoogle();
    });
});

// Handle redirect result
const handleRedirectResult = async () => {
    try {
        const result = await getRedirectResult(auth);
        if (result) {
            const user = result.user;
            const token = await user.getIdToken();

            // Your fetch logic here
            const response = await fetch("widgets/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ token })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            if (data.success) {
                const redirectURL =  'pages/home.php'; // Fallback URL
                localStorage.setItem("uid", data.user.uid);
                window.location.href = redirectURL; // Redirect to saved URL
            } else {
                console.log("Login failed: " + data.message);
            }
        } else {
            console.log("No result from redirect or user not signed in.");
        }
    } catch (error) {
        console.error("Error during sign in redirect:", error);
    }
};

// Call to handle the redirect result
window.onload = handleRedirectResult;

// Handle the redirect result after the sign-in


// Call this function to handle the redirect result when your page loads

setTimeout(() => {
    // Hide the loading screen
    document.getElementById('loading-screen').style.display = 'none';

    // Show the main content

    // Display the fetched data in the #data-output element
}, 2000);

