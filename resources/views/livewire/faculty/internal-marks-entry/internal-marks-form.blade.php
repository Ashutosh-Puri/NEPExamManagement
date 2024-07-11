<x-card-collapsible heading="Subject Details">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-white flex flex-col space-y-2">
        <div class="flex items-center">
            <x-input-label for="batch_data" class="mr-[77px]" :value="__('Subject Name')" />
            <span class="mr-2">:</span>
            <span> {{ isset($batch_data->subject->subject_code) ? $batch_data->subject->subject_code : '-' }} {{ isset($batch_data->subject->subject_name) ? $batch_data->subject->subject_name : '-' }}</span>
        </div>
        <div class="flex items-center">
            <x-input-label for="batch_data" class="mr-[86px]" :value="__('Subject Type')" />
            <span class="mr-2">:</span>
            <span>{{ $batch_data->subject_type == 'I' || $batch_data->subject_type == 'IG' ? 'Internal' : ($batch_data->subject_type == 'IGE' || $batch_data->subject_type == 'IEG' ? 'External' : ($batch_data->subject_type == 'G' ? 'Grade' : 'Practical')) }}</span>
        </div>
        <div class="flex items-center">
            <x-input-label for="batch_data" class="mr-[114px]">Batch Id</x-input-label>
            <span class="mr-2">:</span>
            <span>{{ $batch_data->created_at->year . $batch_data->subject_id . str_pad($batch_data->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex items-center">
            <x-input-label for="batch_data" class="mr-[11px]">Last Date of Marks Entry</x-input-label>
            <span class="mr-2">:</span>
            <span>{{ isset($batch_data->exam_patternclass->intmarksend_date) ? Carbon\Carbon::parse($batch_data->exam_patternclass->intmarksend_date)->format('Y-m-d h:i:s A') : '' }}</span>
        </div>
    </div>
    <div class="px-5 py-2 text-gray-600 dark:text-gray-400 sm:p-6">
        <x-input-radio class="w-5 h-5 cursor-pointer" id="show_non_evaluated" value="1" wire:model.live="selectedOption" name="show_student_list" />
        <x-input-label for="show_non_evaluated" class="inline mb-1 mx-2" :value="__('Show Non-Evaluated Student List')" />
        <x-input-radio class="w-5 h-5 cursor-pointer" id="show_all" value="2" wire:model.live="selectedOption" name="show_student_list" />
        <x-input-label for="show_all" class="inline mb-1 mx-2" :value="__('Show All Student List')" />
    </div>
</x-card-collapsible>

@if ($selectedOption == 1)
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <x-input-label class="ml-5">Non-Evaluated Records Found</x-input-label>
        <div class="px-2 pb-2 text-sm text-gray-600 dark:text-white flex flex-col space-y-2">
            <div>
                <livewire:faculty.internal-marks-entry.non-evaluated-marks-entry.all-non-evaluated-marks-entry-table :key="$selectedOption" :batch_data="$batch_data" />
            </div>
        </div>
    </div>
@elseif($selectedOption == 2)
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <x-input-label class="ml-5">Evaluated & Non-Evaluated Records Found</x-input-label>
        <div class="px-2 pb-2 text-sm text-gray-600 dark:text-white flex flex-col space-y-2">
            <div>
                <livewire:faculty.internal-marks-entry.eval-non-eval-marks-entry.all-eval-non-eval-marks-entry-table :key="$selectedOption" :batch_data="$batch_data" />
            </div>
        </div>
    </div>
@endif
