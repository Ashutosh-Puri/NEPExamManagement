@props(["name" => false, "route" => false, "slot" => false])
<form method="POST" action="{{ route($route) }}">
    @csrf
    <span  onclick="event.preventDefault(); this.closest('form').submit();":class="{ 'bg-primary text-white dark:bg-primary': '{{ request()->routeis($route) }}' }" class=" cursor-pointer flex items-center h-10 p-2 px-5  rounded-md dark:text-light hover:bg-primary hover:text-white text-white dark:hover:bg-primary">
        <span aria-hidden="true">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {{ $slot }}
            </svg>
        </span>
        <span class="ml-2 duration-300 ease-in-out">{{ $name }}</span>
    </span>
</form>