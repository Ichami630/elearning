import StudentProfile from "../../components/StudentProfile";

const Profile = () => {
    const {role,id} = JSON.parse(localStorage.getItem("user"));
  return (
    <>
        {role === 'student' ? (
            <StudentProfile id={id} role={role} />
        ):(
            <div>Welcome {role}</div>
        )}
    </>
  );
};

export default Profile;
