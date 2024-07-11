<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Vertical Wise Exam Time Table" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Create Vertical Wise Exam Time Table">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-time-table.vertical-exam-time-table-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Vertical Wise Exam Time Table">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $exam_time_table_id }})">
        @include('livewire.user.exam-time-table.vertical-exam-time-table-edit-form')
      </x-form>
    </div>
  @elseif($mode == 'bulkedit')
    <div>
      <x-card-header heading="Edit Vertical Wise Exam Time Table">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="bulk_update()">
        @include('livewire.user.exam-time-table.vertical-exam-time-table-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="Vertical Wise Exam Time Table's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame x="0">
        <x-slot:header>
          <div class="flex gap-1">
            <x-table.edit class="h-8 mt-1 p-2" wire:click="setmode('bulkedit')"> All </x-table.edit>
          </div>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                <x-table.th wire:click="sort_column('exam_patternclasses_id')" name="exam_patternclasses_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class </x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject </x-table.th>
                <x-table.th wire:click="sort_column('examdate')" name="examdate" :sort="$sortColumn" :sort_by="$sortColumnBy">Date </x-table.th>
                <x-table.th wire:click="sort_column('timeslot_id')" name="timeslot_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Time</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($examtimetables as $examtimetable)
                <x-table.tr wire:key="{{ $examtimetable->subject_id }}">
                  <x-table.td> {{ $examtimetable->subject_id }}</x-table.td>
                  <x-table.td class="text-wrap">{{ isset($examtimetable->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $examtimetable->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($examtimetable->exampatternclass->patternclass->courseclass->course->course_name) ? $examtimetable->exampatternclass->patternclass->courseclass->course->course_name : '-' }} {{ isset($examtimetable->exampatternclass->patternclass->pattern->pattern_name) ? $examtimetable->exampatternclass->patternclass->pattern->pattern_name : '-' }} </x-table.td>
                  <x-table.td class="text-wrap">
                    {{ isset($examtimetable->subject->subject_code) ? $examtimetable->subject->subject_code : '' }}  {{ isset($examtimetable->subject->subject_name) ? $examtimetable->subject->subject_name : '-' }}
                  </x-table.td>
                  <x-table.td> {{ isset($examtimetable->examdate) ? $examtimetable->examdate : '-' }}</x-table.td>

                  <x-table.td> {{ isset($examtimetable->timetableslot->timeslot) ? $examtimetable->timetableslot->timeslot : '-' }}</x-table.td>
                  <x-table.td>
                    @if ($examtimetable->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $examtimetable->id }})" />
                      <x-table.restore wire:click="restore({{ $examtimetable->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $examtimetable->id }})" />
                      <x-table.archive wire:click="delete({{ $examtimetable->id }})" />
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
          <x-table.paginate :data="$examtimetables" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
