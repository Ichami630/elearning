import {useEffect,useState} from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import {z} from 'zod'
import InputFields from '../InputFields'
import { toast } from 'react-toastify'
import api from '../../utils/api'

const schema = z.object({
    academicYear: z.string(),
    semester: z.enum(["First","Second"]),
    title: z.string().min(2,{message: "Name is required"}),
    code: z.string().min(3,{message: "Invalid email address"}),
    courseType: z.enum(["general_all","specific"]),
    instructorId: z.coerce.number(),
    levelId: z.coerce.number(),
    departmentId: z.coerce.number().optional(), //initially optional
}).refine(
    (data)=>{
        //if the ciursetype is specific, then departmentid is required
        if(data.courseType === "specific"){
            return !!data.departmentId
        }
        return true
    },
    {
        message: "Department is required for departmental courses",
        path: ["departmentId"],
    }
)

const CourseForm = ({type,data = {},onClose}) => {
    const {role} = JSON.parse(localStorage.getItem('user'));
    const {
        register,
        handleSubmit,
        formState: {errors},}= useForm({
            resolver: zodResolver(schema),
            defaultValues: {
                academicYear: "24/25",
                ...data,
              },
        });
    //track the selected course type
    const [selectedCourseType, setSelectedCourseType] = useState("");
    const [departments,setDepartments] = useState([]);
    const [lecturers,setLecturers] = useState([]);

    //fetch all existing departments
    useEffect(()=>{
        const Departments = async () =>{
            const res = await api.get("/controllers/action.fetchAllDepartments.php");
            if(res.data.success){
                setDepartments(res.data.departments);
            }
        }
        Departments();
    },[]);

    //fetch all the instructors 
    useEffect(()=>{
        const lecturers = async () => {
            const res = await api.get("/controllers/action.getLecturers.php",{
                params:{role}
            });
            if(res.data.success){
                setLecturers(res.data.lecturers);
            }
        }
        lecturers();
    },[role]);

    
    const onSubmit = handleSubmit(async (data)=> {
        try {
            console.log(data)
            const res = await api.post("/controllers/action.createCourse.php",data);
            console.log(res);
            if(res.data.success){
                toast.success("course creation and assignment successful");
            }else{
                toast.error(res.data.message);
            }
        } catch (error) {
            console.error(error)
        }finally{
            onClose();
        }
    })
  return (
    <form onSubmit={onSubmit} className='flex flex-col gap-4'>
        <span className="text-normal text-center text-gray-800 font-medium">
            Course Information
        </span>
        <div className="flex justify-bewteen flex-wrap gap-4">
            <InputFields label="academicYear" name="academicYear" type="hidden" register={register} errors={errors} required />
            <InputFields label="Semester"   name="semester" type="select" options={[{label:"First Semester",value:"First"},{label:"Second Semester",value:"Second"}]} register={register} errors={errors} required/>
            <InputFields label="Course Title"  name="title" type="text" register={register} errors={errors} required/>
            <InputFields label="Course Code" name="code" type="text" register={register} errors={errors} required/>
            <InputFields label="course Type"   name="courseType" type="select" options={[{label:"General",value:"general_all"},{label:"Departmental",value:"specific"}]} register={register}
            inputProps={{onChange: ((e)=>setSelectedCourseType(e.target.value))}} required/>
            {selectedCourseType === 'specific' && <InputFields label="Department" name="departmentId" type="select" options={departments.map((d)=>({label:d.name,value:d.id}))} register={register} errors={errors} required/>}
        </div>

        <span className="text-normal text-center text-gray-800 font-medium">
            Class Information
        </span>
        <div className="flex justify-between flex-wrap gap-4">
            <InputFields label="Course Instructor" name="instructorId" type="select" options={lecturers.map((l)=>({label:l.name,value:l.id}))} register={register} errors={errors} required/>
            <InputFields label="Level" name="levelId" type="select" options={[{label: 200,value:2},{label:300,value:3}]} register={register} errors={errors}/>
        </div>

        <div className="flex justify-end gap-2">
            <button type="button" className="bg-red-400 text-white px-4 py-2 rounded-md cursor-pointer"onClick={onClose} title='close modal'>Cancel</button>
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md cursor-pointer">
            {type === 'create' ? 'Create' : 'Update'}
            </button>
        </div>
    </form>
  )
}

export default CourseForm