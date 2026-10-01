@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white text-gray-900 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-[#002525] dark:text-gray-100 dark:border-[#145050] dark:focus:border-[#68C7EC] dark:focus:ring-[#68C7EC]']) }}>
