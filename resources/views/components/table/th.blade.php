@props(['disabled' => false, 'name' => false, 'slot' => false, 'sort' => false, 'sort_by' => false])
<th scope="col" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'cursor-pointer  whitespace-nowrap px-2 py-2 text-start text-xs font-medium uppercase']) !!}>
  {{ $slot }}
  <span class="align-bottom mx-1  inline-flex p-0">
    @if ($sort !== $name)
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
      </svg>
    @elseif($sort_by === 'ASC')
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-4 animate-bounce">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25 12 21m0 0-3.75-3.75M12 21V3" />
      </svg>
    @elseif($sort_by === 'DESC')
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-4 animate-bounce">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
      </svg>
    @endif
  </span>
</th>
