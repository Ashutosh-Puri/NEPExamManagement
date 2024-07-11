@props(['slot'=>false])
<span  {!! $attributes->merge(["class" => "block overflow-x-auto p-1  scrollbar-sm whitespace-nowrap w-40"]) !!} >
    {{ $slot }}             
</span>