@props(["disabled" => false, "slot" => false,"i"=>true])

<button   {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(['type'=>"button","class" => "inline-flex text-white  cursor-pointer"]) !!}>
  <span class="inline-flex items-center justify-center rounded-md bg-green-700 p-1.5 shadow-lg">
    @if ($i)
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-1 h-5 w-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
    @endif
    {{ $slot }}
  </span>
</button>

