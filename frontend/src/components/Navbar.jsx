import { FaBeer,FaSearch,FaCommentDots,FaBullhorn,FaUserCircle} from "react-icons/fa";

const Navbar = () => {
    const user = localStorage.getItem('user')
    const name = JSON.parse(user).name
    const role = JSON.parse(user).role
  return (
    <div className='flex items-center justify-between p-4'>
      {/* SEARCH BAR */}
      <div className='hidden md:flex items-center gap-2 text-xs rounded-full ring-[1.5px] ring-gray-300 px-2'>
        <FaSearch className="w-3 h-3 text-gray-400"/>
        <input type="text" placeholder="Search..." className="w-[200px] p-2 bg-transparent outline-none"/>
      </div>
      {/* ICONS AND USER */}
      <div className='flex items-center gap-6 justify-end w-full'>
        <div className='bg-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer'>
            <FaCommentDots className="w-4 h-4 text-gray-400"/>
        </div>
        <div className='bg-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer relative'>
            <FaBullhorn className="w-4 h-4 text-gray-400"/>
            <div className='absolute -top-3 -right-3 w-5 h-5 flex items-center justify-center bg-blue-500 text-white rounded-full text-xs'>1</div>
        </div>
        <div className='flex flex-col'>
          <span className="text-xs leading-3 font-medium">{name}</span>
          <span className="text-[10px] text-gray-500 text-right">{role}</span>
        </div>
        <FaUserCircle className="w-7 h-7 text-gray-400"/>
      </div>
    </div>
  )
}

export default Navbar