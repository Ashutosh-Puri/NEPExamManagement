@props(["name" => false, "route" => false])
<a wire:navigate href="{{ route($route) }}" role="menuitem" class=" cursor-pointer block px-4 py-2 text-sm text-gray-700  hover:bg-gray-100 dark:text-light dark:hover:bg-primary">
    <span class="ml-2 duration-300 ease-in-out">{{ $name }}</span>
</a>

