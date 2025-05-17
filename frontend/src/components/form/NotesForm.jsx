import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import InputFields from '../InputFields'
import { toast } from 'react-toastify'
import TipTap from '../Tiptap'
import { useState } from 'react'
import api from '../../utils/api'

const schema = z.object({
  courseId: z.string(),
  moduleTitle: z.string().min(2, { message: "module title is required" }),
  noteTitle: z.string().min(2, { message: "note title is required" }),
  noteType: z.enum(["file", "note", "video"]),
})

const NotesForm = ({ type, data = {}, onClose, rest }) => {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(schema),
    defaultValues: {
      courseId: rest,
      ...data,
    },
  })

  const [editorContent, setEditorContent] = useState(data?.noteContent || '')

  const onSubmit = handleSubmit(async (formData) => {
    try {
      const form = new FormData()
      for (const key in formData) {
        form.append(key, formData[key])
      }

      // Append editor content
      form.append("noteContent", editorContent)

    //   console.log("Form data to be sent:")
    //   for (let [key, value] of form.entries()) {
    //     console.log(`${key}: ${value}`)
    //   }

    const res = await api.post("controllers/action.notes.php", form, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })

      if (res.data.success) {
        toast.success(res.data.message)
      } else {
        toast.error(res.data.message)
      }

    } catch (err) {
      console.error(err)
      toast.error("Error uploading course")
    } finally {
      onClose()
    }
  })

  return (
    <form onSubmit={onSubmit} className="flex flex-col gap-4">
      <span className="text-normal text-center text-gray-800 font-medium">
        Module Information
      </span>

      <div className="flex justify-between flex-wrap gap-4">
        <InputFields label="courseId" name="courseId" type="hidden" register={register} errors={errors} required />
        <InputFields label="Module Title" name="moduleTitle" type="text" register={register} errors={errors} required />
        <InputFields
          label="Note Type"
          name="noteType"
          type="select"
          options={[
            { label: "File", value: "file" },
            { label: "Note", value: "note" },
            { label: "Video", value: "video" },
          ]}
          register={register}
          errors={errors}
          required
        />
        <InputFields label="Note Title" name="noteTitle" type="text" register={register} errors={errors} required />
        <div className="w-full text-left">
            <label className="block text-xs text-gray-500">Note Content <span className="text-red-500">*</span></label>
            <TipTap value={editorContent} onChange={setEditorContent} />
        </div>
      </div>

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

export default NotesForm
