@props(['disabled' => false])
<div class="inline-block relative max-w-xs">
    <label class="sr-only">Search</label>
    <input type="search" {!! $attributes->merge(['class' => 'block w-full rounded-lg border-gray-200 px-3 h-10 ps-9 text-sm shadow-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-400 dark:focus:ring-gray-600 dark:border-primary-darker border']) !!}  placeholder="Search">
    <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
      <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.3-4.3" />
      </svg>
    </div>
  </div>