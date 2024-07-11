@props(['slot'=>false ,'heading'=>false])
<div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
    <h1 class="text-2xl font-semibold">{{ $heading }}</h1>
    {{ $slot }}
</div>