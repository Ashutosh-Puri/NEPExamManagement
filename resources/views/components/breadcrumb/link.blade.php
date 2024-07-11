@props(['slot' => false, 'route' => false, 'last' => false, 'name' => false])

@if ($route)
  <li>
    <div class="flex items-center">
      <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
      </svg>
      <a href="{{ route($route) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">{{ $name }}</a>
    </div>
  </li>
@else
  <li aria-current="page">
    <div class="flex items-center">
      <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
      </svg>
      <span class="ms-1 text-sm  sm:text-xs text-primary-darker dark:text-primary-light font-bold md:ms-2  truncate">{{ $name }}</span>
    </div>
  </li>
@endif
