@props(["disabled" => false, "slot" => false,"i"=>true])

<button type="button"  {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "inline-flex text-white  cursor-pointer"]) !!}>
  <span class="inline-flex items-center justify-center rounded-md bg-blue-700 p-1.5 shadow-lg">
    @if ($i)
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-1 h-5 w-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"  />
      </svg>
    @endif
    {{ $slot }}
  </span>
</button>


  