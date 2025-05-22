import React from 'react';
import { useForm, useFieldArray } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import InputFields from '../InputFields';
import api from '../../utils/api';
import { toast } from 'react-toastify';

const questionSchema = z.object({
  question: z.string().min(1, 'Question is required'),
  optionA: z.string().min(1),
  optionB: z.string().min(1),
  optionC: z.string().min(1),
  optionD: z.string().min(1),
  correct: z.enum(['A', 'B', 'C', 'D'], {
    errorMap: () => ({ message: 'Select a correct option' }),
  }),
});

const quizSchema = z.object({
  title: z.string().min(1, 'Quiz title is required'),
  duration: z.string().min(1, 'Duration is required'),
  totalMarks: z.string().min(1, 'Total marks is required'),
  questions: z.array(questionSchema).min(1, 'At least one question is required'),
  courseId: z.string(),
});

export default function QuizForm({ type, data = {}, onClose,rest}) {
  const {
    register,
    control,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(quizSchema),
    defaultValues: {
      courseId: rest,
      title: '',
      totalMarks: '',
      duration: '',
      questions: [
        { question: '', optionA: '', optionB: '', optionC: '', optionD: '', correct: '' },
      ],
      ...data
    },
  });

  const { fields, append, remove } = useFieldArray({
    control,
    name: 'questions',
  });

  const onSubmit = async (data) => {
    try {
      const res = await api.post('/controllers/action.quiz.php', data);
      console.log(res.data);
      if(res.data.success){
        toast.success(res.data.message);
      }else{
        toast.error(res.data.message);
      }
    } catch (err) {
      console.error(err);
      alert('Server error');
    }finally{
        onClose();
    }
  };

  return (
    <div className="max-w-4xl mx-auto px-4 py-8 text-gray-500">
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
        <h2 className="text-2xl text-left font-bold text-center">Create Quiz</h2>

        <InputFields label="courseId" name="courseId" type="hidden" register={register} errors={errors} required />

        <div>
            <label className="block text-sm text-left font-medium">Quiz Title</label>
                <input
                    {...register('title')}
                    className="mt-1 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter quiz title"
                />
            {errors.title && <p className="text-red-500 text-sm">{errors.title.message}</p>}
        </div>
        <div className="flex justify-between items-center text-left">

            <div>
                <label className="block text-sm font-medium">Duration (minutes)</label>
                <input
                    type="number"
                    {...register('duration')}
                    className="mt-1 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="E.g. 30"
                />
                {errors.duration && <p className="text-red-500 text-sm">{errors.duration.message}</p>}
            </div>

            <div>
                <label className="block text-sm font-medium">Total Marks </label>
                <input
                    type="number"
                    {...register('totalMarks')}
                    className="mt-1 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="E.g. 30"
                />
                {errors.totalMarks && <p className="text-red-500 text-sm">{errors.totalMarks.message}</p>}
            </div>
        </div>

       

        <div>
          <h3 className="text-xl text-left font-semibold mt-6 mb-2">Questions</h3>

          {fields.map((field, index) => (
            <div
              key={field.id}
              className="border rounded-lg p-4 mb-4 bg-gray-50 shadow-sm space-y-3"
            >
              <div className="flex justify-between items-center">
                <label className="block font-semibold text-gray-700">
                  Question {index + 1}
                </label>
                <button
                  type="button"
                  onClick={() => remove(index)}
                  className="text-red-600 cursor-pointer text-sm hover:underline"
                >
                  Remove
                </button>
              </div>

              <textarea
                {...register(`questions.${index}.question`)}
                className="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                placeholder="Enter the question"
              />
              {errors.questions?.[index]?.question && (
                <p className="text-red-500 text-sm">{errors.questions[index].question.message}</p>
              )}

              <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input
                  {...register(`questions.${index}.optionA`)}
                  placeholder="Option A"
                  className="p-2 border rounded-md"
                />
                <input
                  {...register(`questions.${index}.optionB`)}
                  placeholder="Option B"
                  className="p-2 border rounded-md"
                />
                <input
                  {...register(`questions.${index}.optionC`)}
                  placeholder="Option C"
                  className="p-2 border rounded-md"
                />
                <input
                  {...register(`questions.${index}.optionD`)}
                  placeholder="Option D"
                  className="p-2 border rounded-md"
                />
              </div>

              <div>
                <label className="block font-medium mt-2">Correct Option</label>
                <select
                  {...register(`questions.${index}.correct`)}
                  className="w-full p-2 border rounded-md"
                >
                  <option value="">Select correct option</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                </select>
                {errors.questions?.[index]?.correct && (
                  <p className="text-red-500 text-sm">{errors.questions[index].correct.message}</p>
                )}
              </div>
            </div>
          ))}

          <button
            type="button"
            onClick={() =>
              append({ question: '', optionA: '', optionB: '', optionC: '', optionD: '', correct: '' })
            }
            className="mt-2 px-4 cursor-pointer py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
          >
            + Add Question
          </button>
        </div>

        <div className="flex justify-end gap-2">
        <button type="button" className="bg-red-400 text-white px-4 py-2 rounded-md cursor-pointer" onClick={onClose}>
          Cancel
        </button>
        <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md cursor-pointer">
          {type === 'create' ? 'Create Quiz' : 'Update Quiz'}
        </button>
      </div>
      </form>
    </div>
  );
}
