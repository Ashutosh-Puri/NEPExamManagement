@props([
    'heading' => false,
    'on' => true,
    'slot' => true,
])

<div x-data="{ on: {{ $on ? 'true' : 'false' }} }" {!! $attributes->merge(['class' => 'm-2 overflow-hidden border dark:border-primary-darker shadow']) !!}>
    <div class="px-2 py-1 font-semibold dark:text-light bg-primary">
        <div class="grid grid-cols-2 md:grid-cols-2 items-center">
            <p class="text-white text-md font-medium">{{ $heading }}</p>
            <div class="flex justify-end">
                <button type="button" class="bg-green-600 border border-white text-white hover:bg-green-600 rounded p-1" @click="on = !on" x-show="!on">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </button>
                <button type="button" class="bg-green-600 border border-white text-white hover:bg-green-600 rounded p-1" @click="on = !on" x-show="on">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div>
        <div class="">
            <div x-show="on">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
