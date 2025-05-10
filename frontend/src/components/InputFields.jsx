
const InputFields = ({label,name,type,register,errors = {},options = [],required = false,defaultValue}) => {
  return (
    <div className={`flex flex-col gap-2 w-full lg:w-[260px] ${type === "hidden" ? "hidden" : ""}`}>
      {type !== "hidden" && (
        <label className="text-xs text-gray-500">
          {label} {required && (<span className="text-red-500 text-sm">*</span>)}
        </label>
      )}
      {type === 'radio' ? (
        <div className="flex gap-4">
          {options.map((opt) => (
            <label key={opt.value} className="flex items-center gap-1">
              <input type="radio" value={opt.value} {...register(name)} />
              {opt.label}
            </label>
          ))}
        </div>
      ) : type === 'select' ? (
        <select {...register(name)} className="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
          <option value="">-- Select {label} --</option>
          {options.map((opt) => (
            <option key={opt.value} value={opt.value}>{opt.label}</option>
          ))}
        </select>
      ) : (
        <input
          type={type}
          {...register(name)}
          defaultValue={defaultValue}
          className={`px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-full`}
        />
      )}
      {errors[name] && <p className="text-red-500 text-sm mt-1">{errors[name]?.message}</p>}
    </div>
  );
}

export default InputFields