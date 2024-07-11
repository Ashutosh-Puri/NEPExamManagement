<div class="">
  <x-table.frame a="0" class="p-0 m-0">
    <x-slot:body>
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>Course</x-table.th>
            <x-table.th>Start Date</x-table.th>
            <x-table.th>Without Late Fee End Date</x-table.th>
            <x-table.th>With Late Fee End Date</x-table.th>
            <x-table.th>Fine Fee End Date</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @foreach ($active_exam_date as $active_exam)
            <x-table.tr wire:key="{{ $active_exam->id }}">
              <x-table.td class="px-1"> <x-table.text-scroll class="w-60">{{ isset($active_exam->patternclass->pattern->pattern_name) ? $active_exam->patternclass->pattern->pattern_name:'' }} {{ isset($active_exam->patternclass->courseclass->classyear->classyear_name) ? $active_exam->patternclass->courseclass->classyear->classyear_name : '' }} {{ isset($active_exam->patternclass->courseclass->course->course_name) ? $active_exam->patternclass->courseclass->course->course_name : '' }}</x-table.text-scroll> </x-table.td>
              <x-table.td class="px-0.5">{{ isset($active_exam->start_date) ? date('d-m-Y', strtotime($active_exam->start_date)) : '' }} </x-table.td>
              <x-table.td class="px-0.5">{{ isset($active_exam->end_date) ? date('d-m-Y', strtotime($active_exam->end_date)) : '' }} </x-table.td>
              <x-table.td class="px-0.5">{{ isset($active_exam->latefee_date) ? date('d-m-Y', strtotime($active_exam->latefee_date)) : '' }} </x-table.td>
              <x-table.td class="px-0.5">{{ isset($active_exam->finefee_date) ? date('d-m-Y', strtotime($active_exam->finefee_date)) : '' }} </x-table.td>
            </x-table.tr>
          @endforeach
        </x-table.tbody>
      </x-table.table>
    </x-slot>
    <x-slot:footer>
      <x-table.paginate :data="$active_exam_date" />
    </x-slot>
  </x-table.frame>
</div>
