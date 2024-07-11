<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Subject Bucket">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.subjectbucket.subjectbucket-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Subject Bucket">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $subjectbucket_id }})">
      @include('livewire.faculty.subjectbucket.subjectbucket-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Subject Bucket">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.subjectbucket.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Subject Bucket's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Subjects Bucket's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy"> ID </x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy"> Subject Name </x-table.th>
                <x-table.th wire:click="sort_column('department_id')" name="department_id" :sort="$sortColumn" :sort_by="$sortColumnBy"> Department Name </x-table.th>
                <x-table.th wire:click="sort_column('academicyear_id')" name="academicyear_id" :sort="$sortColumn" :sort_by="$sortColumnBy"> Academic Year </x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjectbuckets as $subjectbucket)
                <x-table.tr wire:key="{{ $subjectbucket->id }}">
                  <x-table.td> {{ $subjectbucket->id }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subjectbucket->subject->subject_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $subjectbucket->department->dept_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td> {{ $subjectbucket->academicyear->year_name }} </x-table.td>
                  <x-table.td>
                    @if (!$subjectbucket->deleted_at)
                      @if ($subjectbucket->status === 1)
                        <x-table.active wire:click="changestatus({{ $subjectbucket->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $subjectbucket->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($subjectbucket->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $subjectbucket->id }})" />
                      <x-table.restore wire:click="restore({{ $subjectbucket->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $subjectbucket->id }})" />
                      <x-table.edit wire:click="edit({{ $subjectbucket->id }})" />
                      <x-table.archive wire:click="softdelete({{ $subjectbucket->id }})" />
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
          <x-table.paginate :data="$subjectbuckets" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
