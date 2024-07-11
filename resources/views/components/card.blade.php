@props(['disabled' => false,'heading' => false,'content' => false,'button' => false,])

<div class="m-2 pb-3 overflow-hidden rounded shadow">
    <div class="px-2 py-1 font-semibold dark:text-light bg-primary">
        <div class="grid grid-cols-2 md:grid-cols-2 items-center">
            <p class="text-white text-md font-medium">{{ $heading }}</p>
            <div class="flex justify-end">
               {{ $button }}
            </div>
        </div>
    </div>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-1">
            {{ $content }}
        </div>
    </div>
</div>
