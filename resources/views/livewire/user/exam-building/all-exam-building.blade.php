<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Building's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Building">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-building.exam-building-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Exam Building">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $edit_id }})">
      @include('livewire.user.exam-building.exam-building-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Exam Building's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:header>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Name </x-table.th>
                <x-table.th wire:click="sort_column('building_id')" name="building_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Building Name </x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($exambuildings as $exambuilding)
                <x-table.tr wire:key="{{ $exambuilding->id }}">
                  <x-table.td> {{ $exambuilding->id }}</x-table.td>
                  <x-table.td>
                    {{ isset($exambuilding->exam->exam_name) ? $exambuilding->exam->exam_name : '-' }}
                  </x-table.td>
                  <x-table.td>
                    {{ isset($exambuilding->building->building_name) ? $exambuilding->building->building_name : '-' }}
                  </x-table.td>
                  <x-table.td>
                    @if ($exambuilding->deleted_at)
                    @elseif($exambuilding->status == 1)
                      <x-table.active wire:click="update_status({{ $exambuilding->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $exambuilding->id }})" />
                    @endif

                  </x-table.td>
                  <x-table.td>
                    @if ($exambuilding->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $exambuilding->id }})" />
                      <x-table.restore wire:click="restore({{ $exambuilding->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $exambuilding->id }})" />
                      <x-table.archive wire:click="delete({{ $exambuilding->id }})" />
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
          <x-table.paginate :data="$exambuildings" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
