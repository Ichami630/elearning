import { useState, useEffect } from 'react';
import {useParams} from 'react-router-dom'
import api from "../../utils/api";
import {toast} from "react-toastify"


export default function StudentQuizPage() {
  const [currentQuestion, setCurrentQuestion] = useState(0);
  const [answers, setAnswers] = useState({});
  const [timeLeft, setTimeLeft] = useState(0);
  const [submitted, setSubmitted] = useState(false);
  const {quizId} = useParams();
  const [loading,setLoading] = useState(true);
  const {id} = JSON.parse(localStorage.getItem("user"));
  const [quizData, setQuizData] = useState({
    title: '',
    duration: 0,
    questions: [],
  });

useEffect(() => {
  const fetchQuiz = async () => {
    try {
      const res = await api.get("/controllers/action.quiz.php", {
        params: { id: quizId }
      });

      if (res.data.success) {
        const transformedQuestions = res.data.questions.map((question) => {
          const optionsArray = question.options.split(',');

          const options = {
            A: optionsArray[0]?.trim() || '',
            B: optionsArray[1]?.trim() || '',
            C: optionsArray[2]?.trim() || '',
            D: optionsArray[3]?.trim() || ''
          };

          return {
            id: question.id,
            question: question.question,
            options,
            correct: question.correct_option
          };
        });

        setQuizData({
          title: res.data.title,
          duration: res.data.duration,
          questions: transformedQuestions
        });
        setTimeLeft(res.data.duration * 60); // Convert minutes to seconds
        setLoading(false)
      }
    } catch (error) {
      console.error("Failed to fetch quiz questions", error);
    }
  };

  fetchQuiz();
}, [quizId]);



useEffect(() => {
  if (!loading && timeLeft > 0) {
    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          handleSubmit();
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(timer);
  }
}, [loading, timeLeft]);


  const handleOptionSelect = (option) => {
    setAnswers({ ...answers, [currentQuestion]: option });
  };

  const handleNext = () => {
    if (currentQuestion < quizData.questions.length - 1) {
      setCurrentQuestion(currentQuestion + 1);
    }
  };

  const handlePrevious = () => {
    if (currentQuestion > 0) {
      setCurrentQuestion(currentQuestion - 1);
    }
  };

  const handleSubmit = async () => {
    setSubmitted(true);
    let calculatedScore = 0;

    quizData.questions.forEach((question,index)=>{
        if(answers[index] === question.correct){
            calculatedScore += 1;
        }
    })

    try {
        const res = await api.post("/controllers/action.quiz.php",{
            quizId,
            score: calculatedScore,
            studentId: id
        });
        console.log(res.data)
        if(res.data.success){
            toast.success(res.data.message)
        }else{
            toast.error(res.data.message)
        }
        
    } catch (error) {
        console.error("failed to insert",error)
    }

    console.log('Submitted Answers:', answers);
    console.log("score:",calculatedScore)
    // Here you can send the answers to the backend for scoring
  };

  const question = quizData.questions[currentQuestion];

  if (loading) {
  return (
    <div className="flex items-center justify-center h-screen">
      <div className="text-lg text-gray-700">Loading quiz...</div>
    </div>
  );
}

  return (
    <div className=" flex items-center justify-center p-4">
      <div className="w-full max-w-2xl bg-white p-6 rounded-lg shadow-md">
        {!submitted ? (
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <h2 className="text-xl font-semibold">{quizData.title}</h2>
              <span className="text-sm text-red-500">
                Time Left: {Math.floor(timeLeft / 60)}:{String(timeLeft % 60).padStart(2, '0')}
              </span>
            </div>

            <div className="mt-4">
              <p className="font-medium mb-2">Question {currentQuestion + 1} of {quizData.questions.length}</p>
              <p className="text-gray-700 mb-4">{question.question}</p>

              <div className="space-y-2">
  {Object.entries(question.options).map(([key, value]) => (
    <label
      key={key}
      className={`w-full flex items-center p-3 border rounded cursor-pointer transition ${
        answers[currentQuestion] === key
          ? 'bg-blue-100 border-blue-500'
          : 'hover:bg-gray-100'
      }`}
    >
      <input
        type="radio"
        name={`question-${currentQuestion}`}
        value={key}
        checked={answers[currentQuestion] === key}
        onChange={() => handleOptionSelect(key)}
        className="mr-3 accent-blue-500"
      />
      <span>
        <span className="font-semibold">{key}.</span> {value}
      </span>
    </label>
  ))}
</div>

            </div>

            <div className="flex justify-between mt-6 ">
              <button
                onClick={handlePrevious}
                disabled={currentQuestion === 0}
                className={`px-4 py-2 - ${currentQuestion === 0 ? "cursor-not-allowed":"cursor-pointer"}  bg-gray-400 rounded bg-gray-300 text-gray-800 disabled:opacity-50`}              >
                Previous
              </button>
              {currentQuestion < quizData.questions.length - 1 ? (
                <button
                  onClick={handleNext}
                  className="px-4 py-2 cursor-pointer rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                  Next
                </button>
              ) : (
                <button
                  onClick={handleSubmit}
                  className="px-4 py-2 cursor-pointer rounded bg-green-600 text-white hover:bg-green-700"
                >
                  Submit Quiz
                </button>
              )}
            </div>
          </div>
        ) : (
          <div className="text-center space-y-4">
            <h2 className="text-2xl font-bold text-green-600">Quiz Submitted!</h2>
            <p className="text-gray-700">Thank you for completing the quiz.</p>
          </div>
        )}
      </div>
    </div>
  );
}
