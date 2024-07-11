@props(['slot'=>false])
<span :class="{ 'hidden': !isSidebarExpanded }" class="px-5 min-w-full flex dark:text-white fw-bold text-black text-xs cursor-pointer">{{ $slot }}</span>