@props(["name" => false, "route" => false])
<form method="POST" action="{{ route($route) }}">
    @csrf
    <span onclick="event.preventDefault(); this.closest('form').submit();":class="{ 'bg-primary-lighter text-white dark:bg-primary': '{{ request()->routeis($route) }}' }" class=" cursor-pointer block px-4 py-2 text-sm text-gray-700  hover:bg-gray-100 dark:text-light dark:hover:bg-primary">
        <span class="ml-2 duration-300 ease-in-out">{{ $name }}</span>
    </span>
</form>