import React, { useEffect, useState } from 'react';
import { NavLink } from 'react-router-dom';
import FormModal from '../../components/FormModal';
import { FaEye,FaTrash } from 'react-icons/fa';
import Table from '../../components/Table';
import TableSearch from '../../components/TableSearch';
import api from '../../utils/api';

const columns = [
  { header: "Courses", accessor: "courses" },
  { header: "Lecturers", accessor: "Lecturers",className: "hidden md:table-cell", },
  { header: "Actions", accessor: "actions" }
];

const Enrollment = () => {
    const {role,id} = JSON.parse(localStorage.getItem("user"));
  const [courses, setCourses] = useState([]);

  useEffect(() => {
    const fetchCourses = async () => {
      try {
        const res = await api.get("/controllers/action.getCourses.php",{
            params: {
                role: role,
                id: id
            }
        });
        if (res.data.success) {
          // Split courses and levels into arrays
          setCourses(res.data.courses);
        }
      } catch (error) {
        console.error("Failed to fetch enrolled courses", error);
      }
    };
    fetchCourses();
  }, [role,id]);

  const renderRow = (item) => (
    <tr key={item.id} className="border-b border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200">
      <td className="flex items-center gap-4 p-4">
        <div className="flex flex-col">
          <h3 className="font-semibold">{item.title}</h3>
          <p className="text-xs text-gray-500">{item.code}</p>
        </div>
      </td>
      <td className="hidden md:table-cell">Mr John</td>
      <td><div className="flex items-center gap-2">
            <NavLink to={`/dashboard/teachers/${item.id}`} title='view'>
                <button title='access course material' className="cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-blue-100">
                    <FaEye className='w-4 h-4' />
                </button>
            </NavLink>
            {role === 'student' && (
                <button title="uneroll to this course" className='cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-gray-200'>
                    <FaTrash className='w-4 h-4 text-red-400' />
                </button>
            )}
        </div></td> 
    </tr>
  );

  return (
    <div className="p-4 bg-white rounded-md">
        {/* TOP */}
        <div className="flex items-center justify-between">
            <h1 className="hidden md:block text-lg font-semibold">Enrolled Courses</h1>
            <div className="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <TableSearch />
            <div className="flex items-center gap-4 self-end">
                <button className="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-200 cursor-pointer" title='sort by'>
                    <img src="/sort.png"  alt="" width={14} height={14} />
                </button>
                {role === "student" && (
                <FormModal table="enrollement" type="create" title="enroll to a new course"/>
                )}
            </div>
            </div>
        </div>
      <Table columns={columns} data={courses} renderRow={renderRow} noResult={"You are not yet enrolled to any course"} />
    </div>
  );
};

export default Enrollment;
