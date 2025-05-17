@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-deeper-gray-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-purple-500 dark:focus:ring-purple-400 rounded-md shadow-sm']) }}>
