<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Pattern Class's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Exam Pattern Class">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-pattern-class.exam-pattern-class-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Exam Pattern Class">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.exam-pattern-class.exam-pattern-class-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
     
      <x-card-header heading="All Exam Pattern Class's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
                <x-table.th wire:click="sort_column('capmaster_id')" name="capmaster_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Cap</x-table.th>
                <x-table.th wire:click="sort_column('result_date')" name="result_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Result Date</x-table.th>
                <x-table.th wire:click="sort_column('start_date')" name="start_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Start Date</x-table.th>
                <x-table.th wire:click="sort_column('end_date')" name="end_date" :sort="$sortColumn" :sort_by="$sortColumnBy">End Date</x-table.th>
                <x-table.th wire:click="sort_column('latefee_date')" name="latefee_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Late Fee Date</x-table.th>
                <x-table.th wire:click="sort_column('intmarksstart_date')" name="intmarksstart_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Internal Marks Start Date</x-table.th>
                <x-table.th wire:click="sort_column('intmarksend_date')" name="intmarksend_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Internal Marks End Date</x-table.th>
                <x-table.th wire:click="sort_column('finefee_date')" name="finefee_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Fine Fee Date</x-table.th>
                <x-table.th wire:click="sort_column('capscheduled_date')" name="capscheduled_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Cap Scheduled Date</x-table.th>
                <x-table.th wire:click="sort_column('papersettingstart_date')" name="papersettingstart_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Paper Setting Start Date</x-table.th>
                <x-table.th wire:click="sort_column('papersubmission_date')" name="papersubmission_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Paper Submission Date</x-table.th>
                <x-table.th wire:click="sort_column('placeofmeeting')" name="placeofmeeting" :sort="$sortColumn" :sort_by="$sortColumnBy">Place Of Meeting</x-table.th>
                <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy">Description</x-table.th>
                <x-table.th wire:click="sort_column('launch_status')" name="launch_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Launch Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($pattern_exam_classes as $pattern_exam_class)
                <x-table.tr wire:key="{{ $pattern_exam_class->id }}">
                  <x-table.td>{{ $pattern_exam_class->id }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->exam->exam_name }} </x-table.td>
                  <x-table.td> <x-table.text-scroll class="w-full">
                     {{ isset($pattern_exam_class->patternclass->courseclass->classyear->classyear_name) ? $pattern_exam_class->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($pattern_exam_class->patternclass->courseclass->course->course_name) ? $pattern_exam_class->patternclass->courseclass->course->course_name : '-' }} {{ isset($pattern_exam_class->patternclass->pattern->pattern_name) ? $pattern_exam_class->patternclass->pattern->pattern_name : '-' }}
                    </x-table.text-scroll></x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->capmaster->cap_name) ? $pattern_exam_class->capmaster->cap_name : '' }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->result_date }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->start_date) ? date('d-m-Y', strtotime($pattern_exam_class->start_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->end_date) ? date('d-m-Y', strtotime($pattern_exam_class->end_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->latefee_date) ? date('d-m-Y', strtotime($pattern_exam_class->latefee_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->intmarksstart_date) ? date('d-m-Y', strtotime($pattern_exam_class->intmarksstart_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->intmarksend_date) ? date('d-m-Y', strtotime($pattern_exam_class->intmarksend_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($pattern_exam_class->finefee_date) ? date('d-m-Y', strtotime($pattern_exam_class->finefee_date)) : '' }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->capscheduled_date }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->papersettingstart_date }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->papersubmission_date }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->placeofmeeting }} </x-table.td>
                  <x-table.td>{{ $pattern_exam_class->description }} </x-table.td>
                  <x-table.td>
                    @if (!$pattern_exam_class->deleted_at)
                      @if ($pattern_exam_class->launch_status)
                        <x-table.active wire:click="changestatus({{ $pattern_exam_class->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $pattern_exam_class->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($pattern_exam_class->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $pattern_exam_class->id }})" />
                      <x-table.restore wire:click="restore({{ $pattern_exam_class->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $pattern_exam_class->id }})" />
                      <x-table.archive wire:click="delete({{ $pattern_exam_class->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$pattern_exam_classes" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
