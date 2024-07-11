<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Category Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory" :value="__('Subject Category')" />
            <x-input-show id="subjectcategory" :value="$subjectcategory" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory_shortname" :value="__('Subject Category Shortname')" />
            <x-input-show id="subjectcategory_shortname" :value="$subjectcategory_shortname" />
        </div>
    </div>
</div>
