{{-- @props(['id'=>Str::uuid(),'name' => false, 'svg' => false ,'slot'=>false])
<div x-data="{ isActive: false, open: false }">
  <a @click="$event.preventDefault(); open = !open" :aria-expanded="(open || isActive) ? 'true' : 'false'" role="button" aria-haspopup="true" :class="{'rounded-md': !open , 'bg-primary text-white dark:bg-primary': isActive || open, 'pl-5 pr-2 rounded-t-md': isSidebarExpanded, 'mx-3 px-auto rounded-md ': !isSidebarExpanded }" class="flex h-9 py-2 text-white  hover:bg-primary hover:text-white dark:text-light dark:hover:bg-primary">
    <span aria-hidden="true" :class="{ 'mx-auto': !isSidebarExpanded }" class="h-5 w-5">
      <svg class="h-5 w-5 font-bold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {{ $svg }}
      </svg>
    </span>
    <span :class="{ 'hidden': !isSidebarExpanded }" class="ml-2 text-sm">{{ $name }}</span>
    <span :class="{ 'hidden': !isSidebarExpanded }" class="ml-auto" aria-hidden="true">
      <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </span>
  </a>
  <div role="menu" x-show="open" :class="{ 'border border-primary rounded-b-md mt-0':open && isSidebarExpanded }" class=" mt-2 space-y-2" aria-label="{{ $name }}">
    {{ $slot }}
  </div>
</div> --}}

@props(['name' =>Illuminate\Support\Str::uuid() , 'svg' => false, 'slot' => false])
@php
  $name_id =str_replace([' ', '/', '\\', '?', '&', '%', '#', '@', '!', '$', '^', '*', '(', ')', '[', ']', '{', '}', '|', '"', "'", '`', '~', ',', '.', ':', ';', '<', '>'], '_', $name);
@endphp
<div x-data="{ isActive: false,open: localStorage.getItem('dropdown_' + '{{ $name_id }}' + '_open') === 'true' || false, togglesidebarDropdown() { if (this.open) { this.open = false; localStorage.setItem('dropdown_' + '{{ $name_id }}' + '_open', 'false');  } else { this.open = true; localStorage.setItem('dropdown_' + '{{ $name_id }}' + '_open', 'true'); } }}" >
    <a @click="togglesidebarDropdown()" :aria-expanded="open ? 'true' : 'false'"  role="button" aria-haspopup="true" :class="{'rounded-md hover:text-white': !open, '!border-primary border-2 hover:text-black !bg-transparent': isActive || open,'border-primary border-2 !bg-transparent': !isSidebarExpanded && open ,'pl-5 pr-2 rounded-t-md': isSidebarExpanded, 'mx-3 px-auto rounded-md ': !isSidebarExpanded }" class="flex h-9 py-2 text-black dark:text-white  hover:bg-primary hover:text-black  dark:hover:bg-primary">
        <span aria-hidden="true" :class="{ 'mx-auto': !isSidebarExpanded }" class="h-5 w-5">
            <svg class="h-5 w-5 font-bold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {{ $svg }}
            </svg>
        </span>
        <span :class="{ 'hidden': !isSidebarExpanded }" class="ml-2 text-sm">{{ $name }}</span>
        <span :class="{ 'hidden': !isSidebarExpanded }" class="ml-auto" aria-hidden="true">
            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </a>
    <div x-show="open" class="dropdown-container" :class="{ 'border border-primary rounded-b-md mt-0': open && isSidebarExpanded , 'mt-2 ': open && !isSidebarExpanded}" class="space-y-2" aria-label="{{ $name }}">  {{ $slot }} </div>
</div>
