import {useParams} from "react-router-dom"
import {useState,useEffect} from "react";
import api from "../../utils/api";
import { FaEdit } from "react-icons/fa";
import FormModal from '../../components/FormModal';

const Feedback = () => {
    const {assignmentId,submissionId} = useParams();
    
    const [assignment, setAssignment] = useState({
        title: "",
        description: "",
        dueDate: "",
    });
    const [feedback,setFeedback] = useState({
        studentId: 0,
        grade: 0,
        feedback: ""
    });
    const { role,id } = JSON.parse(localStorage.getItem("user"));

    useEffect(() => {
        const getAssignment = async () => {
          const res = await api.get("/controllers/action.assignment.php", {
            params: { assignmentId },
          });
          if (res.data.success) {
            setAssignment({
              title: res.data.title,
              description: res.data.description,
              dueDate: res.data.dueDate,
            });
          }
        };
        //get the feedback left by the lecturer from the student assignment
        const getFeedback = async ()=> {
            const res = await api.get("/controllers/action.feedback.php",{
                params: {submissionId}
            });
            if(res.data.success){
                setFeedback({
                    studentId: res.data.submission.student_id,
                    grade: res.data.submission.grade,
                    feedback: res.data.submission.feedback
                });
            }
        }
        
        getFeedback();
        getAssignment();
    }, [assignmentId,submissionId]);
  return (
    <>
      {id === feedback.studentId || role === 'lecturer' ? (
        <>
          <div className="bg-white p-4 rounded shadow text-gray-800">
            <div className="flex justify-between items-center">
              <div className="text-2xl font-bold">{assignment.title}</div>
              {role === 'lecturer' && (
                <div className="cursor-pointer gap-2 flex  bottom-20 shadow-md w-[150px] items-center p-2 rounded-md bg-blue-500 ">
                  <FormModal table="feedback" type="create" rest={submissionId} /> <span className="text-md text-white text-center">Feedback</span>
                </div>
              )}
            </div>
            <div
              className="prose prose-sm sm:prose lg:prose-lg max-w-none py-4"
              dangerouslySetInnerHTML={{ __html: assignment.description }}
            />
          </div>
          {/*display the feedback if availiable or display no feedback yet if not feedback is available*/}
          <div className="bg-white p-4 rounded shadow mt-4 text-gray-800">
            <h2 className="text-xl font-semibold mb-4">Feedback</h2>
            {feedback.grade || feedback.feedback ? (
              <div className="space-y-4">
                <div>
                  <strong>Grade:</strong> {feedback.grade}
                </div>
                <div>
                  <strong>Feedback:</strong> {feedback.feedback}
                </div>
              </div>
            ) : (
              <p className="text-red-500 text-center">** No feedback yet. **</p>
            )}
          </div>
        </>
      ) : (
        <div className="bg-white p-4 rounded shadow text-gray-800">
          <h2 className="text-xl font-semibold mb-4">You are not authorized to view this feedback</h2>
        </div>
      )}
    </>
  );
}

export default Feedback