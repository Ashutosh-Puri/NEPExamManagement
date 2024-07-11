<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        @if($mode == 'view')
           <span>View Exam Panel</span>
           <x-table.download i="0" class="float-end text-xs text-white" wire:click="setmode('add')">Back</x-table.download>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Pattern Class Name')" />
            <x-input-show id="patternclass_id" :value="$patternclass_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_id" :value="__('Subject Name')" />
            <x-input-show id="subject_id" :value="$patternclass_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="post_id" :value="__('Post Name')" />
            <x-input-show id="post_id" :value="$post_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="department_id" :value="__('Department Name')" />
            <x-input-show id="department_id" :value="$department_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_id" :value="__('Faculty Name')" />
            <x-input-show id="faculty_id" :value="$faculty_id" />
        </div>
    </div>
</div>
