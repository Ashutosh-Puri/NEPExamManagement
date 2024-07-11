<div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Edit Subject Wise Exam Time Table <x-spinner />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 py-2">
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subject_id" :value="__('Subject Name')" />
        <x-input-show id="subject_id" :value="$subject_id" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="exam_patternclasses_id" :value="__('Pattern Class Name')" />
        <x-input-show id="exam_patternclasses_id" :value="$exam_patternclasses_id" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="examdate" :value="__('Select Date')" />
        <x-text-input id="examdate" type="date" wire:model="examdate" name="examdate" class="w-full mt-1" />
        <x-input-error :messages="$errors->get('examdate')" class="mt-1" />
      </div>

      <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="timeslot_id" :value="__('Select Time Slot')" />
        <x-input-select id="timeslot_id" wire:model="timeslot_id" name="timeslot_id" class="text-center w-full mt-1">
          <x-select-option class="text-start" hidden> -- Select Time Slot -- </x-select-option>
          @forelse ($timeslots as $timeid=>$timeslot)
            <x-select-option wire:key="{{ $timeid }}" value="{{ $timeid }}" class="text-start"> {{ $timeslot }} </x-select-option>
          @empty
            <x-select-option class="text-start">Time Slot Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('timeslot_id')" class="mt-1" />
      </div>
    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
  </div>
</div>
