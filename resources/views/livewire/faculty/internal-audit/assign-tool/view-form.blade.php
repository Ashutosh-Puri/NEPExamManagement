<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Assigned Tool Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="academicyear_id" :value="__('Academic Year')" />
            <x-input-show id="academicyear_id" :value="$academicyear_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_id" :value="__('Programme')" />
            <x-input-show id="course_id" :value="$course_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Patternclass')" />
            <x-input-show id="patternclass_id" :value="$patternclass_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_id" :value="__('Subject')" />
            <x-input-show id="subject_id" :value="$subject_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="assignedtool_id" :value="__('Assigned Tools')" />
            <x-input-show id="assignedtool_id" :value="$assignedtool_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="assignedtool_id" :value="__('Document Name')" />
            @foreach ($document_names as $faculty_subject_tool_doc)
                <x-input-show id="assignedtool_id" :value="$faculty_subject_tool_doc->internaltooldocument->internaltooldocumentmaster->doc_name" />
            @endforeach
        </div>
    </div>
</div>
