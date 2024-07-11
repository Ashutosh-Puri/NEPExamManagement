@props(['last'=>false ,'current'=>false ,'step'=>false ,'name'=>false])
<div x-data="{ showTooltip: false }" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" @if ($current==$step) class="border-b-2 border-primary-darker py-2" @endif>
    @if ($step <= $current)
        <a wire:click='move_to({{ $step }})' class="cursor-pointer">
    @endif
    <li class="flex items-center cursor-pointer @if ($current==$step)  text-primary dark:text-primary @endif">
        <span class="flex items-center  justify-center w-5 h-5 mr-1 text-xs border rounded-full shrink-0 @if ($current==$step)  border-parimary dark:border-primary  @endif">
           {{ $step }}
        </span>
        <span class="hidden md:flex  align-top">  {{ $name }}</span>
        @if ($current==$step)
            <span class="md:hidden text-xs align-top">  {{ $name }}</span>
        @else
            <span class="md:hidden align-top" x-show="showTooltip">  {{ $name }}</span>
        @endif
       {{-- @if (!$last)
       <svg class="w-3 h-3 mx-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
       </svg>
       @endif --}}
    </li>
    @if ($step <= $current)
        </a>
    @endif
</div>



