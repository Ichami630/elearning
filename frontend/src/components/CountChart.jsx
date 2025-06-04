
import {
  RadialBarChart,
  RadialBar,
  Legend,
  ResponsiveContainer,
} from "recharts";


const CountChart = ({ data }) => {
  const total = data?.[0]?.count || 0;
  const girls = data?.[1]?.count || 0;
  const boys = data?.[2]?.count || 0;

  return (
    <div className="bg-white rounded-xl w-full h-full p-4">
      {/* TITLE */}
      <div className="flex justify-between items-center">
        <h1 className="text-lg font-semibold">Students</h1>
        <img src="/moreDark.png" alt="" className="w-6 h-6" />
      </div>

      {/* CHART */}
      <div className="relative w-full h-[75%]">
        <ResponsiveContainer>
          <RadialBarChart
            cx="50%"
            cy="50%"
            innerRadius="40%"
            outerRadius="100%"
            barSize={32}
            data={data}
          >
            <RadialBar background dataKey="count" />
          </RadialBarChart>
        </ResponsiveContainer>
        <img
          src="/maleFemale.png"
          alt=""
          className="absolute w-16 h-16 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
        />
      </div>

      {/* BOTTOM */}
      <div className="flex justify-center gap-16">
        <div className="flex flex-col gap-1">
          <div className="w-5 h-5 bg-lamaSky rounded-full" />
          <h1 className="font-bold">{boys}</h1>
          <h2 className="text-xs text-gray-300">
            Males ({total ? ((boys / total) * 100).toFixed(1) : 0}%)
          </h2>
        </div>
        <div className="flex flex-col gap-1">
          <div className="w-5 h-5 bg-lamaYellow rounded-full" />
          <h1 className="font-bold">{girls}</h1>
          <h2 className="text-xs text-gray-300">
            Females ({total ? ((girls / total) * 100).toFixed(1) : 0}%)
          </h2>
        </div>
      </div>
    </div>
  );
};


export default CountChart;
