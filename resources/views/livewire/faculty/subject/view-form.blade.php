<x-card-collapsible heading="Subject Details">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectvertical_id" :value="__('Subject Vertical')" />
            <x-input-show id="subjectvertical_id" :value="$subjectvertical_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_id" :value="__('Programme')" />
            <x-input-show id="course_id" :value="$course_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Pattern Class')" />
            <x-input-show id="patternclass_id" :value="$patternclass_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_sem" :value="__('Subject Semester')" />
            <x-input-show id="subject_sem" :value="$subject_sem" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_name_prefix" :value="__('Subject Name Prefix')" />
            <x-input-show id="subject_name_prefix" :value="$subject_name_prefix" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_name" :value="__('Subject Name')" />
            <x-input-show id="subject_name" :value="$subject_name" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_code" :value="__('Subject Code')" />
            <x-input-show id="subject_code" :value="$subject_code" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory_id " :value="__('Subject Category')" />
            <x-input-show id="subjectcategory_id" :value="$subjectcategory_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_type " :value="__('Subject Type')" />
            <x-input-show id="subject_type" :value="$sub_type" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_credit" :value="__('Subject Credit')" />
            <x-input-show id="subject_credit" :value="$subject_credit" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="classyear_id" :value="__('Class Year')" />
            <x-input-show id="classyear_id" :value="$classyear_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="is_panel" :value="__('Exam Panel')" />
            <x-input-show id="is_panel" :value="$is_panel" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="no_of_sets" :value="__('No Of Sets')" />
            <x-input-show id="no_of_sets" :value="$no_of_sets" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_order" :value="__('Subject Order')" />
            <x-input-show id="subject_order" :value="$subject_order" />
        </div>
    </div>
</x-card-collapsible>

@if ($type['IE'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_ext" :value="__('External Maximum Marks')" />
                <x-input-show id="subject_maxmarks_ext" :value="$subject_maxmarks_ext" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_extpassing" :value="__('External Passing Marks')" />
                <x-input-show id="subject_extpassing" :value="$subject_extpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['IP'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_intpract" :value="__('Internal Practical Maximum Marks')" />
                <x-input-show id="subject_maxmarks_intpract" :value="$subject_maxmarks_intpract" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpractpassing" :value="__('Internal Practical Passing Marks')" />
                <x-input-show id="subject_intpractpassing" :value="$subject_intpractpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['IG'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['I'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['P'])

@elseif ($type['G'])

@elseif ($type['IEP'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-4">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_intpract" :value="__('Internal Practical Maximum Marks')" />
                <x-input-show id="subject_maxmarks_intpract" :value="$subject_maxmarks_intpract" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_ext" :value="__('External Maximum Marks')" />
                <x-input-show id="subject_maxmarks_ext" :value="$subject_maxmarks_ext" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpractpassing" :value="__('Internal Practical Passing Marks')" />
                <x-input-show id="subject_intpractpassing" :value="$subject_intpractpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_extpassing" :value="__('External Passing Marks')" />
                <x-input-show id="subject_extpassing" :value="$subject_extpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['IEG'])
    <x-card-collapsible heading="Subject Marks Details">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
                <x-input-show id="subject_maxmarks" :value="$subject_maxmarks" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
                <x-input-show id="subject_maxmarks_int" :value="$subject_maxmarks_int" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_maxmarks_ext" :value="__('External Maximum Marks')" />
                <x-input-show id="subject_maxmarks_ext" :value="$subject_maxmarks_ext" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
                <x-input-show id="subject_intpassing" :value="$subject_intpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_extpassing" :value="__('External Passing Marks')" />
                <x-input-show id="subject_extpassing" :value="$subject_extpassing" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
                <x-input-show id="subject_totalpassing" :value="$subject_totalpassing" />
            </div>
        </div>
    </x-card-collapsible>
@elseif ($type['E'])
@endif
