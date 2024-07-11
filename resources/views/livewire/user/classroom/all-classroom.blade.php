<div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Classroom's" />
    </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
      <div>
        <x-card-header heading="Add Classroom">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.classroom.classroom-form')
        </x-form>
      </div>
    @elseif($mode == 'edit')
      <div>
        <x-card-header heading="Edit Classroom">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="update({{ $edit_id }})">
            @include('livewire.user.classroom.classroom-form')
        </x-form>
      </div>
    @elseif($mode == 'all')
      <div>
       
        <x-card-header heading="All Classroom's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('building_id')" name="building_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Building</x-table.th>
                  <x-table.th wire:click="sort_column('class_name')" name="class_name" :sort="$sortColumn" :sort_by="$sortColumnBy">class_name</x-table.th>
                  <x-table.th wire:click="sort_column('noofbenches')" name="noofbenches" :sort="$sortColumn" :sort_by="$sortColumnBy">No of Benches</x-table.th>
                  <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($classrooms as $classroom)
                  <x-table.tr wire:key="{{ $classroom->id }}">
                    <x-table.td>{{ $classroom->id }} </x-table.td>
                    <x-table.td>{{ $classroom->building->building_name }} </x-table.td>
                    <x-table.td> {{ $classroom->class_name }} </x-table.td>
                    <x-table.td> {{ $classroom->noofbenches }} </x-table.td>
                    <x-table.td>
                      @if (!$classroom->deleted_at)
                        @if ($classroom->status === 1)
                          <x-table.active wire:click="updatestatus({{ $classroom->id }})" />
                        @else
                          <x-table.inactive wire:click="updatestatus({{ $classroom->id }})" />
                        @endif
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @if ($classroom->deleted_at)
                        <x-table.delete wire:click="deleteconfirmation({{ $classroom->id }})" />
                        <x-table.restore wire:click="restore({{ $classroom->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $classroom->id }})" />
                        <x-table.archive wire:click="delete({{ $classroom->id }})" />
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$classrooms" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
  </div>
  