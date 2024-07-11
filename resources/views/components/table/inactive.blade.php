@props(['disabled' => false, 'slot' => false, 'i' => true])

{{-- <button type="button" {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "inline-flex text-white cursor-pointer"]) !!}>
  <span class="relative">
    <input  class = 'hidden peer'  type="checkbox"  />
    <div class="w-14 h-8 rounded-full bg-red-700 dark:bg-red-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-lighter dark:peer-focus:ring-primary-darker  peer-checked:bg-red-700 peer-checked:dark:bg-red-700 transition-all duration-300"></div>
    <div class="absolute inset-y-0 left-0 w-6 h-6 m-1 rounded-full peer-checked:right-0 peer-checked:left-auto  bg-white transition-all duration-300 transform translate-x-0"></div>
</span>
</button> --}}
<button type="button" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'inline-flex text-white cursor-pointer']) !!}>
  <span class="relative">
    @php
      $randomNumber = rand();
    @endphp
    <input id="input_{{ $randomNumber }}" name="input_{{ $randomNumber }}" class="peer hidden" type="checkbox" />
    <div class="w-12 h-7 rounded-full bg-red-700 dark:bg-red-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-lighter dark:peer-focus:ring-primary-darker  peer-checked:bg-red-700 peer-checked:dark:bg-red-700 transition-all duration-300"></div>
    <div class="absolute inset-y-0 left-0 w-5 h-5 m-1 rounded-full peer-checked:right-0 peer-checked:left-auto  bg-white transition-all duration-300 transform translate-x-0"></div>
  </span>
</button>
