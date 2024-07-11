@props(['slot'=>false,'heading'=>false])
<div   {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-xl border-2  p-3  dark:border-primary']) }} >
    <div class="text-lg font-bold leading-none text-gray-800 dark:text-white ">{{ $heading }}</div>
    <div class="mt-2">
        {{ $slot }}
    </div>
</div>