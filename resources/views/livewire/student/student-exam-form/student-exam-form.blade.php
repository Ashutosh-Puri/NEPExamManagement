<div class="">
  <x-card-header heading="Student Exam Form" />
  <x-form wire:submit="student_exam_form_save()">
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Exam Form <x-spinner />
      </div>
      @if ($page == 1)
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="medium_instruction" :value="__('Select Medium Of Instruction')" /><x-required />
            <x-input-select id="medium_instruction" wire:model="medium_instruction" name="medium_instruction" class="text-center  w-full mt-1" :value="old('medium_instruction', $medium_instruction)" required autocomplete="medium_instruction">
              <x-select-option class="text-start" hidden> -- Select Medium Of Instruction -- </x-select-option>
              <x-select-option class="text-start" value="E">English</x-select-option>
              <x-select-option class="text-start" value="M">Marathi</x-select-option>
              <x-select-option class="text-start" value="H">Hindi</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('medium_instruction')" class="mt-2" />
          </div>
          @if ($abcid_option['show_abcid'])
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="abcid" :value="__('ABC ID')" /><x-required />
              <x-text-input id="abcid" type="number" wire:model="abcid" name="abcid" class="w-full mt-1" :value="old('abcid', $abcid)" autofocus autocomplete="abcid" />
              <x-input-error :messages="$errors->get('abcid')" class="mt-2" />
            </div> 
          @endif
        </div>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.td colspan="8" class="bg-primary-darker text-white">
                <p class="text-center font-bold py-2 uppercase border-b-2">Subject Details</p>
              </x-table.td>
            </x-table.tr>
            <x-table.tr>
              <x-table.th>SEM</x-table.th>
              <x-table.th>Subject Code</x-table.th>
              <x-table.th>Subject Name</x-table.th>
              <x-table.th>Internal</x-table.th>
              <x-table.th>External</x-table.th>
              <x-table.th>Practical</x-table.th>
              <x-table.th>Grade</x-table.th>
              <x-table.th>Project</x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($backlog_subjects as $index1 => $b_subject)
              @if ($index1 === 0)
                <x-table.tr>
                  <x-table.td colspan="8" class="bg-red-700 text-white">
                    <p class="text-center font-bold  uppercase">Backlog Subjects</p>
                  </x-table.td>
                </x-table.tr>
              @endif
              <x-table.tr wire:key="{{ $b_subject->id }}" class="dark:odd:bg-primary-darker odd:bg-primary-lighter dark:text-white text-black py-1">
                <x-table.td> {{ $b_subject->subject_sem }}</x-table.td>
                <x-table.td> {{ $b_subject->subject_code }}</x-table.td>
                <x-table.td> {{ $b_subject->subject_name }}</x-table.td>
                <x-table.td> <input type="checkbox" name="internals.{{ $b_subject->id }}" wire:model="internals.{{ $b_subject->id }}" id="internals.{{ $b_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="externals.{{ $b_subject->id }}" wire:model="externals.{{ $b_subject->id }}" id="externals.{{ $b_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="practicals.{{ $b_subject->id }}" wire:model="practicals.{{ $b_subject->id }}" id="practicals.{{ $b_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="grades.{{ $b_subject->id }}" wire:model="grades.{{ $b_subject->id }}" id="grades.{{ $b_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="projects.{{ $b_subject->id }}" wire:model="projects.{{ $b_subject->id }}" id="projects.{{ $b_subject->id }}" class="disabled" disabled></x-table.td>
              </x-table.tr>
            @endforeach
            @foreach ($regular_subjects as $index2 => $r_subject)
              @if ($index2 === 0)
                <x-table.tr>
                  <x-table.td colspan="8" class="bg-green-700 text-white">
                    <p class="text-center font-bold  uppercase">Regular Subjects</p>
                  </x-table.td>
                </x-table.tr>
              @endif
              <x-table.tr wire:key="{{ $r_subject->id }}" class="dark:odd:bg-primary-darker odd:bg-primary-lighter dark:text-white text-black py-1">
                <x-table.td> {{ $r_subject->subject_sem }}</x-table.td>
                <x-table.td> {{ $r_subject->subject_code }}</x-table.td>
                <x-table.td> {{ $r_subject->subject_name }}</x-table.td>
                <x-table.td> <input type="checkbox" name="internals.{{ $r_subject->id }}" wire:model="internals.{{ $r_subject->id }}" id="internals.{{ $r_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="externals.{{ $r_subject->id }}" wire:model="externals.{{ $r_subject->id }}" id="externals.{{ $r_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="practicals.{{ $r_subject->id }}" wire:model="practicals.{{ $r_subject->id }}" id="practicals.{{ $r_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="grades.{{ $r_subject->id }}" wire:model="grades.{{ $r_subject->id }}" id="grades.{{ $r_subject->id }}" class="disabled" disabled></x-table.td>
                <x-table.td> <input type="checkbox" name="projects.{{ $r_subject->id }}" wire:model="projects.{{ $r_subject->id }}" id="projects.{{ $r_subject->id }}" class="disabled" disabled></x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
        @if (!$extra_credit_subjects->isEmpty())
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.td colspan="8" class="bg-primary-darker text-white">
                  <p class="text-center font-bold py-2 uppercase border-b-2">Extra Credit Subjects</p>
                </x-table.td>
              </x-table.tr>
              <x-table.tr>
                <x-table.th>SEM</x-table.th>
                <x-table.th>Subject Code</x-table.th>
                <x-table.th>Subject Name</x-table.th>
                <x-table.th>Credit</x-table.th>
                <x-table.th>Grade</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($extra_credit_subjects as $index3 => $e_subject)
                <x-table.tr wire:key="{{ $e_subject->id }}" class="dark:odd:bg-primary-darker odd:bg-primary-lighter dark:text-white text-black py-1">
                  <x-table.td> {{ $e_subject->subject_sem }}</x-table.td>
                  <x-table.td> {{ $e_subject->subject_code }}</x-table.td>
                  <x-table.td> {{ $e_subject->subject_name }}</x-table.td>
                  <x-table.td> {{ $e_subject->credit ?? 2 }}</x-table.td>
                  <x-table.td> <input type="checkbox" name="grades" id=""></x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        @endif
        <div>
          <x-form-btn wire:click="next_back()" type="button">Next</x-form-btn>
          <x-form-btn wire:click="cancel()" type="button">Cancel</x-form-btn>
        </div>
    </div>
  @elseif ($page == 2)
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Fees
      </div>
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>Fee Type</x-table.th>
            <x-table.th>Fee Amount</x-table.th>
            <x-table.th>Remark</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @php
            $total = 0;
          @endphp
          @foreach ($exam_fee_courses as $course_fee)
            <x-table.tr wire:key="{{ $course_fee->id }}" class="dark:even:bg-primary-darker even:bg-primary-lighter dark:text-white text-black py-1">
              <x-table.td> {{ $course_fee->examfee->fee_name }}</x-table.td>
              <x-table.td> {{ $course_fee->fee }}</x-table.td>
              <x-table.td> {{ $course_fee->remark }}</x-table.td>
            </x-table.tr>
            @php
              $total = $total + $course_fee->fee;
            @endphp
          @endforeach
          <x-table.tr class=" dark:text-white text-black py-1">
            <x-table.td> TOTAL FEE</x-table.td>
            <x-table.td> {{ $total }}</x-table.td>
            <x-table.td></x-table.td>
          </x-table.tr>
        </x-table.tbody>
      </x-table.table>
      <div>
        <x-form-btn wire:loading.attr='disabled' wire:target='student_exam_form_save()'>Submit Exam Form</x-form-btn>
        <x-form-btn class="float-start" wire:click="next_back()" type="button">Back</x-form-btn>
        <x-form-btn wire:click="cancel()" type="button">Cancel</x-form-btn>
      </div>
    </div>
    @endif
  </x-form>
</div>
