<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Internal Tool Document Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="internaltoolmaster_id" :value="__('Tool Name')" />
            <x-input-show id="internaltoolmaster_id" :value="$internaltoolmaster_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="internaltooldoc_id" :value="__('Document Name')" />
            <x-input-show id="internaltooldoc_id" :value="$internaltooldoc_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="is_multiple" :value="__('Is Multiple')" />
            <x-input-show id="is_multiple" :value="$is_multiple" />
        </div>
    </div>
</div>
