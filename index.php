<?php
// include 'pages/home.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/main.css">

</head>

<body>
    <div id="loading-screen">
        <div id="loading-icon"></div>
    </div>
    <div id="home">
        <form action="" method="post">
            <div id="logo">
                <img src="src/images/icons/Vector.svg" alt="">
                <div id="logo-h1">Juzquiz</div>
            </div>
            <div id="google-button" onclick="Signin(this)">
                <img src="src/images/icons/google-icon.svg" alt="">
                <div>
                    <p>Continue with Gmail</p>
                </div>

            </div>
            <!-- <div id="facebook-button" onclick="Signin(this)">
                <img src="src/images/icons/social_12942327 1.svg" alt="">
                <div>
                    <p>Continue with Facebook</p>
                </div>

            </div> -->
        </form>
    </div>

</body>
<script type="module">
    // main sign-in script
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import { getAuth, GoogleAuthProvider, FacebookAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js";
    // Import the setter function


    window.Signin = (e) => {
        var firebaseConfig = {
            apiKey: "AIzaSyAlO3Mx6TmNt5dLOx5H10VOehg0nexzp3w",
            authDomain: "flutterdbit11.firebaseapp.com",
            projectId: "flutterdbit11",
            storageBucket: "flutterdbit11.appspot.com",
            messagingSenderId: "944558548912",
            appId: "1:944558548912:web:bd7a70d3e4d1b46a59d875",
        };



        // Initialize Firebase
        const firebase = initializeApp(firebaseConfig);
        const auth = getAuth();
        signInWithPopup(auth, new GoogleAuthProvider())
            .then((result) => {
                const user = result.user;
                user.getIdToken().then((token) => {
                    // Send token to the server using fetch
                    fetch("widgets/login.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ token })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.href = 'pages/home.php';  // Set the global uid
                                localStorage.setItem("uid", data.user.uid);
                            } else {
                                console.log("Login failed: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error during fetch:", error);
                        });
                }).catch(error => {
                    console.error("Error getting ID token:", error);
                });
            })
            .catch((error) => {
                console.error("Error during sign in:", error);
            });
    }
    setTimeout(() => {
        // Hide the loading screen
        document.getElementById('loading-screen').style.display = 'none';

        // Show the main content

        // Display the fetched data in the #data-output element
    }, 2000);

</script>

</html>