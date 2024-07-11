<div>
  <x-form wire:submit="allocate_class_room('{{ $examdate }}', {{ $timeslot_id }}, '{{ $time_slot }}')">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2">
      <section class="overflow-x-scroll">
        <div class="grid grid-cols-1 md:grid-cols-2  ">
          <p class="mx-2 my-1 "> {{ 'Exam Date : ' . date('d-M-Y', strtotime($examdate)) }}</p>
          <p class="mx-2 my-1 float-right"> {{ 'Time Slot : ' . $time_slot }}</p>
        </div>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th> ID </x-table.th>
              <x-table.th> Class </x-table.th>
              <x-table.th> Subject </x-table.th>
              <x-table.th> Total </x-table.th>
              <x-table.th> Blocks </x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @php
              $i = 1;
              $total = 0;
              $totalblock = 0;
            @endphp
            @foreach ($exam_time_tables as $exam_time_table)
              @php
                $seatno = collect();
                $studentexamforms = $exam_time_table->subject->studentexamforms->where('exam_id', $exam->id)->where('ext_status', 1);
                foreach ($studentexamforms as $studentexamform) {
                    if ($studentexamform->examformmaster->inwardstatus == 1) {
                        if (!is_null($studentexamform->student->examstudentseatnos->last())) {
                            $seatno->push($studentexamform->student->examstudentseatnos->last()->seatno);
                        } else {
                            $this->dispatch('alert', type: 'info', message: 'Please Re-generate Seatno first !!');
                        }
                    }
                }
              @endphp
              @if ($seatno->count() != 0)
                <x-table.tr>
                  <x-table.td> {{ $i++ }} </x-table.td>
                  <x-table.td class="text-wrap"> {{ $exam_time_table->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $exam_time_table->exampatternclass->patternclass->courseclass->course->course_name }} {{ $exam_time_table->exampatternclass->patternclass->pattern->pattern_name }}</x-table.td>
                  <x-table.td class="text-wrap"> {{ $exam_time_table->subject->subject_code }} {{ $exam_time_table->subject->subject_name }} </x-table.td>
                  <x-table.td>{{ $seatno->count() }} </x-table.td>
                  @php
                    $total = $total + $seatno->count();
                    if ($seatno->count() < $block->block_size) {
                        $totalblock = $totalblock + 1;
                    } else {
                        $totalblock = $totalblock + $seatno->count() / $block->block_size;
                    }
                  @endphp
                  @if ($seatno->count() < $block->block_size)
                    <x-table.td>1 </x-table.td>
                  @else
                    <x-table.td>{{ round($seatno->count() / $block->block_size, 0) }} </x-table.td>
                  @endif
                </x-table.tr>
              @endif
            @endforeach
            <x-table.tr>
              <x-table.td colspan="3">Total No of Students and Blocks </x-table.td>
              <x-table.td>{{ $total }} </x-table.td>
              <x-table.td>{{ round($totalblock, 0) }} </x-table.td>
            </x-table.tr>
          </x-table.tbody>
        </x-table.table>
      </section>
      <section class="overflow-x-scroll">
        @if (isset($exam))
          <x-table.table class="mt-8">
            <x-table.thead>
              <x-table.tr>
                <x-table.th> ID </x-table.th>
                <x-table.th> Building </x-table.th>
                <x-table.th> Total Classrooms/Block </x-table.th>
                <x-table.th> Building Capacity </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @php
                $i = 1;
                $count = 0;
                $str = '';
              @endphp
              @foreach ($exam->exambuildings as $class_room)
                <x-table.tr>
                  <x-table.td> {{ $i++ }}</x-table.td>
                  <x-table.td> {{ $class_room->building->building_name }} </x-table.td>
                  <x-table.td> {{ $class_room->building->classrooms->where('status', 1)->count() }} </x-table.td>
                  <x-table.td> {{ $class_room->building->classrooms->where('status', 1)->pluck('noofbenches')->sum() }} </x-table.td>
                  <x-table.td> 
                    <x-input-checkbox wire:loading.class='cursor-not-allowed'  wire:model='buildings.{{ $class_room->building_id }}' name='buildings.{{ $class_room->building_id }}' id='buildings.{{ $class_room->building_id }}'  class="h-6 w-6 mx-2 cursor-pointer" />
                  </x-table.td>
                </x-table.tr>
                @php
                    $this->buildings[$class_room->building_id]=true;
                @endphp
              @endforeach
            </x-table.tbody>
          </x-table.table>
        @endif
      </section>
    </div>
    <x-form-btn>Generate Block</x-form-btn>
  </x-form>
</div>
