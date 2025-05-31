import {useState} from 'react';
import api from '../../utils/api';
import {toast} from 'react-toastify'

const FeedbackForm = ({type, data = {}, onClose, rest }) => {
    const [formData,setFormData] = useState({
        submissionId: rest,
        grade: "",
        feedback: ""
    })
    const handleChange = (e) => {
        const {name,value} = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value
        }));
    }
    const handleSubmit = async (e) =>{
        e.preventDefault();
        try {
            const res = await api.post("controllers/action.feedback.php",formData, {
                    headers: { "Content-Type": "multipart/form-data" },
            });
            if(res.data.success){
                toast.success(res.data.message)
            }else{
                toast.error(res.data.message)
            }
        }catch(error){
            console.error("error submitting form",error)
        }finally{
            onClose();
        }
    }
  return (
    <form onSubmit={handleSubmit} className="flex flex-col gap-4">
        <span className="text-normal text-center text-gray-800 font-medium">
            Grade and leave a feedback for this assignment
        </span>
        <input type="hidden" name="submissionId" value={formData.rest} />
        <label htmlFor="grade">Grade <span className="text-red-500">*</span></label>
        <input className="border text-gray-400 border-gray-300 focus:outline-none focus:border-blue-400 p-2 rounded-md w-full"  value={formData.grade} type="text" name="grade" onChange={handleChange} />
        <label htmlFor="feedback">Feedback <span className="text-red-500">*</span></label>
        <textarea name="feedback" value={formData.feedback}  onChange={handleChange} className="border border-gray-300 text-gray-400 focus:outline-none focus:border-blue-400 p-2 rounded-md w-full"  cols="30" rows="10"></textarea>

        <div className="flex justify-end gap-2">
            <button type="button" className="bg-red-400 text-white px-4 py-2 rounded-md cursor-pointer" onClick={onClose}>
                Cancel
            </button>
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md cursor-pointer">
                {type === 'create' ? 'Create' : 'Update'}
            </button>
        </div>
    </form>
  )
}

export default FeedbackForm