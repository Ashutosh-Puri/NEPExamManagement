<div>
  <div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Ordinace 163 Students Mark Entry" />
    </x-breadcrumb.breadcrumb>
    <x-card-header heading="Ordinace 163 Students Mark Entry">
    </x-card-header>
    <div>
      <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
          Filter Ordinace 163 Students <x-spinner></x-spinner>
        </div>
        <div class="grid grid-cols-12 md:grid-cols-12">
          <div class="px-1 col-span-6 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Select Class')" />
            <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
              <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
              @forelse ($pattern_classes as $pattern_calss)
                <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }}</x-select-option>
              @empty
                <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
              @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
          </div>
          <div class="px-1 py-2 col-span-5 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="ordinace163master_id" :value="__('Select Activity')" />
            <x-input-select id="ordinace163master_id" wire:model.live="ordinace163master_id" name="ordinace163master_id" class="text-center w-full mt-1" :value="old('ordinace163master_id', $ordinace163master_id)" required autocomplete="ordinace163master_id">
              <x-select-option class="text-start" hidden> -- Select Activity -- </x-select-option>
              @forelse ($ordinace_163s as $ordinace_163id => $ordinace_163name)
                <x-select-option wire:key="{{ $ordinace_163id }}" value="{{ $ordinace_163id }}" class="text-start"> {{ $ordinace_163name }} </x-select-option>
              @empty
                <x-select-option class="text-start">Activities Not Found</x-select-option>
              @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('ordinace163master_id')" class="mt-1" />
          </div>
          <div class="px-1 py-2  pt-4 text-sm text-gray-600 dark:text-gray-400">
            <br>
            <x-table.cancel i="0" wire:click='clear()' class=" px-3">Clear</x-table.cancel>
          </div>
        </div>
        <div class="overflow-x-scroll">

          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>Exam</x-table.th>
                <x-table.th>Pattern Class</x-table.th>
                <x-table.th>Activity</x-table.th>
                <x-table.th>Student</x-table.th>
                <x-table.th>Fee Paid</x-table.th>
                <x-table.th>Seatno</x-table.th>
                <x-table.th>Marks</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($studentordinace163s as $studentordinace163)
                <x-table.tr wire:key="{{ $studentordinace163->id }}">
                  <x-table.td>{{ $studentordinace163->id }} </x-table.td>
                  <x-table.td>{{ $studentordinace163->exam->exam_name }} </x-table.td>
                  <x-table.td> {{ isset($studentordinace163->patternclass->courseclass->classyear->classyear_name) ? $studentordinace163->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($studentordinace163->patternclass->courseclass->course->course_name) ? $studentordinace163->patternclass->courseclass->course->course_name : '-' }} {{ isset($studentordinace163->patternclass->pattern->pattern_name) ? $studentordinace163->patternclass->pattern->pattern_name : '-' }} </x-table.td>
                  <x-table.td>{{ $studentordinace163->ordinace163master->activity_name }} </x-table.td>
                  <x-table.td>{{ $studentordinace163->student->student_name }} </x-table.td>
                  <x-table.td>
                    @if ($studentordinace163->is_fee_paid)
                      <x-status type="success">Yes</x-status>
                    @else
                      <x-status type="danger">No</x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>{{ $studentordinace163->seatno }} </x-table.td>
                  <x-table.td>
                    @if ($studentordinace163->is_fee_paid)
                      <x-form name="save_marks_{{ $studentordinace163->id }}" wire:submit="save_marks({{ $studentordinace163->id }})">
                        <div class="grid grid-flow-col col-auto gap-x-1">
                          <div>
                            <x-text-input id="ordinace_163_marks.{{ $studentordinace163->id }}" name="ordinace_163_marks.{{ $studentordinace163->id }}" wire:model="ordinace_163_marks.{{ $studentordinace163->id }}" placeholder="{{ __('Enter Marks') }}" class="w-[115px]  h-[35px]" />
                            @error("ordinace_163_marks.{$studentordinace163->id}")
                              <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
                            @enderror
                          </div>
                          <div>
                            <x-table.create i="0" type="submit" class="px-2 ">Save</x-table.create>
                          </div>
                        </div>
                        </div>
                      </x-form>
                    @else
                    <x-status type="danger" class="text-center"> Fee Not Paid </x-status>
                    @endif
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td class="text-center" colspan="7">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
          <x-form-btn type="button" wire:click="save_all_marks()">Save All</x-form-btn>
        </div>
      </div>
    </div>
  </div>
</div>
