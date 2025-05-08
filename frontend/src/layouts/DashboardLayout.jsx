import {Outlet,useNavigate,NavLink} from 'react-router-dom'
import { useEffect } from 'react'
import checkSession from '../utils/checkSession'
import Navbar from '../components/Navbar'
import Menu from '../components/Menu'

const DashboardLayout = () => {
  const navigate = useNavigate()
  useEffect(()=> {
    const verifySession = async ()=>{
      const isActive = await checkSession();
      if(!isActive){
        localStorage.removeItem('user')
        navigate('/login')
      }
    }
    verifySession()
  },[navigate])
  return (
    <>
        <div className='flex h-screen overflow-hidden'>
            <aside className='p-4 w-[14%] md:w-[8%] lg:w-[16%] xl:w-[14%] overflow-y-auto hide-scrollbar'>
              <NavLink to="/" className="flex justify-between items-center lg:justify-start gap-2">
                <img src="/logo.jpg" alt="Logo" className="w-8 h-8 rounded-full" />
                <span className="hidden lg:block text-lg font-bold">Eduspark</span>
              </NavLink>
              {/* menu bar component here */}
              <Menu/>
            </aside>
            <div className='flex flex-col flex-grow w-[86%] md:w-[92%] lg:w-[84%] xl:w-[86%] bg-[#F7F8FA]'>
              <header className="shrink-0">
                {/* header component here */}
                <Navbar/>
              </header>
              <main className='flex-grow overflow-y-auto hide-scrollbar p-4'>
                <Outlet/>
              </main>
              {/* Footer */}
              <footer className="shrink-0 h-12 bg-white px-6 flex items-center justify-center text-sm text-gray-500">
               &copy; {new Date().getFullYear()} Eduspark. All rights reserved.
              </footer>
            </div>
        </div>
    </>
  )
}

export default DashboardLayout