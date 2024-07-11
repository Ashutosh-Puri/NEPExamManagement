@props(['name'=>false ,'route'=>false ,'slot'=>false])
<a wire:navigate href="{{ route($route) }}" :class="{ 'bg-primary dark:bg-primary  text-white': '{{ request()->routeis($route) }}' }" class="flex items-center p-2 px-5 text-gray-500  rounded-md dark:text-light hover:text-white hover:bg-primary ">
    <span aria-hidden="true">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
           {{ $slot }} 
        </svg>
    </span>
    <span class="ml-2 duration-300 ease-in-out">{{ $name }}</span>
</a>