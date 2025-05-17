<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-200 uppercase tracking-widest hover:bg-purple-700 dark:hover:bg-purple-600 focus:bg-purple-700 dark:focus:bg-purple-600 active:bg-purple-800 dark:active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
