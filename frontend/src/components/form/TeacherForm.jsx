import React from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import {z} from 'zod'
import InputFields from '../InputFields'
import { toast } from 'react-toastify'
import api from '../../utils/api'

const schema = z.object({
    role: z.string(),
    name: z.string().min(2,{message: "Name is required"}),
    email: z.string().email({message: "Invalid email address"}),
    sex: z.enum(["Male","Female"]),
    password: z.string().min(4,{message:"Password must be atleast 4 characters long"}),
    title: z.enum(["Mr","Mrs","Dr","Prof"])
})

const TeacherForm = ({type,data = {},onClose}) => {
    const {
        register,
        handleSubmit,
        formState: {errors},}= useForm({
            resolver: zodResolver(schema),
            defaultValues: {
                role: "lecturer",
                ...data,
              },
        });
    
    const onSubmit = handleSubmit(async (data)=> {
        try {
            const res = await api.post("/controllers/action.signup.php",data);
            if(res.data.success){
                toast.success("Teacher created successfull");
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
            Authentication Information
        </span>
        <div className="flex justify-bewteen flex-wrap gap-4">
            <InputFields label="Email"  name="email" type="email" register={register} errors={errors} required/>
            <InputFields label="Password" name="password" type="password" register={register} errors={errors} required/>
            <InputFields label="Role"   name="role" type="hidden" register={register} required/>
        </div>

        <span className="text-normal text-center text-gray-800 font-medium">
            Personal Information
        </span>
        <div className="flex justify-between flex-wrap gap-4">
            <InputFields label="Name" name="name" type="text" register={register} errors={errors} required/>
            <InputFields label="Sex" name="sex" type="radio" options={[{label: "Male",value:"Male"},{label:"Female",value:"Female"}]} register={register} errors={errors} required/>
            <InputFields label="Title" name="title" type="select" options={[{label: "Male",value:"Male"},{label:"Mr",value:"Mrs"},{label:"Dr",value:"Dr"},{label:"Prof",value:"Prof"}]} register={register} errors={errors} required/>
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

export default TeacherForm