import { useEffect, useState } from 'react'
import UserCard from '../../components/UserCard'
import api from '../../utils/api'
import Announcements from '../../components/Announcement'
import CourseCard from '../../components/CourseCard'

const Home = () => {

  const [totals, setTotals] = useState({
    students: 0,
    lecturers: 0,
    admins: 0,
  })

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

    fetchTotals()
  }, [])

  return (
    <div className='flex gap-4 flex-col md:flex-row'>
      {/* LEFT */}
      <div className="w-full flex flex-col lg:w-2/3 gap-8">
        <div className="flex gap-4 justify-between flex-wrap">
          <UserCard type="students" total={totals.students} />
          <UserCard type="lecturers" total={totals.lecturers} />
          <UserCard type="admins" total={totals.admins} />
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
