import React,{ useEffect,useState } from "react";
import api from "../utils/api";

const StudentProfile = ({id,role}) => {
    const [profile,setProfile] = useState(
        {
            name: '',
            email: '',
            department: '',
            level: '',
            enrolledCourses: [],
            pendingAssignments: 0,
            pendingQuizzes: 0
        }
    )
    useEffect(()=>{
        const getUserProfile = async () => {
            try {
                const res = await api.post("/controllers/action.profile.php", {
                    id,
                    role
                });
                console.log(res.data)
                if(res.data.success){
                    setProfile({
                        name: res.data.name,
                        email: res.data.email,
                        department: res.data.department,
                        level: res.data.level,
                        enrolledCourses: res.data.enrollment,
                        pendingAssignments: res.data.pendingAssignments,
                        pendingQuizzes: res.data.pendingQuizzes
                    })
                }
            } catch (error) {
                console.error(error)
            }
        }
        getUserProfile()
    },[id,role])
  return (
    <div className="min-h-screen bg-gray-100 py-10 px-4">
      <div className="max-w-6xl mx-auto bg-white shadow-md rounded-lg overflow-hidden grid grid-cols-1 md:grid-cols-4">
        
        {/* SIDEBAR */}
        <aside className="bg-blue-50 p-6 md:col-span-1 border-r">
          <div className="flex flex-col items-center text-center">
            <img
              src="/profile.png"
              alt="Student"
              className="w-24 h-24 rounded-full object-cover"
            />
            <h2 className="mt-4 text-xl font-semibold">{profile.name}</h2>
            <p className="text-sm text-gray-600">Level {profile.level} - {profile.department}</p>
          </div>

          <div className="mt-6 space-y-3 text-sm">
            <div className="bg-white shadow p-3 rounded-md">
              <p className="text-gray-500">Courses Enrolled</p>
              <p className="text-lg font-bold text-blue-700">{profile.enrolledCourses.length}</p>
            </div>
            <div className="bg-white shadow p-3 rounded-md">
              <p className="text-gray-500">Assignments Pending</p>
              <p className="text-lg font-bold text-yellow-600">{profile.pendingAssignments}</p>
            </div>
            <div className="bg-white shadow p-3 rounded-md">
              <p className="text-gray-500">Quizzes Completed</p>
              <p className="text-lg font-bold text-green-600">{profile.pendingQuizzes}</p>
            </div>
          </div>
        </aside>

        {/* MAIN PANEL */}
        <main className="p-6 md:col-span-3">
          {/* Personal Info */}
          <section className="mb-8">
            <h3 className="text-xl font-bold mb-2">Personal Information</h3>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
              <p><strong>Email:</strong> {profile.email}</p>
              <p><strong>Phone:</strong> +237 6XX XXX XXX</p>
              <p><strong>Matricule:</strong> 21CMXXXX</p>
              <p><strong>Department:</strong> {profile.department}</p>
            </div>
          </section>

          {/* Enrolled Courses */}
          <section className="mb-8">
            <h3 className="text-xl font-bold mb-2">Enrolled Courses</h3>
            <ul className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              {profile.enrolledCourses.map((course, idx) => (
                <li key={idx} className="bg-gray-50 p-4 rounded-md shadow hover:bg-gray-100">
                  <h4 className="font-semibold">{course.title}</h4>
                  <p className="text-sm text-gray-500 mt-1">Lecturer: {course.lecturer}</p>
                </li>
              ))}
            </ul>
          </section>

          {/* Assignments */}
          <section className="mb-8">
            <h3 className="text-xl font-bold mb-2">Recent Assignments</h3>
            <div className="overflow-auto">
              <table className="min-w-full text-sm text-left">
                <thead className="bg-gray-100 text-gray-600 uppercase">
                  <tr>
                    <th className="p-2">Course</th>
                    <th className="p-2">Title</th>
                    <th className="p-2">Status</th>
                    <th className="p-2">Due Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr className="border-b">
                    <td className="p-2">Web Dev</td>
                    <td className="p-2">Build a Portfolio</td>
                    <td className="p-2 text-yellow-600 font-medium">Pending</td>
                    <td className="p-2">June 20, 2025</td>
                  </tr>
                  <tr>
                    <td className="p-2">AI Basics</td>
                    <td className="p-2">Neural Networks Intro</td>
                    <td className="p-2 text-green-600 font-medium">Submitted</td>
                    <td className="p-2">June 10, 2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          {/* Quizzes */}
          <section>
            <h3 className="text-xl font-bold mb-2">Recent Quiz Results</h3>
            <ul className="space-y-3 text-sm">
              <li className="bg-green-50 p-3 rounded shadow flex justify-between">
                <span>Data Structures - Quiz 1</span>
                <span className="font-semibold text-green-700">85%</span>
              </li>
              <li className="bg-yellow-50 p-3 rounded shadow flex justify-between">
                <span>Discrete Math - Quiz 2</span>
                <span className="font-semibold text-yellow-700">65%</span>
              </li>
            </ul>
          </section>
        </main>
      </div>
    </div>
  );
};

export default StudentProfile;
