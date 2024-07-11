<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Vertical Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_vertical" :value="__('Subject Vertical')" />
            <x-input-show id="subject_vertical" :value="$subject_vertical" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectvertical_shortname" :value="__('Subject Vertical Shortname')" />
            <x-input-show id="subjectvertical_shortname" :value="$subjectvertical_shortname" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectbuckettype_id" :value="__('Subject Bucket Type')" />
            <x-input-show id="subjectbuckettype_id" :value="$subjectbuckettype_id" />
        </div>
    </div>
</div>
