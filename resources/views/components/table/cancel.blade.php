@props(['slot' => false, 'i' => true])

<button type="button" {!! $attributes->merge(['class' => 'inline-flex text-white  cursor-pointer tems-center justify-center rounded-md bg-red-700 p-1.5 shadow-lg']) !!}>
  @if ($i)
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
    </svg>
  @endif
  {{ $slot }}
</button>
