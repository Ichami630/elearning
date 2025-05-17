import {useParams} from "react-router-dom";
import {useEffect,useState} from "react";
import api from "../../utils/api";

const Note = () => {
    const {title} = useParams();
    const [note,setNote] = useState({});
    //get the content of the note
    useEffect(()=>{
        const getNote = async () =>{
            const res = await api.get("/controllers/action.notes.php",{
                params:{title}
            });
            if(res.data.success){
                setNote(res.data.notes);
            }

        }

        getNote();
    },[title])
  return (
    <div className="bg-white p-4 rounded shadow text-gray-800">
      <div
        className="prose prose-sm sm:prose lg:prose-lg max-w-none"
        dangerouslySetInnerHTML={{ __html: note }}
      />
    </div>
  )
}

export default Note