import React from "react";
import {
  FaHouseUser,
  FaGraduationCap,
  FaUser,
  FaBook,
  FaUserSlash,
  FaStickyNote,
  FaBookOpen,
  FaBullhorn,
  FaCommentDots,
  FaUserCircle,
  FaCaretRight,
} from "react-icons/fa";
import { NavLink } from "react-router-dom";

// Map icon names to actual components
const icons = {
  FaHouseUser,
  FaGraduationCap,
  FaUser,
  FaBook,
  FaUserSlash,
  FaStickyNote,
  FaBookOpen,
  FaBullhorn,
  FaCommentDots,
  FaUserCircle,
  FaCaretRight,
};

const menuItems = [
  {
    title: "MENU",
    items: [
      { icon: "FaHouseUser", label: "Home", href: "/", visible: ["admin", "lecturer", "student"] },
      { icon: "FaGraduationCap", label: "Teachers", href: "/dashboard/teachers", visible: ["admin","lecturer","student"] },
      { icon: "FaUser", label: "Students", href: "/dashboard/students", visible: ["admin", "lecturer"] },
      { icon: "FaBook", label: "Subjects", href: "/dashboard/subjects", visible: ["admin", "lecturer"] },
      { icon: "FaUserSlash", label: "Classes", href: "/dashboard/classes", visible: ["admin", "lecturer"] },
      { icon: "FaBookOpen", label: "Quiz", href: "/dashboard/quiz", visible: ["admin", "lecturer", "student"] },
      { icon: "FaStickyNote", label: "Assignments", href: "/dashboard/assignments", visible: ["admin", "lecturer", "student"] },
      { icon: "FaCommentDots", label: "Messages", href: "/dashboard/messages", visible: ["admin", "lecturer", "student"] },
      { icon: "FaBullhorn", label: "Announcements", href: "/dashboard/announcements", visible: ["admin", "lecturer", "student"] },
    ],
  },
  {
    title: "OTHER",
    items: [
      { icon: "FaUserCircle", label: "Profile", href: "/dashboard/profile", visible: ["admin", "lecturer", "student"] },
      { icon: "FaCaretRight", label: "Logout", href: "/logout", visible: ["admin", "lecturer", "student"] },
    ],
  },
];

const Menu = () => {
  const user = localStorage.getItem("user");
  const role = JSON.parse(user)?.role;

  return (
    <div className="mt-4 text-sm">
      {menuItems.map((section) => (
        <div className="flex flex-col gap-2" key={section.title}>
          <span className="hidden lg:block text-gray-400 font-light my-4">
            {section.title}
          </span>
          {section.items.map((item) => {
            if (item.visible.includes(role)) {
              const IconComponent = icons[item.icon];
              return (
                <NavLink
                  to={item.href}
                  key={item.label}
                  className={({ isActive }) =>
                    `flex items-center justify-center lg:justify-start gap-4 py-2 md:px-2 rounded-md transition-all ${
                      isActive
                        ? "bg-blue-500 text-white"
                        : "text-gray-500 hover:bg-blue-100"
                    }`
                  }
                >
                  {IconComponent && (
                    <IconComponent
                      className={`w-5 h-5 ${
                        item.href === window.location.pathname
                          ? "text-white"
                          : "text-gray-400"
                      }`}
                    />
                  )}
                  <span className="hidden lg:block">{item.label}</span>
                </NavLink>
              );
            }
            return null;
          })}
        </div>
      ))}
    </div>
  );
};

export default Menu;
