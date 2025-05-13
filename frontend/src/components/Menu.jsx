import React from "react";

import { NavLink } from "react-router-dom";


const menuItems = [
  {
    title: "MENU",
    items: [
      { icon: "/home.png", label: "Home", href: "/", visible: ["admin", "lecturer", "student"] },
      { icon: "/teacher.png", label: "Teachers", href: "/dashboard/teachers", visible: ["admin","lecturer","student"] },
      { icon: "/student.png", label: "Students", href: "/dashboard/students", visible: ["admin", "lecturer"] },
      { icon: "/subject.png", label: "Courses", href: "/dashboard/subjects", visible: ["admin", "lecturer"] },
      { icon: "/lesson.png", label: "Enrollment", href: "/dashboard/enrollments", visible: ["student"] },
      { icon: "/class.png", label: "Classes", href: "/dashboard/classes", visible: ["admin", "lecturer"] },
      { icon: "/exam.png", label: "Quiz", href: "/dashboard/quiz", visible: ["admin", "lecturer", "student"] },
      { icon: "/assignment.png", label: "Assignments", href: "/dashboard/assignments", visible: ["admin", "lecturer", "student"] },
      { icon: "/message.png", label: "Messages", href: "/dashboard/messages", visible: ["admin", "lecturer", "student"] },
      { icon: "/announcement.png", label: "Announcements", href: "/dashboard/announcements", visible: ["admin", "lecturer", "student"] },
    ],
  },
  {
    title: "OTHER",
    items: [
      { icon: "/profile.png", label: "Profile", href: "/dashboard/profile", visible: ["admin", "lecturer", "student"] },
      { icon: "/logout.png", label: "Logout", href: "/logout", visible: ["admin", "lecturer", "student"] },
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
                  <img src={item.icon} className="w-4 h-4" alt={`${item.label} logo`} />
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
