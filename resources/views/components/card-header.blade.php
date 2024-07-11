@props([ 'disabled'=>false, 'slot'=>false, 'heading'=>false,])
<div class="py-2  border font-semibold dark:border-primary-darker dark:text-light bg-primary-darker">
    <div class="grid grid-col-12">
        <span class="col-span-9  w-full overflow-x-auto whitespace-nowrap">
            <p class="text-white text-md font-medium align-middle flex ml-2 text-lg">{{ $heading }}</p>
        </span>
        <span class="col-start-10 col-span-1  ">
            <span class="float-right">
                {{ $slot }}
            </span>
        </span>
    </div>
</div>