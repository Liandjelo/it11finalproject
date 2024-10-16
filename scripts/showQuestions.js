
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getFirestore, collection, getDocs, query, where, addDoc, setDoc, doc } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";

export async function viewQuestions(x,y) {
    let firebaseInitialized = false;
    console.log(userAccount.uid);
    console.log(y);
    if(!firebaseInitialized){
        let firebaseConfig = {
            apiKey: "AIzaSyAlO3Mx6TmNt5dLOx5H10VOehg0nexzp3w",
            authDomain: "flutterdbit11.firebaseapp.com",
            projectId: "flutterdbit11",
            storageBucket: "flutterdbit11.appspot.com",
            messagingSenderId: "944558548912",
            appId: "1:944558548912:web:bd7a70d3e4d1b46a59d875",
        };
        const app = initializeApp(firebaseConfig);
        firebaseInitialized = true;
        const db = getFirestore(app);
        const table = collection(db, "questions");
        const q = query(table, where("subjectID", "==", x));
        try {
            const querySnapshot = await getDocs(q); // Get the documents that match the query
            let correctAnswers = [];
            let item = 1;
            
            querySnapshot.forEach((doc) => {
                console.log(doc.id, " => ", doc.data());
            
                let itemCon = document.createElement('div');
                itemCon.style.display = 'flex';
                itemCon.style.flexDirection = 'column';
                let questions = document.createElement('p');
                let answers = document.createElement('div');
                answers.style.display = 'grid';
                answers.style.gridTemplate = '1fr/repeat(2,1fr)';
            
                // Push the correct answer for the current question
                correctAnswers.push(doc.data().answer);
            
                // Display choices for the current question
                doc.data().choices.forEach(element => {
                    let answerCon  = document.createElement('div');
                    let labelAnswer = document.createElement('p');
                    labelAnswer.className = `choices-item${item}`;
                    let answerItem = document.createElement('input');
                    
                    answerCon.style.display = 'flex';
                    answerItem.type = 'radio';
                    answerItem.name =  `item ${item}`;
                    answerItem.value = element;
                    answerItem.setAttribute("required", true);
                    answerItem.className = 'choices';
                    labelAnswer.textContent = element;
                    labelAnswer.style.cursor = 'pointer';
            
                    answerCon.appendChild(answerItem);
                    answerCon.appendChild(labelAnswer);
                    answers.appendChild(answerCon);
                });
            
                questions.textContent = `${item}. ${doc.data().question}`;
                item++; // Increment item for each question
                questions.style.backgroundColor = "#fff";
                questions.style.padding = '10px';
                questions.style.borderRadius = '10px';
                answers.style.borderRadius = '10px';
                answers.style.backgroundColor = "#fff";
                itemCon.appendChild(questions);
                itemCon.appendChild(answers);
                document.getElementById('listQuestions').appendChild(itemCon);
            });
            
            console.log(correctAnswers); // Check the correct answers array
            
            let submit = document.createElement('input');
            submit.type = 'submit';
            submit.id = 'submit-button';
            submit.value = 'Submit'; // Use value for input buttons
            
            document.getElementById('listQuestions').appendChild(submit);
            let textscore = document.getElementById('quizScore');
            // Handle form submission and compute score
            document.getElementById('listQuestions').onsubmit = (event) => {
                event.preventDefault();  // Prevent the form from submitting
                
                document.getElementById('quizbacktohome').addEventListener('click',()=>{
                    document.getElementById('QuizPage').style.display = '';
                    document.getElementById('viewScore').style.display = '';
                    document.getElementById('back-to-home').style.display = '';
                    backtohome();
                });
                document.getElementById('QuizPage').style.display = 'none';
                document.getElementById('viewScore').style.display = 'flex';
                document.getElementById('back-to-home').style.display = 'none';
              
                document.getElementById('showAnswers').addEventListener('click',()=>{
                    document.getElementById('QuizPage').style.display = 'flex';
                    document.getElementById('viewScore').style.display = '';
                    document.getElementById('back-to-home').style.display = '';
                    document.getElementById('submit-button').value = 'View Score';
                });
                Array.from(document.getElementsByClassName('choices')).forEach(e=>{
                    e.disabled = true;
                });
                
                let score = 0;
                for (let index = 0; index < item; index++) {
                    Array.from(document.getElementsByClassName(`choices-item${index+1}`)).forEach(e=>{
                    if (e.textContent.trim() === correctAnswers[index].trim()){
                        e.style.color = '#36eb4e';
                    }
               });
                    
                }
     
                // Loop through each question
                for (let i = 1; i < item; i++) {
                    // Get the user's selected answer for the current question
                    let userAnswer = document.querySelector(`input[name="item ${i}"]:checked`);
            
                    if (userAnswer) {
                        // Check if the selected answer matches the correct answer
                        if (userAnswer.value.trim() === correctAnswers[i - 1].trim()) { 
                           // Match array indexing
                            score++;  // Increase score if the answer is correct
                        }
                    } else {
                        console.log(`No answer selected for question ${i}`);
                    }
                }
            
                textscore.textContent = `You got ${score}/${item - 1}`;
                addScore(userAccount.uid,  capitalizeWords(userAccount.displayName), y, `${score}/${item-1}`);
            };
            
        } catch (error) {
            console.error("Error fetching documents: ", error);
        }
    }
    
   
   
  
  
    
 
   
    
}

export function backtohome(){
    document.getElementById('QuizPage').style.display = 'none';
        document.getElementById('content').style.display = '';
        document.getElementById('listQuestions').innerHTML = '';
 }
 export class userAccount{
    static displayName;
    static uid;
}
 async function addScore(x,y,z,a){
    let firebaseInitialized = false;
    if(!firebaseInitialized){
        let firebaseConfig = {
            apiKey: "AIzaSyAlO3Mx6TmNt5dLOx5H10VOehg0nexzp3w",
            authDomain: "flutterdbit11.firebaseapp.com",
            projectId: "flutterdbit11",
            storageBucket: "flutterdbit11.appspot.com",
            messagingSenderId: "944558548912",
            appId: "1:944558548912:web:bd7a70d3e4d1b46a59d875",
        };
        const app = initializeApp(firebaseConfig);
        firebaseInitialized = true;
        const db = getFirestore(app);
       
        try{
            const table = collection(db, "score");
            const q = query(table, where("subjectName", "==", z), where('userId', "==", x));
            const checkScore = await getDocs(q);
            if(!checkScore.empty){
                checkScore.forEach(async (e)=>{
                    console.log(e.id, "=> ", e.data());
                  if(e.data().score !== null){
                   await setDoc(doc(db,"score", e.id), {userId: x, userName: y, subjectName: z, score:a}, { merge: true });
                  }

                });
            }
            else{
                await addDoc(table, {userId: x, userName: y, subjectName: z, score:a});
            }
           
        //    await addDoc(table, {
        //         userId: x,   
        //         userName: y,
        //         subjectName: z, // User ID
        //         score: a,        // Score value
        //     });
            console.log('Successfully added to firestore.')
        }
        catch (e){
            console.error('error: ', e);
        }
       
    }
 }
 function capitalizeWords(string) {
    return string.replace(/\b\w/g, char => char.toUpperCase());
}
async function setDocument(x,y,z,a,b){
    return await setDoc(x, {userId: y, userName: z, subjectName: a, score:b}, { merge: true });
}
