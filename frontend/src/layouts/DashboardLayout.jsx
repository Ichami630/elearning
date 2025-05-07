import {Outlet,useNavigate} from 'react-router-dom'
import { useEffect } from 'react'
import checkSession from '../utils/checkSession'

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
        <div className='flex flex-col h-screen'>
            <header className='bg-gray-800 text-white p-4'>Dashboard Header</header>
            <main className='flex-grow p-4'>
            <Outlet/>
            </main>
            <footer className='bg-gray-800 text-white p-4'>Dashboard Footer</footer>
        </div>
    </>
  )
}

export default DashboardLayout