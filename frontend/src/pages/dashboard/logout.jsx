// Logout.jsx
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../../utils/api"; // adjust path as needed

const Logout = () => {
  const navigate = useNavigate();

  useEffect(() => {
    const doLogout = async () => {
      if(confirm("Are you sure you want to logout?")){
        try {
            await api.GET("/controller/action.logout.php", {
            });
          } catch (error) {
            console.error("Logout error:", error);
          } finally {
            localStorage.removeItem("user");
            navigate("/login");
          }
      }
    };

    doLogout();
  }, [navigate]);

  return null; // or loading spinner if you want
};

export default Logout;
