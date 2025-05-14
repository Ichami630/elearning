import React, { useEffect, useState } from 'react';
import { NavLink } from 'react-router-dom';
import FormModal from '../../components/FormModal';
import { FaEye,FaTrash } from 'react-icons/fa';
import Table from '../../components/Table';
import TableSearch from '../../components/TableSearch';
import api from '../../utils/api';

const columns = [
  { header: "Info", accessor: "info" },
  { header: "Department", accessor: "department",className:"hidden md:table-cell" },
  { header: "Level", accessor: "level",className:"hidden md:table-cell" },
  { header: "Actions", accessor: "actions" }
];

const Course = () => {
    const {role} = JSON.parse(localStorage.getItem('user'));
  const [students, setStudents] = useState([]);

useEffect(() => {
  const fetchStudents = async () => {
    try {
      const res = await api.get("/controllers/action.student.php");
      if (res.data.success) {
        setStudents(res.data.students);
      } else {
        console.error("API returned error:", res.data.message);
      }
    } catch (error) {
      console.error("Failed to fetch students", error);
    }
  };
  fetchStudents();
}, []);


  const renderRow = (item) => (
    <tr key={item.id} className="border-b border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200">
      <td className="flex items-center gap-4 p-4">
        <img src="/profile.png" alt="" width={40} height={40} className="md:hidden xl:block w-10 h-10 rounded-full object-cover hidden lg:table-cell" />
        <div className="flex flex-col">
          <h3 className="font-semibold">{item.name}</h3>
          <p className="text-xs text-gray-500">{item.email}</p>
        </div>
      </td>
      <td className="hidden md:table-cell">{item.department}</td>
      <td className="hidden lg:table-cell">{item.level}</td>
      <td><div className="flex items-center gap-2">
            <NavLink to={`/dashboard/students/${item.id}`} title='view'>
                <button className="cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-blue-100">
                    <FaEye className='w-4 h-4' />
                </button>
            </NavLink>
            {role === 'admin' && (
                <button title="delete this lecturer" className='cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-gray-200'>
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
            <h1 className="hidden md:block text-lg font-semibold">All Students</h1>
            <div className="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <TableSearch />
            <div className="flex items-center gap-4 self-end">
                <button className="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-200 cursor-pointer" title='sort by'>
                    <img src="/sort.png"  alt="" width={14} height={14} />
                </button>
                {role === "admin" && (
                <FormModal table="course" type="create"/>
                )}
            </div>
            </div>
        </div>
      <Table columns={columns} data={students} renderRow={renderRow} noResult={"No Student Found"} />
    </div>
  );
};

export default Course;
