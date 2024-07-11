<div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      @if ($mode == 'add')
        Create
      @else
        Edit
      @endif Vertical Wise Exam Time Table <x-spinner />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 py-2">
      <div class="px-5 py-2 col-span-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subjectvertical_id" :value="__('Select Subject Verticals')" /><x-required />
        <x-input-select id="subjectvertical_id" wire:model.live="subjectvertical_id" name="subjectvertical_id" class="text-center w-full mt-1" :value="old('subjectvertical_id', $subjectvertical_id)" required autocomplete="subjectvertical_id">
          <x-select-option class="text-start" hidden> -- Select Subject Verticals -- </x-select-option>
          @forelse ($subject_verticals as $subject_vertical_id => $subject_vertical_name)
            <x-select-option wire:key="{{ $subject_vertical_id }}" value="{{ $subject_vertical_id }}" class="text-start"> {{ $subject_vertical_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subject Categories Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subjectvertical_id')" class="mt-1" />
      </div>
      {{-- 
      <div class="px-2 py-2  col-span-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="examexampatternclass_ids" :value="__('Select Pattern Class')" class="mb-2" /><x-required />
        <x-select2.select multiple="multiple" style="width:100%;" id="examexampatternclass_ids" name="examexampatternclass_ids" wire:model='examexampatternclass_ids' class="rounded-lg !w-full ">
          <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
          @foreach ($exampatternclasses as $exampattern_class)
            <x-select-option wire:key="{{ $exampattern_class->id }}" value="{{ $exampattern_class->id }}" class="text-start"> {{ $exampattern_class->patternclass->courseclass->classyear->classyear_name ?? '-' }} {{ $exampattern_class->patternclass->courseclass->course->course_name ?? '-' }} {{ $exampattern_class->patternclass->pattern->pattern_name ?? '-' }}</x-select-option>
          @endforeach
        </x-select2.select>
      </div> 
    --}}

      <div class="px-5 py-2  col-span-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="examexampatternclass_ids" :value="__('Select Pattern class')" />
        <x-required />
        <x-input-select id="exampatternclass_ids" wire:model="exampatternclass_ids" name="exampatternclass_ids" class="text-center w-full mt-1" multiple autocomplete="exampatternclass_ids">
          <x-select-option class="text-start" hidden> -- Select Pattern class -- </x-select-option>
          @forelse ($exampatternclasses as $exampattern_class)
            <x-select-option wire:key="{{ $exampattern_class->id }}" value="{{ $exampattern_class->id }}" class="text-start"> {{ $exampattern_class->patternclass->courseclass->classyear->classyear_name ?? '-' }} {{ $exampattern_class->patternclass->courseclass->course->course_name ?? '-' }} {{ $exampattern_class->patternclass->pattern->pattern_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('exampatternclass_ids')" class="mt-1" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="examdate" :value="__('Select Date')" />
        <x-required />
        <x-text-input id="examdate" type="date" wire:model="examdate" name="examdate" required class="w-full mt-1" />
        <x-input-error :messages="$errors->get('examdate')" class="mt-1" />
      </div>

      <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="timeslot_id" :value="__('Select Time Slot')" />
        <x-required />
        <x-input-select id="timeslot_id" wire:model="timeslot_id" name="timeslot_id" required class="text-center w-full mt-1">
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
