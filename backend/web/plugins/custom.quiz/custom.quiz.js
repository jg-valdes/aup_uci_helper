// Variables and functions
var slides;
var currentSlide = 0;

var aupUciQuiz = function(){
    // Variables
    var _questions = [];
    const quizContainer = document.getElementById('quiz');
    const resultsContainer = document.getElementById('results');
    const quizForm = document.getElementById('quiz-form');
    const submitButton = document.getElementById('submit_quiz');

    return {
        init: function(questions=[]){
            _questions = questions;
            this.buildQuiz();
            // Show the first slide
            slides = document.querySelectorAll(".slide");
            showSlide(currentSlide);

            // // Event listeners
            submitButton.addEventListener('click', this.showResults);

        },
        buildQuiz: function () {
            // variable to store the HTML output
            const output = [];

            // for each question...
            _questions.forEach(
                (currentQuestion, questionNumber) => {

                    // variable to store the list of possible answers
                    const answers = [];

                    // and for each available answer...
                    for(metricId in currentQuestion.answers){

                        // ...add an HTML radio button
                        answers.push(
                            `<label>
                      <input type="radio" name="metric_${currentQuestion.questionId}" value="${metricId}">
                      ${currentQuestion.answers[metricId]}
                    </label>`
                        );
                    }

                    // add this question and its answers to the output
                    output.push(
                        `<div class="slide">
                    <div class="question"> ${currentQuestion.question} </div>
                    <div class="answers"> ${answers.join("")} </div>
                  </div>`
                    );
                }
            );

            // finally combine our output list into one string of HTML and put it on the page
            quizContainer.innerHTML = output.join('');
        },
        showResults: function () {
            // gather answer containers from our quiz
            const answerContainers = quizContainer.querySelectorAll('.answers');

            // keep track of user's answers
            let numCorrect = 0;

            for(node of answerContainers){
                quizForm.appendChild(node);
            }
            quizForm.submit();
        }

    }
}();

const submitButton = document.getElementById('submit_quiz');

// Pagination
const previousButton = document.getElementById("previous");
const nextButton = document.getElementById("next");

// Event listeners
previousButton.addEventListener("click", showPreviousSlide);
nextButton.addEventListener("click", showNextSlide);

function showSlide(n) {
    slides[currentSlide].classList.remove('active-slide');
    slides[n].classList.add('active-slide');
    currentSlide = n;
    if(currentSlide === 0){
        previousButton.style.display = 'none';
    }
    else{
        previousButton.style.display = 'inline-block';
    }
    if(currentSlide === slides.length-1){
        nextButton.style.display = 'none';
        submitButton.style.display = 'inline-block';
    }
    else{
        nextButton.style.display = 'inline-block';
        submitButton.style.display = 'none';
    }
}

function showNextSlide() {
    showSlide(currentSlide + 1);
}

function showPreviousSlide() {
    showSlide(currentSlide - 1);
}
