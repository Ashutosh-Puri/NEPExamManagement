<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Assign Tool">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.internal-audit.assign-tool.assigntool-form')
      </x-form>
    </div>
  @elseif($mode == 'view')
    <x-card-header heading="View Assigned Tool">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.internal-audit.assign-tool.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Assign Tool's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Assigned Tool's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th>Academic Year</x-table.th>
                <x-table.th>Patternclass</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
                <x-table.th>Tool Name</x-table.th>
                <x-table.th>Document Name</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($assigned_int_tools as $assigned_int_tool)
                <x-table.tr wire:key="{{ $assigned_int_tool->id }}">
                  <x-table.td>{{ $assigned_int_tool->id }} </x-table.td>
                  <x-table.td>
                    {{ isset($assigned_int_tool->academicyear->year_name) ? $assigned_int_tool->academicyear->year_name : '' }}
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
                    @foreach ($assigned_int_tool->facultysubjecttools as $faculty_subject_tool_doc)
                      <p>
                        {{ isset($faculty_subject_tool_doc->internaltooldocument->internaltooldocumentmaster->doc_name) ? $faculty_subject_tool_doc->internaltooldocument->internaltooldocumentmaster->doc_name : '' }}
                      </p>
                    @endforeach
                  </x-table.td>
                  <x-table.td>
                    @if (!$assigned_int_tool->deleted_at)
                      @if ($assigned_int_tool->status === 1)
                        <x-table.active wire:click="changestatus({{ $assigned_int_tool->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $assigned_int_tool->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    <x-table.view wire:click="view({{ $assigned_int_tool->id }})" />
                    <x-table.delete wire:click="deleteconfirmation({{ $assigned_int_tool->id }})" />
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
          <x-table.paginate :data="$assigned_int_tools" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
