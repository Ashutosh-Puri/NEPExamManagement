<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Unfairmean's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Unfairmeans">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.unfairmean.unfairmean-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Unfairmeans">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $unfairmeans_id }})">
      @include('livewire.user.unfairmean.unfairmean-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Unfairmean's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>

      <x-table.frame p="0">
        <x-slot:header>
          <div class="flex gap-x-1">
            <span  class="text-center" >
              <x-status type='info' class="h-8 py-2.5 mt-1" i="0" wire:click="sendmail"> Send Mail </x-status>
            </span>
            <a class="text-center " href="{{ route('user.unfairmean_attendance') }}">
              <x-status type='info' class="h-8 py-2.5 mt-1" i="0"> Attendance </x-status>
            </a>
            <a class="text-center" href="{{ route('user.unfairmean_finalreport') }}">
              <x-status type='info' class="h-8 py-2.5 mt-1" i="0"> Report </x-status>
            </a>
            <a class="text-center" href="{{ route('user.performance_cancelreport') }}">
              <x-status type='info' class="h-8 py-2.5 mt-1" i="0"> Cancel Report </x-status>
            </a>
          </div>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                {{-- <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th> --}}
                <x-table.th wire:click="sort_column('exam_patternclasses_id')" name="exam_patternclasses_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Pattern Class</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student</x-table.th>
                <x-table.th wire:click="sort_column('exam_studentseatnos_id')" name="exam_studentseatnos_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Seat No</x-table.th>
                <x-table.th wire:click="sort_column('memid')" name="memid" :sort="$sortColumn" :sort_by="$sortColumnBy">Mem ID</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject</x-table.th>
                {{-- <x-table.th wire:click="sort_column('unfairmeansmaster_id')" name="unfairmeansmaster_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Location</x-table.th> --}}
                <x-table.th wire:click="sort_column('punishment')" name="punishment" :sort="$sortColumn" :sort_by="$sortColumnBy">Punishment</x-table.th>
                <x-table.th wire:click="sort_column('paid_status')" name="paid_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Paid Status</x-table.th>
                <x-table.th wire:click="sort_column('email')" name="email" :sort="$sortColumn" :sort_by="$sortColumnBy">Email</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($groupedUnfairmeans as $subjectId => $subjectExamPanels)
                @foreach ($subjectExamPanels as $index => $unfairmeanss)
                  <x-table.tr wire:key="{{ $index }}">

                    {{-- <x-table.td> {{ $unfairmeanss->id }}</x-table.td> --}}
                    {{-- First column --}}
                    @if ($index === 0)
                      <x-table.td rowspan="{{ count($subjectExamPanels) }}">
                        {{ isset($unfairmeanss->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $unfairmeanss->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($unfairmeanss->exampatternclass->patternclass->courseclass->course->course_name) ? $unfairmeanss->exampatternclass->patternclass->courseclass->course->course_name : '-' }}
                      </x-table.td>
                      <x-table.td rowspan="{{ count($subjectExamPanels) }}">
                        <x-table.text-scroll>{{ isset($unfairmeanss->student->student_name) ? $unfairmeanss->student->student_name : '' }}</x-table.text-scroll>
                      </x-table.td>
                      <x-table.td rowspan="{{ count($subjectExamPanels) }}">
                        <x-table.text-scroll>{{ isset($unfairmeanss->examstudentseatno->seatno) ? $unfairmeanss->examstudentseatno->seatno : '' }}</x-table.text-scroll>
                      </x-table.td>
                      <x-table.td rowspan="{{ count($subjectExamPanels) }}">
                        <x-table.text-scroll>{{ isset($unfairmeanss->student->memid) ? $unfairmeanss->student->memid : '' }}</x-table.text-scroll>
                      </x-table.td>
                    @endif

                    {{-- Second column --}}
                    <x-table.td>
                      <x-table.text-scroll>{{ optional($unfairmeanss->subject)->subject_name ?? 'No Faculty Assigned' }}</x-table.text-scroll>
                    </x-table.td>

                    <x-table.td>
                      <x-table.text-scroll>{{ isset($unfairmeanss->punishment) ? $unfairmeanss->punishment : '' }}</x-table.text-scroll>
                    </x-table.td>

                    <x-table.td>
                      @if ($unfairmeanss->deleted_at)
                      @elseif($unfairmeanss->paid_status == 1)
                        <x-table.active wire:click="Status({{ $unfairmeanss->id }})" />
                      @else
                        <x-table.inactive wire:click="Status({{ $unfairmeanss->id }})" />
                      @endif
                    </x-table.td>

                    <x-table.td>
                      @if ($unfairmeanss->deleted_at)
                      @elseif($unfairmeanss->email == 1)
                        <x-status type="success">Yes</x-table.status>
                        @else
                          <x-status type="danger"> No </x-table.status>
                      @endif
                    </x-table.td>

                    {{-- Fourth column --}}
                    <x-table.td>
                      <p class="py-1">
                        @if ($unfairmeanss->deleted_at)
                          <x-table.delete wire:click="deleteconfirmation({{ $unfairmeanss->id }})" />
                          <x-table.restore wire:click="restore({{ $unfairmeanss->id }})" />
                        @else
                          {{-- <x-table.view wire:click="view({{ $unfairmeanss->id }})" /> --}}
                          {{-- <x-table.edit wire:click="edit({{ $unfairmeanss->id }})" /> --}}
                          <x-table.archive wire:click="delete({{ $unfairmeanss->id }})" />
                        @endif
                      </p>
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              @empty
                <x-table.tr>
                  <x-table.td colspan='9' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>

          </x-table.table>
        </x-slot>
        {{-- <x-slot:footer>
          <x-table.paginate :data="$groupedUnfairmeans" />
        </x-slot> --}}
      </x-table.frame>
    </div>
  @endif
</div>
