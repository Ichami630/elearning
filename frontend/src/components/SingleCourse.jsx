import {Tab, TabGroup, TabList, TabPanel, TabPanels,Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/react'
import { useParams,NavLink } from 'react-router-dom';
import { useEffect, useState } from 'react';
import api from '../utils/api';
import Table from './Table';
import FormModal from './FormModal';
import { FaEdit } from 'react-icons/fa';
import { ChevronDownIcon,LinkIcon } from '@heroicons/react/24/solid'

const columns = [
  { header: "Student", accessor: "student" },
  { header: "Email", accessor: "email"},
]

export default function SingleCourse() {
  const renderRow = (item) => (
      <tr key={item.id} className="border-b border-gray-300 even:bg-slate-200 text-sm hover:bg-blue-200">
        <td className="flex items-center gap-4 p-4">
          <img src="/profile.png" alt="" width={40} height={40} className="md:hidden xl:block w-10 h-10 rounded-full object-cover hidden lg:table-cell" />
          <div className="flex flex-col">
            <h3 className="font-semibold">{item.name}</h3>
          </div>
        </td>
        <td>{item.email}</td>
      </tr>
    );

  const {role} = JSON.parse(localStorage.getItem('user'))
  const {courseId} = useParams();
  const [participants, setParticipants] = useState([]);
  const [modules,setModules] = useState([]);
  const [selectedTab,setSelectedTab] = useState('');

  //fetch all students currently enrolled in this course
  useEffect(()=>{
    const participants = async () => {
      try {
        const res = await api.get("/controllers/action.student.php",{
        params:{courseId}
        });
        if(res.data.success){
          setParticipants(res.data.partcipants);
        }
      }catch(error){
        console.error("Failed to fetch participants", error);
      }
    }
    participants();

    //fetch all course resources
    const notes = async ()=>{
      try{
        const res = await api.get("/controllers/action.notes.php",{
        params:{courseId}
        });
        if(res.data.success){
          //spit topics into arrays
          const formattedModules = res.data.modules.map(module =>({
            ...module,
            topics: module.topics? module.topics.split(","): [],
            type: module.type? module.type.split(","): [],
          }))
          setModules(formattedModules);
        }
      }catch(error){
        console.log("Failed to get notes",error);
      }
    }
    notes();
  },[courseId]);
  const tabs = [
    { name: 'Course', key: 'course' },
    { name: 'Participants', key: 'participants' },
    ...(role === 'lecturer'
      ? [
          { name: 'Assignments', key: 'assignments' },
          { name: 'Submissions', key: 'submissions' },
          { name: 'Quizzes', key: 'quiz' },
        ]
      : [{ name: 'Grades', key: 'grades' }])
  ];

  return (
    <div>
    <TabGroup>
      <TabList className="flex space-x-2 border-b border-gray-400 mb-4 flex-col md:flex-row">
        {tabs.map((tab) => (
          <Tab
            onClick={() => setSelectedTab(tab.key)}
            key={tab.key}
            className={({ selected }) =>
              `px-4 py-2 text-sm font-medium rounded-t-md focus:outline-none ${
                selected ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'
              }`
            }
          >
            {tab.name}
          </Tab>
        ))}
      </TabList>
      <div className="">
        <TabPanels>
          <TabPanel className="w-full lg:max-w-4xl">
            {
              modules.length === 0 ? (
                <div className="p-4 text-center rounded-md text-red-500">** No Resources Available for this course **</div>
              ):(
            modules.map((module, index) => (
              <div key={index} className="bg-white rounded-md shadow-sm p-4 mb-4">
                <Disclosure>
                  <DisclosureButton className="group text-md font-bold mb-4 flex justify-between w-full items-center gap-2">
                    {module.title}
                    <div className="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center transition-transform duration-200">
                      <ChevronDownIcon className="w-4 h-4 group-data-[open]:rotate-180 transition-transform duration-200" />
                    </div>
                  </DisclosureButton>

                  <div className="pl-2 space-y-2 text-sm">
                    {module.topics.map((topic, index) => (
                      <DisclosurePanel key={index} className="text-gray-600 flex flex-col gap-2">
                        <div className="flex flex-row gap-4">
                        <LinkIcon className="w-5 text-blue-200" /> <NavLink to={`/dashboard/courses/${courseId}/${topic}`}className="hover:underline">
                          [{module.type[index]}] {topic}
                        </NavLink></div>
                        { role === 'lecturer' && (<div className="flex justify-end">
                          <FaEdit className="w-4 h-4 cursor-pointer" title="Add new note under this module" />
                        </div>) }
                      </DisclosurePanel>
                    ))}
                  </div>
                </Disclosure>
              </div>
            ))
             )
            }

            {role === 'lecturer' && selectedTab === 'course' &&<div className="absolute cursor-pointer gap-2 flex text-center bottom-20 shadow-md right-10 w-[150px] items-center p-2 rounded-md bg-blue-500 text-white">
              <FormModal table="notes" type="create" rest={courseId} /> <span className="text-md">New Notes</span>
            </div>}
          </TabPanel>

          <TabPanel className="p-4 bg-white rounded-md"><Table columns={columns} data={participants} renderRow={renderRow} noResult={"No Student is currently enrolled in this course"} /></TabPanel>
          <TabPanel>Content 3</TabPanel>
          <TabPanel>Content 4</TabPanel>
          <TabPanel>
                {role === 'lecturer' && selectedTab === 'quiz' &&<div className="absolute cursor-pointer gap-2 flex text-center bottom-20 shadow-md right-10 w-[150px] items-center p-2 rounded-md bg-blue-500 text-white">
                <FormModal table="quiz" type="create" rest={courseId} /> <span className="text-md">Create Quiz</span>
      </      div>}
          </TabPanel>
        </TabPanels>
      </div>
    </TabGroup>
    </div>
  );
}
