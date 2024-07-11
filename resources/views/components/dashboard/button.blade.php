@props(['slot'=>false])
<button type="button"  {{ $attributes->merge(['type' => 'button', 'class' => ' inline-flex items-center justify-center rounded-xl border-2 bg-white px-3 py-2 my-2 text-md font-semibold text-gray-800 transition hover:text-green-500 dark:border-primary']) }} >
    {{ $slot }}
</button>