<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md font-semibold text-xs text-white dark:text-primary-800 uppercase tracking-widest hover:bg-primary-darker dark:hover:bg-primary-darker focus:bg-primary-darker   focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker border text-center']) }}>
    {{ $slot }}
</button>
