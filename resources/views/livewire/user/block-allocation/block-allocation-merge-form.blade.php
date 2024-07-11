<div>

  <div class="grid grid-cols-1 md:grid-cols-1">
    <section class="overflow-x-scroll">
      <x-table.table>
        <x-table.tbody>
          <x-table.tr>
            <x-table.td colspan="6">
              <p class="text-center"> {{ 'Exam  : ' . $exam->exam_name }} </p>
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td colspan="3">
              <p class="float-start mx-5"> Exam Date : {{ date('d/m/Y', strtotime($examdate)) }} </p>
            </x-table.td>
            <x-table.td colspan="3">
              <p class="float-end mx-5"> Time : {{ $time_slot }} </p>
            </x-table.td>
          </x-table.tr>
        </x-table.tbody>
      </x-table.table>
    </section>
    <section class="overflow-x-scroll">
      <x-table.table class="w-full">
        <x-table.thead>
          <x-table.tr>
            <x-table.th> Block ( size )</x-table.th>
            <x-table.th> Class </x-table.th>
            <x-table.th> ( SEM ) Subject </x-table.th>
            <x-table.th> Seatno </x-table.th>
            <x-table.th> Total </x-table.th>
            <x-table.th> New Seatno </x-table.th>
            <x-table.th> Merge </x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @foreach ($exam_time_tables as $exam_time_table)
            @foreach ($exam_time_table->exampatternclass->blockallocations->where('subject_id', $exam_time_table->subject_id)->where('exampatternclass_id', $exam_time_table->exam_patternclasses_id) as $block)
              <x-table.tr wire:key='{{ $block->id }}'>
                <x-table.td>{{ $block->classroom->class_name }} ( {{ $block->classroom->noofbenches }} ) </x-table.td>

                <x-table.td>
                  {{ $exam_time_table->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $exam_time_table->exampatternclass->patternclass->courseclass->course->course_name }} {{ $exam_time_table->exampatternclass->patternclass->pattern->pattern_name }}
                </x-table.td>
                <x-table.td> ( {{ $exam_time_table->subject->subject_sem }} ) {{ $exam_time_table->subject->subject_code }} {{ $exam_time_table->subject->subject_name }} </x-table.td>
                <x-table.td class="text-wrap">
                  {{ $block->studentblockallocations->pluck('seatno')->implode(', ') }}
                </x-table.td>
                <x-table.td> {{ $block->studentblockallocations->pluck('seatno')->count() }}</x-table.td>
                <x-table.td>
                  <x-textarea wire:model='seatnos.{{ $block->id }}' id="seatnos.{{ $block->id }}" name="seatnos.{{ $block->id }}" placeholder="Enter Seat Numbers, separated by commas ','" required></x-textarea>
                  @error("seatnos.{$block->id}")
                    <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
                  @enderror
                </x-table.td>
                <x-table.td>
                  <x-form-btn wire:loading.attr="disabled" wire:click="merge_seatnos_in_block('{{ $examdate }}',{{ $timeslot_id }},{{ $block->id }})">Merge</x-form-btn>
                </x-table.td>
              </x-table.tr>
            @endforeach
          @endforeach
        </x-table.tbody>
      </x-table.table>
    </section>
  </div>

</div>
