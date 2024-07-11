@props(["name" => false, "route" => false, "slot" => false])
<form method="POST" action="{{ route($route) }}">
    @csrf
    <span  onclick="event.preventDefault(); this.closest('form').submit();" :class="{ 'bg-primary text-white dark:bg-primary': '{{ request()->routeis($route) }}', 'px-5 rounded-md': isSidebarExpanded, 'mx-3 px-auto rounded-md ': !isSidebarExpanded }" class="cursor-pointer flex h-10 py-2 text-black dark:text-white  hover:bg-primary hover:text-white  dark:hover:bg-primary" >
        <span aria-hidden="true" :class="{ 'mx-auto': !isSidebarExpanded }" class="h-5 w-5">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {{ $slot }}
            </svg>
        </span>
        <span :class="{ 'hidden': !isSidebarExpanded }" class="ml-2 truncate">{{ $name }}</span>
</form>