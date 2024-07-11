@props(['slot'=>false])
<div class="relative flex overflow-x-hidden">
    <p class="animate-marquee whitespace-nowrap font-bold">
      {{ $slot }}
    </p>
</div>