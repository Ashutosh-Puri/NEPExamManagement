<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Department Prefix Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="dept_id" :value="__('Department Name')" />
            <x-input-show id="dept_id" :value="$dept_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="pattern_id" :value="__('Pattern Name')" />
            <x-input-show id="pattern_id" :value="$pattern_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="prefix" :value="__('Department Prefix')" />
            <x-input-show id="prefix" :value="$prefix" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="postfix" :value="__('Department Postfix')" />
            <x-input-show id="postfix" :value="$postfix" />
        </div>
    </div>
</div>
