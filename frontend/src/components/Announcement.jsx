import { useEffect, useState } from "react";
import { NavLink } from "react-router-dom";
import api from "../utils/api";

const Announcements = () => {
  const [announcements, setAnnouncements] = useState([]);

  useEffect(() => {
    const fetchAnnouncements = async () => {
      try {
        const res = await api.get("/controllers/action.getAllAnnouncements.php");
        if (res.data.success) {
          setAnnouncements(res.data.announcements);
        } else {
          setAnnouncements([]);
        }
      } catch (error) {
        console.error("Failed to fetch announcements", error);
        setAnnouncements([]);
      }
    };

    fetchAnnouncements();
  }, []);

  return (
    <div className="bg-white p-4 rounded-md">
      <div className="flex items-center justify-between">
        <h1 className="text-xl font-semibold">Announcements</h1>
        <NavLink className="text-xs text-gray-400 cursor-pointer" to="/">View All</NavLink>
      </div>

      {announcements.length === 0 ? (
        <p className="text-center text-red-500 mt-4">No announcements available.</p>
      ) : (
        <div className="flex flex-col gap-4 mt-4">
          {announcements.slice(0,3).map((item, index) => (
            <div
              key={index}
              className={`rounded-md p-4 ${index % 2 === 0 ? "bg-blue-100" : "bg-blue-200"}`}
            >
              <div className="flex items-center justify-between">
                <h2 className="font-medium">{item.title}</h2>
                <span className="text-xs text-gray-400 bg-white rounded-md px-1 py-1">
                  {item.posted_at?.substring(0, 10) || "N/A"}
                </span>
              </div>
              <p className="text-sm text-gray-400 mt-1">{item.message}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Announcements;
