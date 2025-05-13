import { useState,useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import {z} from 'zod'
import InputFields from '../InputFields'
import { toast } from 'react-toastify'
import api from '../../utils/api'

const schema = z.object({
    sex: z.enum({message: "Please select one"}),
    title: z.enum({message: "please choose one"})
})

const EnrollmentForm = ({type,data = {},onClose}) => {
    const {id} = JSON.parse(localStorage.getItem("user"));
    const [courses,setCourses] = useState([]);
    const [semester,setSemester] = useState("");

    useEffect(()=>{
        const fetchCourses = async ()=>{
            if(!semester) return; //wait until semester selected
            try {const res = await api.get("/controllers/action.getCourses.php",{
                params:{id,semester}
            });
            if(res.data.success){
                setCourses(res.data.courses);
            }}catch(error){
                console.error("failed to fetch courses",error)
            }
        }
        fetchCourses();
    },[id,semester])
    const {
        register,
        handleSubmit,
        formState: {errors},}= useForm({
            resolver: zodResolver(schema),
            defaultValues: {
                ...data,
              },
        });
    
    const onSubmit = handleSubmit(async (data)=> {
        try {
            console.log(data);
            toast.success("course enrollment successfull")
        } catch (error) {
            console.error(error)
        }finally{
            onClose();
        }
    })
  return (
    <form onSubmit={onSubmit} className='flex flex-col gap-4'>
        <span className="text-normal text-center text-gray-800 font-medium">
            Enroll to course
        </span>

        <div className="flex justify-between flex-wrap gap-4">
            <InputFields label="Semester" name="semester" type="select" options={[{label: "First",value:"First"},{label: "Second",value:"Second"}]} register={register} errors={errors} required inputProps={{
            onChange: (e) => setSemester(e.target.value),
            }}/>
            <InputFields label="Course" name="course" type="select" options={courses.map((c)=>({label: c.course_title, value: c.id}))} register={register} errors={errors} required/>
        </div>

        <div className="flex justify-end gap-2">
            <button type="button" className="bg-red-400 text-white px-4 py-2 rounded-md cursor-pointer"onClick={onClose} title='close modal'>Cancel</button>
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md cursor-pointer">
            {type === 'create' ? 'Enroll' : 'Update'}
            </button>
        </div>
    </form>
  )
}

export default EnrollmentForm