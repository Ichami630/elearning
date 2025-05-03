import {Outlet} from 'react-router-dom'

const DashboardLayout = () => {
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