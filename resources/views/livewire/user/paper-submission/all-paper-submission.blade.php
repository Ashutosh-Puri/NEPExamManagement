<div>
  <x-card-header heading="All Paper Submission's">
  </x-card-header>
  <x-form wire:submit="save()">
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Paper Set
        <x-spinner />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="patternclass_id" :value="__('Select Class')" />
          <x-required />
          <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
            <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
            @forelse ($patternclasses as $pattern_calss)
              <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }} </x-select-option>
            @empty
              <x-select-option class="text-start">Patternclass </x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="subject_id" :value="__('Subject')" />
          <x-required />
          <x-input-select id="subject_id" wire:model="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autofocus autocomplete="subject_id">
            <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
            @foreach ($subjects as $e_id => $ename)
              <x-select-option wire:key="{{ $e_id }}" value="{{ $e_id }}" class="text-start">{{ $ename }}</x-select-option>
            @endforeach
          </x-input-select>
          <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
        </div>
      </div>


      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="faculty_id" :value="__('Select Faculty')" />
          <x-input-select id="faculty_id" wire:model="faculty_id" name="faculty_id" class="text-center w-full mt-1" :value="old('faculty_id', $faculty_id)" required autocomplete="faculty_id">
              <x-select-option hidden> -- Select Faculty -- </x-select-option>
              @forelse ($faculties as $faculty_id => $faculty_name)
                  <x-select-option wire:key="{{ $faculty_id }}" value="{{ $faculty_id }}" class="text-start"> {{ $faculty_name }} </x-select-option>
              @empty
                  <x-select-option class="text-start">Teachers Not Found</x-select-option>
              @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('faculty_id')" class="mt-1" />
      </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="noofsets" :value="__('No of Set')" />
          <x-required />
          <x-input-select id="noofsets" wire:model="noofsets" name="noofsets" class="text-center  w-full mt-1" :value="old('noofsets', $noofsets)" required autocomplete="noofsets">
            <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
            <x-select-option class="text-start" value="1">1</x-select-option>
            <x-select-option class="text-start" value="2">2</x-select-option>
            <x-select-option class="text-start" value="3">3</x-select-option>
            <x-select-option class="text-start" value="4">4</x-select-option>
          </x-input-select>
          <x-input-error :messages="$errors->get('noofsets')" class="mt-2" />
        </div>

      </div>
      <x-form-btn>
        Submit
      </x-form-btn>
    </div>

    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
      <x-table.frame a='0'>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Name</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
                <x-table.th wire:click="sort_column('chairman_id')" name="chairman_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name</x-table.th>
                <x-table.th wire:click="sort_column('user_id')" name="user_id" :sort="$sortColumn" :sort_by="$sortColumnBy">User Name</x-table.th>
                <x-table.th wire:click="sort_column('noofsets')" name="noofsets" :sort="$sortColumn" :sort_by="$sortColumnBy">No of Set</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($papersubmissions as $papersubmission)
                <x-table.tr wire:key="{{ $papersubmission->id }}">
                  <x-table.td>{{ $papersubmission->id }} </x-table.td>
                  <x-table.td>{{ $papersubmission->exam->exam_name }} </x-table.td>
                  <x-table.td>{{ $papersubmission->subject->subject_name }} </x-table.td>
                  <x-table.td>{{ isset($papersubmission->faculty->faculty_name)?$papersubmission->faculty->faculty_name:''; }} </x-table.td>
                  <x-table.td>{{ isset($papersubmission->user->name) ? $papersubmission->user->name : '' }} </x-table.td>
                  <x-table.td>{{ $papersubmission->noofsets }} </x-table.td>
                  <x-table.td>
                    @if ($papersubmission->deleted_at)
                    @elseif($papersubmission->status == 1)
                      <x-table.active wire:click="update_status({{ $papersubmission->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $papersubmission->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    <x-table.delete wire:click="deleteconfirmation({{ $papersubmission->id }})" />
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='8' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot:body>
      </x-table.frame>
    </div>

  </x-form>
</div>
