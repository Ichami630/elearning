import { Tab, TabGroup, TabList, TabPanel, TabPanels,Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/react'
import { useParams } from 'react-router-dom';
import { useEffect, useState } from 'react';
import api from '../utils/api';
import Table from './Table';
import { ChevronDownIcon } from '@heroicons/react/20/solid'

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
        ]
      : [{ name: 'Grades', key: 'grades' }])
  ];

  return (
    <TabGroup>
      <TabList className="flex space-x-2 border-b border-gray-400 mb-4 flex-col md:flex-row">
        {tabs.map((tab) => (
          <Tab
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
      <div className="p-4 bg-white rounded-md">
        <TabPanels>
          <TabPanel>
            <Disclosure>
              <DisclosureButton className="group flex items-center gap-2">
                Is team pricing available?
                <ChevronDownIcon className="w-5 group-data-open:rotate-180 transition-transform duration-200" />
              </DisclosureButton>
              <DisclosurePanel className="text-gray-500">
                Yes! You can purchase a license that you can share with your entire team.
              </DisclosurePanel>
            </Disclosure>
          </TabPanel>
          <TabPanel><Table columns={columns} data={participants} renderRow={renderRow} noResult={"No Student is currently enrolled in this course"} /></TabPanel>
          <TabPanel>Content 3</TabPanel>
        </TabPanels>
      </div>
    </TabGroup>
  );
}
