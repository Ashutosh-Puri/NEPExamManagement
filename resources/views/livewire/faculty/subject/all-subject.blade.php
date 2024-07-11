<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Subject">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.subject.subject-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Subject">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $subject_id }})">
      @include('livewire.faculty.subject.subject-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Subject">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.subject.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Subjects" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Subjects">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('subject_name')" name="subject_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
                <x-table.th wire:click="sort_column('department_id')" name="department_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Department Name</x-table.th>
                <x-table.th wire:click="sort_column('pattern_id')" name="pattern_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Name</x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="class_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Class Name</x-table.th>
                <x-table.th wire:click="sort_column('subject_code')" name="subject_code" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Code</x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjects as $subject)
                <x-table.tr wire:key="{{ $subject->id }}">
                  <x-table.td> {{ $subject->id }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subject->subject_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subject->department->dept_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subject->patternclass->pattern->pattern_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subject->patternclass->courseclass->classyear->classyear_name }} - {{ $subject->patternclass->courseclass->course->course_name ?? '' }} </x-table.text-scroll>
                    {{-- <x-table.text-scroll> {{ $subject->patternclass->courseclass->classyear->classyear_name }} -  </x-table.text-scroll> --}}
                  </x-table.td>
                  <x-table.td> {{ $subject->subject_code }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subject->college->college_name ?? '' }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if (!$subject->deleted_at)
                      @if ($subject->status === 1)
                        <x-table.active wire:click="changestatus({{ $subject->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $subject->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($subject->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $subject->id }})" />
                      <x-table.restore wire:click="restore({{ $subject->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $subject->id }})" />
                      <x-table.edit wire:click="edit({{ $subject->id }})" />
                      <x-table.archive wire:click="softdelete({{ $subject->id }})" />
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
          <x-table.paginate :data="$subjects" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
