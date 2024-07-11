<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Apply Ordinace" />
  </x-breadcrumb.breadcrumb>
  <div>
    <x-card-header heading="Apply Ordinace" />
    <x-table.frame x="0">
      <x-slot:body>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
              <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
              <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
              <x-table.th> Apply Ordinace </x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($exam_pattern_classes as $pattern_exam_class)
              <x-table.tr wire:key="{{ $pattern_exam_class->id }}">
                <x-table.td>{{ $pattern_exam_class->id }} </x-table.td>
                <x-table.td>{{ $pattern_exam_class->exam->exam_name }} </x-table.td>
                <x-table.td>{{ $pattern_exam_class->patternclass->courseclass->classyear->classyear_name }} {{ $pattern_exam_class->patternclass->courseclass->course->course_name }} {{ $pattern_exam_class->patternclass->pattern->pattern_name }} </x-table.td>
                <x-table.td>
                  @if ($pattern_exam_class->deleted_at)
                  @else
                    <x-table.create i="0" wire:click="generate_final_result({{ $pattern_exam_class->id }})"> O.1 , O.4 & O.163</x-table.create>
                    <x-table.create i="0" wire:click="apply_ordinace_two({{ $pattern_exam_class->id }})">Passing Certificate</x-table.create>
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
      </x-slot>
      <x-slot:footer>
        <x-table.paginate :data="$exam_pattern_classes" />
      </x-slot>
    </x-table.frame>
  </div>
</div>
