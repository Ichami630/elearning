import React, { useEffect, useState } from 'react'
import { NavLink } from 'react-router-dom'
import api from "../utils/api"

const CourseCard = () => {
    const user = localStorage.getItem("user");
    const {id,role} = JSON.parse(user)
    const [courses,setCourses] = useState([]);
    useEffect(()=>{
        const fetchCourses = async () => {
            try{
                const res = await api.get("/controllers/action.getCourses.php",{
                    params:{
                        id: id,
                        role: role
                    }
                });
                if(res.data.success){
                    setCourses(res.data.courses);
                }else{setCourses([])}
            }catch(error){
                console.error("error fetch courses",error);
                setCourses([]);
            }
        }
        fetchCourses();
    },[id,role]);
  return (
    <div className="bg-white p-4 rounded-md">
        <div className="flex items-center justify-between">
            <h1 className="text-xl font-semibold">{role != 'student' ?("Courses"):("Enrolled Courses")}</h1>
            <NavLink className="text-xs text-gray-400 cursor-pointer" to="/dashboard/courses">View All</NavLink>
        </div>
        {courses.length === 0 ? (
            <p className="text-center text-red-500 mt-4">No courses available.</p>
        ):(
            <div className="flex flex-col gap-4 mt-4">
                {courses.slice(0,3).map((item,index)=>(
                    <NavLink key={index} to={`/dashboard/courses/${item.id}`} className="shadow-md cursor-pointer">
                        <img
                            src={`courseThumbnail/${item.thumbnail === null ? 'networking1.jpg' : item.thumbnail}`}
                            alt="thumbnail"
                            className="w-full h-32 object-cover"
                        />
                        <div className="mt-2 p-4 font-medium text-sm text-gray-800">
                            <h2 className='hover:underline'>{item.title}</h2>
                            <p className='text-gray-400 text-xs font-normal'>{item.code}</p>
                        </div>
                    </NavLink>
                ))}
            </div>
        )}
    </div>
  )
}

export default CourseCard