@props(['slot' => false,'svg' => false])

<button {{ $attributes->merge(['type' => 'button', 'class' => 'uppercase mx-5 float-right inline-flex items-center px-4 py-2 my-3 bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md font-semibold text-xs text-white dark:text-primary-800  tracking-widest hover:bg-primary-darker dark:hover:bg-primary-darker focus:bg-primary-darker   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker border']) }}>
    {{ $slot }}
    {{ $svg }}
</button>
