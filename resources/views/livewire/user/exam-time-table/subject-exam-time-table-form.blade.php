<div>

  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      @if ($mode == 'add')
        Create
      @else
        Edit
      @endif Subject Wise Exam Time Table <x-spinner />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 py-2">
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subjectcategory_id" :value="__('Select Subject Category')" />
        <x-input-select id="subjectcategory_id" wire:model.live="subjectcategory_id" name="subjectcategory_id" class="text-center w-full mt-1" :value="old('subjectcategory_id', $subjectcategory_id)" required autocomplete="subjectcategory_id">
          <x-select-option class="text-start" hidden> -- Select Subject Category -- </x-select-option>
          @forelse ($subject_categories as $subject_category_id => $subject_category_name)
            <x-select-option wire:key="{{ $subject_category_id }}" value="{{ $subject_category_id }}" class="text-start"> {{ $subject_category_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subject Categories Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subjectcategory_id')" class="mt-1" />
      </div>

      <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subject_id" :value="__('Select Subject')" />
        <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
          <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
          @forelse ($subjects as $subjectid => $subjectname)
            <x-select-option wire:key="{{ $subjectid }}" value="{{ $subjectid }}" class="text-start"> {{ $subjectname }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subjects Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="examdate" :value="__('Select Date')" />
        <x-text-input id="examdate" type="date" wire:model.live="examdate" name="examdate" class="w-full mt-1" />
        <x-input-error :messages="$errors->get('examdate')" class="mt-1" />
      </div>

      <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="timeslot_id" :value="__('Select Time Slot')" />
        <x-input-select id="timeslot_id" wire:model.live="timeslot_id" name="timeslot_id" class="text-center w-full mt-1">
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
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th name="id">No.</x-table.th>
            <x-table.th name="exampatternclass">Pattern Class</x-table.th>
            <x-table.th name="examdate">Exam Date</x-table.th>
            <x-table.th name="timeslot_id">Time Slot</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($exampatternclasses as $exampatternclass)
            <x-table.tr>
              <x-table.tr wire:key="{{ $exampatternclass->id }}">
                <x-table.td> {{ $exampatternclass->id }} </x-table.td>
                <x-table.td> {{ isset($exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($exampatternclass->patternclass->courseclass->course->course_name) ? $exampatternclass->patternclass->courseclass->course->course_name : '-' }}  {{ isset($exampatternclass->patternclass->pattern->pattern_name) ? $exampatternclass->patternclass->pattern->pattern_name : '-' }}</x-table.td>
                <x-table.td>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="examdates.{{ $exampatternclass->id }}" />
                    <x-text-input id="examdates.{{ $exampatternclass->id }}" type="date" wire:model="examdates.{{ $exampatternclass->id }}" name="examdates.{{ $exampatternclass->id }}" class="w-full mt-1" />
                    <x-input-error :messages="$errors->get('examdates.{$exampatternclass->id')" class="mt-1" />
                  </div>
                </x-table.td>

                <x-table.td>
                  <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="timeslot_ids.{{ $exampatternclass->id }}" />
                    <x-input-select id="timeslot_ids.{{ $exampatternclass->id }}" wire:model="timeslot_ids.{{ $exampatternclass->id }}" name="timeslot_ids.{{ $exampatternclass->id }}" class="text-center w-full mt-1">
                      <x-select-option class="text-start" hidden> -- Select Time Slot -- </x-select-option>
                      @forelse ($timeslots as $timeid=>$timeslot)
                        <x-select-option wire:key="{{ $timeid }}" value="{{ $timeid }}" class="text-start"> {{ $timeslot }} </x-select-option>
                      @empty
                        <x-select-option class="text-start">Time Slot Not Found</x-select-option>
                      @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('timeslot_ids.{$exampatternclass->id}')" class="mt-1" />
                  </div>
                </x-table.td>
              </x-table.tr>
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
  </div>
