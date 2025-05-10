// components/FormModal.jsx
import React, { useState } from 'react';
import TeacherForm from './form/TeacherForm';

const tableToForm = {
  teacher: TeacherForm,
};

const FormModal = ({ table, type, onClose = () => {} }) => {
  const [isOpen,setIsOpen] = useState(false);
  const FormComponent = tableToForm[table];
  if (!FormComponent) return null;

  return (
    <>
        <button className="flex justify-center items-center rounded-full bg-yellow-200 w-8 h-8 cursor-pointer" onClick={()=>setIsOpen(true)} title='create new teacher'>
            <img src={`/${type}.png`} alt={type} className='w-4 h-4'/>
        </button>
        {isOpen && (
             <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                <div className="relative z-[60] w-[90%] md:w-[70%] lg:w-[60%] xl:w-[50%] 2xl:w-[40%] bg-white p-6 rounded-md max-h-[90vh] overflow-auto hide-scrollbar">
                <FormComponent type={type} onClose={() => { setIsOpen(false); onClose(); }} />
                </div>
            </div>
        )}

    </>
    
  );
};

export default FormModal;
