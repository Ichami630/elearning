import React from 'react'

const UserCard = ({total,type}) => {
    return (
        <div className="rounded-2xl odd:bg-[#22CEF5] even:bg-[#027BF5] p-4 flex-1 min-w-[130px]">
          <div className="flex justify-between items-center">
            <span className="text-[10px] bg-white px-2 py-1 rounded-full text-gray-500">
              2024/25
            </span>
            <img src="/more.png" alt="" width={20} height={20} />
          </div>
          <h1 className="text-2xl font-semibold my-4">{total}</h1>
          <h2 className="capitalize text-sm font-medium text-gray-800">{type}</h2>
        </div>
    );
}

export default UserCard