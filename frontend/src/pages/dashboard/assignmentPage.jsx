import { useState,useEffect } from "react";
import Table from "../../components/Table";
import api from "../../utils/api";
import { NavLink } from "react-router-dom";
import { FaEye, FaTrash } from "react-icons/fa";


const assignmentColumns = [
  { header: "Course", accessor: "course",className:"hidden md:table-cell" },
  { header: "Title", accessor: "title",className:"hidden md:table-cell" },
  { header: "Due Date", accessor: "dueDate"},
  {header: "Action",accessor:"action"}
]


const AssignmentPage = () => {
    const {role,id} = JSON.parse(localStorage.getItem('user'))
    const [assignments,setAssignments] = useState([]);
    useEffect(()=>{
        //fetch all asignments for this course
            const assignments = async ()=>{
              const res = await api.get("/controllers/action.assignment.php",{
                params:{studentId: id}
              })
              console.log(res.data);
              if(res.data.success){
                setAssignments(res.data.assignments);
              }
            }
            assignments();
    },[id])

    const renderAssignmentRow = (item) => (
      <tr key={item.id} className="border-b text-left border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200">
        <td className="flex gap-4 p-4">
            <div className="flex flex-col">
                <h3 className="font-semibold">{item.course_title}</h3>
                <p className="text-xs text-gray-500">{item.code}</p>
            </div>
        </td>
        <td className="hidden md:table-cell">{item.title}</td>
        <td>{item.due_date}</td>
        <td><div className="flex items-center gap-2">
          <NavLink to={`/dashboard/assignment/${item.id}`} title='view'>
              <button title='view this assignment' className="cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-blue-100">
                  <FaEye className='w-4 h-4' />
              </button>
          </NavLink>
          {role === 'lecturer' && (
              <button title="delete this assignment" className='cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-gray-200'>
                  <FaTrash className='w-4 h-4 text-red-400' />
              </button>
          )}
        </div></td> 
      </tr>
    );
  return (
    <div className="p-4 bg-white rounded-md">
        <h2 className="text-2xl font-semibold mb-4">Assignments</h2>
        <Table columns={assignmentColumns} data={assignments} renderRow={renderAssignmentRow} noResult={"No assignment available for your class"} />
    </div>
  )
}

export default AssignmentPage