<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/main.css">
</head>

<body>
    <div id="loading-screen">
        <div id="loading-icon"></div>
    </div>
    <div id="dashboard">

        <div id="menu">
            <div id="profile">
                <div id="profile-img"></div>
                <p id="profile-name"></p>
            </div>

            <div id="logout" onclick="signOut()">
                <img src="../src/images/icons/Vector (1).svg" alt="">
                <p>Logout</p>
            </div>
        </div>
        <div id="content">
            <div id="dashboard-profile"></div>
            <p id="select-this-subjects">Select this Subjects</p>
            <div id="subjects">

            </div>
        </div>
        <div id="quiz-loading-screen">
        <div id="loading-icon-quiz"></div>
        </div>
        <div id="QuizPage">
     
            <div id="questions">
                <div id="back-to-home">Back to Home</div>
                <form id="listQuestions">

                </form>
            </div>

        </div>
        <div id="viewScore">

            <img id="complete-icon" src="../src/images/icons/icons8-complete-500.svg" />
            <div id="quizScore"></div>
            <div id="completed">Completed</div>
            <div id="showAnswers">Show Answers</div>
            <div id="quizbacktohome">Back to Home</div>

        </div>
    </div>
</body>
<script type="module">
    import { viewQuestions, backtohome, userAccount } from '../scripts/showQuestions.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import { getFirestore, collection, getDocs, query, where } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";
    var firebaseConfig = {
        apiKey: "AIzaSyAlO3Mx6TmNt5dLOx5H10VOehg0nexzp3w",
        authDomain: "flutterdbit11.firebaseapp.com",
        projectId: "flutterdbit11",
        storageBucket: "flutterdbit11.appspot.com",
        messagingSenderId: "944558548912",
        appId: "1:944558548912:web:bd7a70d3e4d1b46a59d875",
    };
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);
    async function fetchData() {
        const querySnapshot = await getDocs(collection(db, "subjects")); // Change "users" to your collection name
        querySnapshot.forEach((doc) => {

            // console.log(doc.id, " => ", doc.data());
            let con = document.createElement('div');
            let text = document.createElement('p');
            text.style.color = ['#49111C', '#0B3954', '3993DD'].includes(doc.data().backgroundColor) ? '#fff' : '';
            text.textContent = doc.data().subjectname;
            text.style.fontWeight = '600';
            con.style.backgroundColor = doc.data().backgroundColor;
            con.style.cursor = 'pointer';
            con.style.padding = '20px';
            con.style.display = 'flex';
            con.style.justifyContent = 'center';
            con.style.borderRadius = '10px';
            con.id = doc.id;
            con.className = 'subjects-con';
            con.appendChild(text);
            document.getElementById('subjects').appendChild(con);
            con.onclick = (e) => {
              
                document.getElementById('content').style.display = 'none';
                document.getElementById('quiz-loading-screen').style.display = 'flex';
                setTimeout(() => {
                    document.getElementById('quiz-loading-screen').style.display = '';
                    document.getElementById('QuizPage').style.display = 'flex';
                }, 2000);
                viewQuestions(e.target.id, doc.data().subjectname);
            }

            // You can manipulate the DOM or do other actions here
        });
    };

    fetchData();
    document.getElementById('back-to-home').addEventListener('click', () => {
        backtohome();
    });
    fetch('../widgets/userdetails.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 'uid': localStorage.getItem('uid') })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response is not ok.');
            }
            return response.json();
        })
        .then(data => {
            // console.log(data.photoUrl);
            // Set the background image using the style property
            userAccount.displayName = data.displayName.toLowerCase();
            userAccount.uid = localStorage.getItem('uid');

        })
        .catch(error => {
            console.error('Error fetching user details:', error);
        });


</script>
<script>
    
    function signOut() {
        // Remove the uid from local storage
        localStorage.removeItem('uid');
        // Redirect to the login page or index page
        window.location.reload();
    }
    if (localStorage.getItem('uid') === null) {
        window.location.href = '../index.php';
    }
    document.addEventListener('DOMContentLoaded', () => {

        // Check if uid is null and redirect if necessary
        fetch('../widgets/userdetails.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 'uid': localStorage.getItem('uid') })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response is not ok.');
                }
                return response.json();
            })
            .then(data => {
                // console.log(data.photoUrl);
                // Set the background image using the style property
                document.getElementById('profile-img').style.backgroundImage = `url(${data.photoUrl})`;
                document.getElementById('profile-name').textContent = data.displayName.toLowerCase();
                let dashprof = document.createElement('div');
                let profname = document.createElement('div');
                dashprof.id = 'dashboard-profile-img';
                dashprof.style.backgroundImage = `url(${data.photoUrl})`;
                profname.textContent = data.displayName;
                document.getElementById('dashboard-profile').appendChild(dashprof);
                document.getElementById('dashboard-profile').appendChild(profname);
                setTimeout(() => {
                    // Hide the loading screen
                    document.getElementById('loading-screen').style.display = 'none';

                    // Show the main content

                    // Display the fetched data in the #data-output element
                }, 2000);

            })
            .catch(error => {
                console.error('Error fetching user details:', error);
            });
    });
    // console.log('updated');
    // Define the signOut function



</script>

</html>