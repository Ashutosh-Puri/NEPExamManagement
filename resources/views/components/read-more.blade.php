@props(['slot'=>false,'limit' => 50])

<div x-data="{ showFullText: false }">
    <div x-show="!showFullText" x-cloak>
        <div style=" overflow: hidden; line-height: 1.5em;">
            {!! Str::words($slot, $limit, '... <button @click="showFullText = true" class="text-blue-500 cursor-pointer"> Read More </button>') !!}
        </div>
    </div>
    <div x-show="showFullText">
        {{ $slot }}
        <button x-show="showFullText" @click="showFullText = false" class="text-blue-500 cursor-pointer pl-2">
            Show Less
        </button>
    </div>
</div>

