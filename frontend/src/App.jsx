import { Children } from "react"
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

const App = () => {
  // const isAuthenticated = ()=>{
  //   return !!localStorage.getItem('token')
  // }

  const isAuthenticated = () => {   
    return false;
  }
  
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
            <Route path="/dashboard/test" element={<NotFound />} />
          </Route>
        </Routes>
      </Router>
      <ToastContainer />
    </>
  )
}

export default App