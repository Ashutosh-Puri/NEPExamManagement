<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Faculty Head">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.faculty-head.facultyhead-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Faculty Head">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $facultyhead_id }})">
      @include('livewire.faculty.faculty-head.facultyhead-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Faculty Head">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.faculty-head.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Faculty Head's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Faculty Head's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name</x-table.th>
                <x-table.th wire:click="sort_column('department_id')" name="department_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Department Name</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($facultyheads as $facultyhead)
                <x-table.tr wire:key="{{ $facultyhead->id }}">
                  <x-table.td>{{ $facultyhead->id }} </x-table.td>
                  <x-table.td>{{ $facultyhead->faculty->faculty_name }} </x-table.td>
                  <x-table.td>{{ $facultyhead->department->dept_name }} </x-table.td>
                  <x-table.td>
                    @if (!$facultyhead->deleted_at)
                      @if ($facultyhead->status === 1)
                        <x-table.active wire:click="changestatus({{ $facultyhead->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $facultyhead->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($facultyhead->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $facultyhead->id }})" />
                      <x-table.restore wire:click="restore({{ $facultyhead->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $facultyhead->id }})" />
                      <x-table.edit wire:click="edit({{ $facultyhead->id }})" />
                      <x-table.archive wire:click="softdelete({{ $facultyhead->id }})" />
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
          <x-table.paginate :data="$facultyheads" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
