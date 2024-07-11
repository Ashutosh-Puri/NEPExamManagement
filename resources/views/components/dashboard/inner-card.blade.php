@props(['slot'=>false ,'heading'=>false])
<div class="gap-y-5 border-2 border-white py-2 px-3 rounded-md ">
    <p class="font-bold mb-1">{{ $heading }} </p>
    {{ $slot }}
</div>