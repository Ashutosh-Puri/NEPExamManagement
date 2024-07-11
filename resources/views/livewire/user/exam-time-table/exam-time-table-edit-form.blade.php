<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Edit Class Wise Exam Time Table <x-spinner />
  </div>

  <x-table.frame a="0">
    <x-slot:header>
    </x-slot>
    <x-slot:body>
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="sem" :value="__('Select Semester')" />
          <x-input-select id="sem" wire:model.live="sem" name="sem" class="text-center w-full mt-1">
            <x-select-option class="text-start" hidden> -- Select Semester -- </x-select-option>
            @forelse ($semesters as $semester)
              <x-select-option wire:key="{{ $semester->id }}" value="{{ $semester->semester }}" class="text-start"> {{ $semester->semester }} </x-select-option>
            @empty
              <x-select-option class="text-start">Semister Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('semester')" class="mt-1" />
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

      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>No.</x-table.th>
            <x-table.th>Sem</x-table.th>
            <x-table.th>Subject Code & Subject Name </x-table.th>
            <x-table.th>Exam Date</x-table.th>
            <x-table.th>Time Slot</x-table.th>
            <x-table.th>Action</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($subjects as $subject)
            <x-table.tr wire:key="{{ $subject->id }}">
              <x-table.td> {{ $subject->id }}</x-table.td>
              <x-table.td> {{ $subject->subject_sem }} </x-table.td>
              <x-table.td>
                <x-table.text-scroll class="w-full"> {{ $subject->subject_code }} {{ $subject->subject_name }} </x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="examdates" />
                  <x-text-input id="examdates.{{ $subject->id }}" type="date" wire:model="examdates.{{ $subject->id }}" name="examdates.{{ $subject->id }}" class="w-full mt-1" />
                  <x-input-error :messages="$errors->get('examdates.{ $subject->id }')" class="mt-1" />
                </div>
              </x-table.td>
              <x-table.td>
                <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="timeslot_ids.{{ $subject->id }}" />
                  <x-input-select id="timeslot_ids.{{ $subject->id }}" wire:model="timeslot_ids.{{ $subject->id }}" name="timeslot_ids.{{ $subject->id }}" class="text-center !min-w-fit mt-1">
                    <x-select-option class="text-start" hidden> -- Select Time Slot -- </x-select-option>
                    @forelse ($timeslots as $timeid=>$timeslot)
                      <x-select-option wire:key="{{ $timeid }}" value="{{ $timeid }}" class="text-start"> {{ $timeslot }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Time Slot Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('timeslot_ids.{$subject->id}')" class="mt-1" />
                </div>
              </x-table.td>
              <x-table.td>
                  @if (in_array($subject->id,$deletetable))
                    <x-table.delete wire:click="delete_time_table_entry({{ array_search($subject->id, $deletetable) }})" />
                  @endif
              </x-table.td>
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </x-slot>
  </x-table.frame>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
