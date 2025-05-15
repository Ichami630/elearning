
import { Navigate,BrowserRouter as Router,Routes,Route } from "react-router-dom"
import { ToastContainer } from "react-toastify"
import 'react-toastify/dist/ReactToastify.css'

// Layouts
import DashboardLayout from "./layouts/DashboardLayout"
import MainLayout from "./layouts/MainLayout"

//pages
import NotFound from "./components/NotFound"
import Home from "./pages/dashboard/Home"
import Login from "./pages/Login"
import Teacher from "./pages/dashboard/teacher"
import Enrollment from "./pages/dashboard/enrollment"
import Student from "./pages/dashboard/student"
import Course from "./pages/dashboard/course"
import SingleCourse from "./components/SingleCourse"
import Logout from "./pages/dashboard/logout"

const App = () => {
  const isAuthenticated = ()=>{
    return !!localStorage.getItem('user')
  }

  // const isAuthenticated = () => {   
  //   return false;
  // }
  
  const protectedRoutes = (children) => {
    return isAuthenticated() ? children : <Navigate to="/Login" />
  }
  return (
    <>
      <Router>
        <Routes>
           {/* Public Routes - Wrapped inside MainLayout */}
          <Route element={<MainLayout />}>
            <Route path="/login" element={<Login />} />
            <Route path="*" element={<NotFound />} />
          </Route>

          {/* Protected Routes - Wrapped inside DashboardLayout */}
          <Route path="/" element={
            protectedRoutes(<DashboardLayout />)
          }>
            <Route index element={<Home />} />
            <Route path="/logout" element={<Logout/>}/>
            <Route path="/dashboard/teachers" element={<Teacher />} />
            <Route path="/dashboard/enrollments" element={<Enrollment />} />
            <Route path="/dashboard/students" element={<Student />} />
            <Route path="/dashboard/subjects" element={<Course />} />
            <Route path="/dashboard/courses/:courseId" element={<SingleCourse />} />
            <Route path="*" element={<NotFound />} />
          </Route>
        </Routes>
      </Router>
      <ToastContainer />
    </>
  )
}

export default App