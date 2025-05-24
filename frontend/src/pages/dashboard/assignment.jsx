import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import { FaEdit } from "react-icons/fa";
import Table from "../../components/Table";
import TableSearch from "../../components/TableSearch";
import TipTap from "../../components/Tiptap";
import { toast } from 'react-toastify'
import api from "../../utils/api";

const columns = [
  { header: "Discussion", accessor: "discussion" },
  { header: "Started By", accessor: "startedBy" },
  { header: "Date Submitted", accessor: "dateSubmitted", className: "hidden md:table-cell" },
];

const renderRow = (item) => (
  <tr
    key={item.id}
    className="border-b border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200"
  >
    <td className="flex items-center gap-4 p-4">{item.title}</td>
    <td>{item.name}</td>
    <td className="hidden md:table-cell">{item.submitted_at}</td>
  </tr>
);

const Assignment = () => {
  const { assignmentId } = useParams();
  const { role,id } = JSON.parse(localStorage.getItem("user"));
  const [submissions, setSubmissions] = useState([]);
  const [editorContent, setEditorContent] = useState("");
  const [showForm, setShowForm] = useState(false);
  const [contentType, setContentType] = useState("text");
  const [formData, setFormData] = useState({
    title: "",
    file: null,
  });

  const [assignment, setAssignment] = useState({
    title: "",
    description: "",
    dueDate: "",
  });

  useEffect(() => {
    const getAssignment = async () => {
      const res = await api.get("/controllers/action.assignment.php", {
        params: { assignmentId },
      });
      if (res.data.success) {
        setAssignment({
          title: res.data.title,
          description: res.data.description,
          dueDate: res.data.dueDate,
        });
      }
    };

    const getAllSubmissions = async () => {
      const res = await api.get("/controllers/action.submission.php", {
        params: { assignmentId },
      });
      if (res.data.success) {
        setSubmissions(res.data.submissions);
      }
    };

    getAssignment();
    getAllSubmissions();
  }, [assignmentId]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: files ? files[0] : value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const payload = new FormData();
    payload.append("assignmentId", assignmentId);
    payload.append("studentId", id);
    payload.append("title", formData.title);

    if (contentType === "text") {
      payload.append("message", editorContent);
    } else {
      payload.append("file", formData.file);
    }
    //    console.log("Form data to be sent:")
    //   for (let [key, value] of payload.entries()) {
    //     console.log(`${key}: ${value}`)
    //   }

    // Submit the form data to your API
    try {
      const res = await api.post("/controllers/action.submission.php", payload, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      console.log(res.data)
      if (res.data.success) {
        toast.success(res.data.message);
      }else{
        toast.error(res.data.message);
      }
    } catch (err) {
      console.error("Error submitting discussion:", err);
    }finally{
        setShowForm(false);
    }
  };

  return (
    <>
      <div className="bg-white p-4 rounded shadow text-gray-800">
        <div className="flex justify-between items-center">
          <div className="text-2xl font-bold">{assignment.title}</div>
          {role === "lecturer" && <FaEdit className="w-5 h-5 cursor-pointer text-gray-600" />}
        </div>
        <div
          className="prose prose-sm sm:prose lg:prose-lg max-w-none py-4"
          dangerouslySetInnerHTML={{ __html: assignment.description }}
        />
      </div>

      {/* Submissions Section */}
      <div className="bg-white p-4 rounded-md my-4">
        <div className="flex flex-col md:flex-row justify-between items-center gap-4">
          <TableSearch />
          <button
            className="p-2 rounded-md cursor-pointer bg-blue-400 hover:bg-blue-500 text-white text-sm"
            onClick={() => setShowForm(true)}
          >
            Add Discussion
          </button>
        </div>

        {showForm && (
          <form className="my-4 space-y-4" onSubmit={handleSubmit}>
            <div className="flex flex-col w-full md:max-w-[760px]">
              <label htmlFor="title" className="font-semibold">
                Discussion <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                name="title"
                value={formData.title}
                onChange={handleChange}
                required
                className="border border-gray-300 focus:outline-none focus:border-blue-400 p-2 rounded-md w-full"
              />
            </div>

            <div className="flex flex-col w-full md:max-w-[760px]">
              <label htmlFor="contentType" className="font-semibold">
                Content Type
              </label>
              <select
                name="contentType"
                value={contentType}
                onChange={(e) => setContentType(e.target.value)}
                className="border border-gray-300 focus:outline-none focus:border-blue-400 p-2 rounded-md w-full"
              >
                <option value="text">Text</option>
                <option value="file">File</option>
              </select>
            </div>

            {contentType === "text" ? (
              <div>
                <label htmlFor="content" className="font-semibold">
                  Content
                </label>
                <TipTap value={editorContent} onChange={setEditorContent} />
              </div>
            ) : (
              <div className="flex flex-col w-full md:max-w-[760px]">
                <label htmlFor="file" className="font-semibold">
                  File
                </label>
                <input
                  type="file"
                  name="file"
                  onChange={handleChange}
                  className="border border-gray-300 p-2 rounded-md focus:outline-none focus:border-blue-400"
                />
              </div>
            )}

            <button
              type="submit"
              className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
            >
              Submit Discussion
            </button>
          </form>
        )}

        <Table
          columns={columns}
          data={submissions}
          renderRow={renderRow}
          noResult={"No discussion/submission for this assignment yet"}
        />
      </div>
    </>
  );
};

export default Assignment;
