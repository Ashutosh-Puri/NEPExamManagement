@props(["disabled" => false, "slot" => false,"i"=>true])

<button type="button" {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "inline-flex text-white  cursor-pointer"]) !!}>
  <span class="inline-flex items-center justify-center rounded-md bg-orange-700 p-1.5 shadow-lg">
    @if ($i)
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-1 h-5 w-5 dark:text-white">
        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
      </svg>
    @endif
    {{ $slot }}
  </span>
</button>

