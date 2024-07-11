<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Headwise Tool Report's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="All Headwise Tool's" />
  <x-table.frame>
    <x-slot:header>
      <div class="flex gap-x-0.5">
        <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center h-10">
          <x-select-option class="text-start" hidden>Year</x-select-option>
          @foreach ($academicyears as $academicyearid => $academicyearname)
            <x-select-option wire:key="{{ $academicyearid }}" value="{{ $academicyearid }}" class="text-start">{{ $academicyearname }}</x-select-option>
          @endforeach
        </x-input-select>
        <x-table.cancel class="mx-2" wire:click='resetinput()' i="0"> Clear</x-table.cancel>
      </div>
    </x-slot:header>
    <x-slot:body>
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
            <x-table.th>Academic Year</x-table.th>
            <x-table.th>Faculty Name</x-table.th>
            <x-table.th>Patternclass</x-table.th>
            <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
            <x-table.th>Tool Name</x-table.th>
            <x-table.th>Total Required Document</x-table.th>
            <x-table.th>Total Uploaded Document</x-table.th>
            <x-table.th>Total Remaining Document</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($faculty_internal_documents as $assigned_int_tool)
            <x-table.tr wire:key="{{ $assigned_int_tool->id }}">
              <x-table.td>{{ $assigned_int_tool->id }} </x-table.td>
              <x-table.td>
                {{ isset($assigned_int_tool->academicyear->year_name) ? $assigned_int_tool->academicyear->year_name : '' }}
              </x-table.td>
              <x-table.td>
                <x-table.text-scroll>{{ $assigned_int_tool->faculty->faculty_name ?? '' }}</x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                <x-table.text-scroll>
                  {{ (isset($assigned_int_tool->subject->patternclass->pattern->pattern_name) ? $assigned_int_tool->subject->patternclass->pattern->pattern_name : '') . ' ' . (isset($assigned_int_tool->subject->patternclass->courseclass->classyear->classyear_name) ? $assigned_int_tool->subject->patternclass->courseclass->classyear->classyear_name : '') . ' ' . (isset($assigned_int_tool->subject->patternclass->courseclass->course->course_name) ? $assigned_int_tool->subject->patternclass->courseclass->course->course_name : '') }}
                </x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                <x-table.text-scroll>{{ isset($assigned_int_tool->subject->subject_name) ? $assigned_int_tool->subject->subject_name : '' }}</x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                <x-table.text-scroll>
                  {{ isset($assigned_int_tool->internaltoolmaster->toolname) ? $assigned_int_tool->internaltoolmaster->toolname : '' }}
                </x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                {{ $assigned_int_tool->facultysubjecttools->count() ?? '' }}
              </x-table.td>
              <x-table.td>
                {{ $assigned_int_tool->facultysubjecttools->whereNotNull('document_filePath')->count() ?? '' }}
              </x-table.td>
              <x-table.td>
                {{ $assigned_int_tool->facultysubjecttools->count() - $assigned_int_tool->facultysubjecttools->whereNotNull('document_filePath')->count() }}
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
      <x-table.paginate :data="$faculty_internal_documents" />
    </x-slot>
  </x-table.frame>
</div>
