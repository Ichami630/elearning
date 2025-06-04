import { useEffect, useState } from 'react'
import UserCard from '../../components/UserCard'
import api from '../../utils/api'
import Announcements from '../../components/Announcement'
import CourseCard from '../../components/CourseCard'
import CountChart from '../../components/CountChart'

// const data = [
//   {
//     name: "Total",
//     count: 106,
//     fill: "white",
//   },
//   {
//     name: "Girls",
//     count: 53,
//     fill: "#FAE27C",
//   },
//   {
//     name: "Boys",
//     count: 53,
//     fill: "#C3EBFA",
//   },
// ];

const Home = () => {
  const {role,id} = JSON.parse(localStorage.getItem('user'))
  const [totals, setTotals] = useState({
    students: 0,
    lecturers: 0,
    admins: 0,
  })
  const [data,setData] = useState([]);

  useEffect(() => {
    const fetchTotals = async () => {
      try {
        const response = await api.get("/controllers/action.getTotalUsers.php")
        if (response.data.status) {
          setTotals({
            students: response.data.totalStudents,
            lecturers: response.data.totalLecturers,
            admins: response.data.totalAdmins,
          })
        }
      } catch (error) {
        console.error("Failed to fetch user totals", error)
      }
    }

    const fetchCountData = async () =>{
      const res = await api.get("/controllers/action.getTotalUsers.php",{
        params:{id,role}
      });
      console.log(res.data)
      if(res.data.success){
        setData(res.data.result);
      }
    }

    fetchCountData()
    fetchTotals()
  }, [id,role])

  return (
    <div className='flex gap-4 flex-col md:flex-row'>
      {/* LEFT */}
      <div className="w-full flex flex-col lg:w-2/3 gap-8">
        <div className="flex gap-4 justify-between flex-wrap">
          <UserCard type="students" total={totals.students} />
          <UserCard type="lecturers" total={totals.lecturers} />
          <UserCard type="admins" total={totals.admins} />
        </div>
        {/* MIDDLE */}
        <div className="flex flex-col lg:flex-row gap-4">
          {/* COUNT CHART */}
          <div className="w-full lg:w-1/3 h-[450px]">
            <CountChart data={data} />
          </div>
        </div>
      </div>
      {/* RIGHT */}
      <div className="flex w-full lg:w-1/3 flex-col gap-8">
        <CourseCard />
        <Announcements/>
      </div>
    </div>
  )
}

export default Home
