<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Fee Course's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Exam Fee Course">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-fee-course.exam-fee-course-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Exam Fee Course">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.exam-fee-course.exam-fee-course-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
    
      <x-card-header heading="All Exam Fee Course's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('fee')" name="fee" :sort="$sortColumn" :sort_by="$sortColumnBy">Fee</x-table.th>
                <x-table.th wire:click="sort_column('sem')" name="sem" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem</x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
                <x-table.th wire:click="sort_column('examfees_id')" name="examfees_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Fee Type</x-table.th>
                <x-table.th wire:click="sort_column('approve_status')" name="approve_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Approved</x-table.th>
                <x-table.th wire:click="sort_column('active_status')" name="active_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($examfeecourses as $exam_fee_course)
                <x-table.tr wire:key="{{ $exam_fee_course->id }}">
                  <x-table.td>{{ $exam_fee_course->id }} </x-table.td>
                  <x-table.td>{{ $exam_fee_course->fee }} </x-table.td>
                  <x-table.td>{{ $exam_fee_course->sem }} </x-table.td>
                  <x-table.td> <x-table.text-scroll>{{ isset($exam_fee_course->patternclass->pattern->pattern_name) ? $exam_fee_course->patternclass->pattern->pattern_name : '-' }} {{ isset($exam_fee_course->patternclass->courseclass->classyear->classyear_name) ? $exam_fee_course->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($exam_fee_course->patternclass->courseclass->course->course_name) ? $exam_fee_course->patternclass->courseclass->course->course_name : '-' }} </x-table.text-scroll></x-table.td>
                  <x-table.td> <x-table.text-scroll>{{ isset($exam_fee_course->examfee->fee_name) ? $exam_fee_course->examfee->fee_name : '-' }} </x-table.text-scroll></x-table.td>
                  <x-table.td>
                    @if ($exam_fee_course->approve_status == 1)
                      <x-status type="success"> Yes </x-status>
                    @else
                      <x-status type="danger"> No </x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (!$exam_fee_course->deleted_at)
                      @if ($exam_fee_course->active_status)
                        <x-table.active wire:click="status({{ $exam_fee_course->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $exam_fee_course->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($exam_fee_course->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $exam_fee_course->id }})" />
                      <x-table.restore wire:click="restore({{ $exam_fee_course->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $exam_fee_course->id }})" />
                      @if ($exam_fee_course->approve_status == 1)
                        <x-table.reject wire:click="approve({{ $exam_fee_course->id }})" />
                      @else
                        <x-table.approve wire:click="approve({{ $exam_fee_course->id }})" />
                      @endif
                      <x-table.archive wire:click="delete({{ $exam_fee_course->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$examfeecourses" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
