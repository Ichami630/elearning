import React, { useEffect, useState } from 'react';
import { NavLink,useNavigate } from 'react-router-dom';
import FormModal from '../../components/FormModal';
import Table from '../../components/Table';
import TableSearch from '../../components/TableSearch';
import api from '../../utils/api';

const columns = [
  { header: "Course", accessor: "course" },
  { header: "Instructor", accessor: "instructor",className:"hidden md:table-cell" },
  { header: "Quiz Title", accessor: "quiz title",className:"hidden md:table-cell" },
  { header: "Department", accessor: "department",className:"hidden md:table-cell" },
  { header: "Date", accessor: "date",className:"hidden md:table-cell" },
  { header: "Actions", accessor: "actions" }
];

const Quiz = () => {
    const {role,id} = JSON.parse(localStorage.getItem('user'));
  const [quizzes, setQuizzes] = useState([]);
  const [isOpen, setIsOpen] = useState(false);
  const [attemptedQuizIds, setAttemptedQuizIds] = useState([]);
  const [selectedQuizId,setSelectedQuizId] = useState(null);

  const navigate = useNavigate();
useEffect(() => {
  const quizzes = async () => {
    try {
      const res = await api.get("/controllers/action.quiz.php",{
        params:{role,studentId: id}
      });
      // console.log(res.data)
      if (res.data.success) {
        setQuizzes(res.data.quizzes);
      } else {
        console.error("API returned error:", res.data.message);
      }
    } catch (error) {
      console.error("Failed to fetch students", error);
    }
  };
  quizzes();

  //check if the student has already taken on this quiz
  const quizAttempt = async () =>{
    try {
        const res = await api.get("controllers/action.getQuizAttemp.php",{
            params:{role,id}
        });
        if(res.data.success){
            const ids = res.data.attemptedQuizzes.map(q => q.id); // get just the IDs
            setAttemptedQuizIds(ids);
        }
    } catch (error) {
        console.error("Failed to fetch quiz attempt", error);
    }
  }
  quizAttempt();
}, [role,id]);



  const renderRow = (item) => (
    <tr key={item.id} className="border-b border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200">
      <td className="flex items-center gap-4 p-4">
        <div className="flex flex-col">
          <h3 className="font-semibold">{item.course_title}</h3>
          <p className="text-xs text-gray-500">{item.code}, <span>Level {item.level}</span></p>
        </div>
      </td>
      <td className="hidden md:table-cell">{item.lecturer}</td>
      <td className="hidden md:table-cell">{item.title}</td>
      <td className="hidden md:table-cell">{item.department}</td>
      <td className="hidden md:table-cell">{item.date_added.substring(0,10)}</td>
      <td>
        {role === 'student' && (
            attemptedQuizIds.includes(item.id) ? (
                <button className="text-center bg-gray-400 p-2 text-white rounded-md opacity-50 cursor-not-allowed w-[100px]" title="You have already attempted this quiz" disabled>
                 Attempted
                </button>
            ) : (
                <button onClick={() => {
                    setSelectedQuizId(item.id);
                    setIsOpen(true);
                }} className="text-center bg-blue-500 p-2 w-[100px] text-white rounded-md cursor-pointer" title="Attempt this quiz">
                Attempt Quiz
                </button>
            )
        )}

      </td> 
    </tr>
  );

  //show the modal for comfirmation upon attempting quiz
  if(isOpen){
    return(
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div className="relative z-[60] w-[90%] md:w-[70%] lg:w-[60%] xl:w-[50%] 2xl:w-[40%] bg-white p-6 rounded-md max-h-[90vh] overflow-auto hide-scrollbar">
                <div className="flex items-center flex-col gap-4">
                    <h4 className="text-xl font-semibold">Are you sure you want to attempt this quiz?</h4>
                    <div className="text-sm text-red-500">Note: You have just one trial</div>
                    <div className="flex flex-row gap-2">
                        <button className="text-white bg-blue-500 p-2 rounded-md cursor-pointer" onClick={()=>{
                            setIsOpen(false);
                            navigate(`/dashboard/quiz/${selectedQuizId}`);
                        }}>Take Quiz</button>
                        <button className="text-white bg-red-400 p-2 rounded-md cursor-pointer" onClick={()=>(setIsOpen(false))}>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    )
  }

  return (
    <div className="p-4 bg-white rounded-md">
        {/* TOP */}
        <div className="flex items-center justify-between">
            <h1 className="hidden md:block text-lg font-semibold">All Quizzes</h1>
            <div className="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <TableSearch />
            <div className="flex items-center gap-4 self-end">
                <button className="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-200 cursor-pointer" title='sort by'>
                    <img src="/sort.png"  alt="" width={14} height={14} />
                </button>
                {role === "lecturer" && (
                <FormModal table="quiz" type="create"/>
                )}
            </div>
            </div>
        </div>
      <Table columns={columns} data={quizzes} renderRow={renderRow} noResult={"No Quiz Found"} />
    </div>
  );
};

export default Quiz;
