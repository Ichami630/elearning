// components/FormModal.jsx
import React, { useState } from 'react';
import TeacherForm from './form/TeacherForm';
import EnrollmentForm from './form/EnrollmentForm';
import StudentForm from './form/StudentForm';
import CourseForm from './form/CourseForm';
import NotesForm from './form/NotesForm';
import QuizForm from './form/QuizForm';
import AssignmentForm from './form/AssignmentForm';

const tableToForm = {
  teacher: TeacherForm,
  enrollement: EnrollmentForm,
  student: StudentForm,
  course: CourseForm,
  notes: NotesForm,
  quiz: QuizForm,
  assignment: AssignmentForm,
};

const FormModal = ({ table, type, title, onClose = () => {},...rest }) => {
  const [isOpen,setIsOpen] = useState(false);
  const FormComponent = tableToForm[table];
  if (!FormComponent) return null;

  return (
    <>
        <button className="flex justify-center items-center rounded-full bg-yellow-200 w-8 h-8 cursor-pointer" onClick={()=>setIsOpen(true)} title={title}>
            <img src={`/${type}.png`} alt={type} className='w-4 h-4'/>
        </button>
        {isOpen && (
             <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                <div className="relative z-[60] w-[90%] md:w-[70%] lg:w-[60%] xl:w-[50%] 2xl:w-[40%] bg-white p-6 rounded-md max-h-[90vh] overflow-auto hide-scrollbar">
                <FormComponent type={type} onClose={() => { setIsOpen(false); onClose(); }} {...rest} />
                </div>
            </div>
        )}

    </>
    
  );
};

export default FormModal;
