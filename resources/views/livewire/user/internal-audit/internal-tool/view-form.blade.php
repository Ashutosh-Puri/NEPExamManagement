<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Internal Tool Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="toolname" :value="__('Tool Name')" />
            <x-input-show id="toolname" :value="$toolname" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_type" :value="__('Programme Type')" />
            <x-input-show id="course_type" :value="$course_type" />
        </div>
    </div>
</div>
