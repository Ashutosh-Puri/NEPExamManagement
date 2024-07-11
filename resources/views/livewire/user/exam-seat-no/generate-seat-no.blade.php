<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Seat No's" />
  </x-breadcrumb.breadcrumb>

  <div>
    <x-card-header heading="Exam Seat No's">
      <x-spinner />
    </x-card-header>
    <x-table.frame x="0">
      <x-slot:header>
        <div class="flex gap-x-1">
          <x-table.create  wire:click="generate_all_class_seat_numbers()">All</x-table.create>
          <x-table.regenerate  wire:click="regenerate_all_class_seat_numbers()">All</x-table.regenerate>
          <x-table.delete  wire:click="delete_all_class_seat_numbers()">All</x-table.edit>
        </div>
      </x-slot>
      <x-slot:body>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
              <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Name </x-table.th>
              <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class Name </x-table.th>
              <x-table.th> Action </x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @forelse ($exampatternclasses as $exampatternclass)
              <x-table.tr wire:key="{{ $exampatternclass->id }}">
                <x-table.td> {{ $exampatternclass->id }}</x-table.td>
                <x-table.td>
                  <x-table.text-scroll> {{ isset($exampatternclass->exam->exam_name) ? $exampatternclass->exam->exam_name : '' }} </x-table.text-scroll>
                </x-table.td>
                <x-table.td class="text-wrap">
                  {{ isset($exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($exampatternclass->patternclass->courseclass->course->course_name) ? $exampatternclass->patternclass->courseclass->course->course_name : '-' }} {{ isset($exampatternclass->patternclass->pattern->pattern_name) ? $exampatternclass->patternclass->pattern->pattern_name : '-' }}
                </x-table.td>
                <x-table.td>
                  @if (isset($exampatternclass->examstudentseatnos->first()->printstatus))
                    <x-table.regenerate wire:click="regenerate_class_seat_numbers({{ $exampatternclass->id }})" />
                      <x-table.delete wire:click="delete_class_seat_numbers({{ $exampatternclass->id }})" />
                      <a href="{{ route('user.seat_nos', $exampatternclass->id) }}"><x-table.download /></a>
                  @else
                    <x-table.create wire:click="generate_class_seat_numbers({{ $exampatternclass->id }})" />
                  @endif
                </x-table.td>
              </x-table.tr>
            @empty
              <x-table.tr>
                <x-table.td colspan='8' class="text-center">No Data Found</x-table.td>
              </x-table.tr>
            @endforelse
          </x-table.tbody>
        </x-table.table>
      </x-slot>
      <x-slot:footer>
        <x-table.paginate :data="$exampatternclasses" />
      </x-slot>
    </x-table.frame>
  </div>

</div>
