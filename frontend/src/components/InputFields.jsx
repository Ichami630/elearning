
const InputFields = ({label,name,type,register,errors = {},options = [],required = false,defaultValue,inputProps}) => {
  return (
    <div className={`flex flex-col gap-2 w-full lg:w-[260px] ${type === "hidden" ? "hidden" : ""}`}>
      {type !== "hidden" && (
        <label className="text-xs text-gray-500">
          {label} {required && (<span className="text-red-500 text-sm">*</span>)}
        </label>
      )}
      {type === 'radio' ? (
        <div className="flex gap-4" {...inputProps}>
          {options.map((opt) => (
            <label key={opt.value} className="flex items-center gap-1 text-left">
              <input type="radio" value={opt.value} {...register(name)} />
              {opt.label}
            </label>
          ))}
        </div>
      ) : type === 'select' ? (
        <select {...register(name)} className="px-4 py-2 text-gray-400 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-full" {...inputProps}>
          <option className="text-gray-400" value="">-- Select {label} --</option>
          {options.map((opt) => (
            <option className="text-gray-400" key={opt.value} value={opt.value}>{opt.label}</option>
          ))}
        </select>
      ) : (
        <input
          type={type}
          {...register(name)}
          defaultValue={defaultValue}
          {...inputProps}
          className={`px-4 py-2 text-gray-400 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-full`}
        />
      )}
      {errors[name] && <p className="text-red-500 text-sm mt-1">{errors[name]?.message}</p>}
    </div>
  );
}

export default InputFields