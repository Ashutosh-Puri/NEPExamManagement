<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name=" Ordinace 163's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add  Ordinace 163">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.ordinace.ordinace-163-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit  Ordinace 163">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.ordinace.ordinace-163-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Ordinace 163's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('activity_name')" name="activity_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Activity Name</x-table.th>
                <x-table.th wire:click="sort_column('ordinace_name')" name="ordinace_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Ordinace Name</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($ordinace163s as $ordinace163)
                <x-table.tr wire:key="{{ $ordinace163->id }}">
                  <x-table.td>{{ $ordinace163->id }} </x-table.td>
                  <x-table.td>{{ $ordinace163->activity_name }} </x-table.td>
                  <x-table.td>{{ $ordinace163->ordinace_name }} </x-table.td>
                  <x-table.td>
                    @if ($ordinace163->status == 1)
                      <x-table.active wire:click="changestatus({{ $ordinace163->id }})" />
                    @else
                      <x-table.inactive wire:click="changestatus({{ $ordinace163->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    <x-table.edit wire:click="edit({{ $ordinace163->id }})" />
                    <x-table.delete wire:click="deleteconfirmation({{ $ordinace163->id }})" />
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$ordinace163s" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
