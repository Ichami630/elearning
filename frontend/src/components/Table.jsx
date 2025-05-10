

const Table = ({columns,renderRow,data=[],noResult}) => {
  return (
    <table className="w-full mt-4">
     <thead>
        <tr className="text-left text-gray-500 text-sm">
          {columns.map((col) => (
            <th key={col.accessor} className={col.className}>{col.header}</th>
          ))}
        </tr>
      </thead>
        <tbody>{data.length === 0 ? (
            <tr>
                <td colSpan={columns.length} className="text-center text-red-500 p-4">
                    ** {noResult} **
                </td>
            </tr>
        ):(data.map((item)=>renderRow(item)))}</tbody>
    </table>
  )
}

export default Table