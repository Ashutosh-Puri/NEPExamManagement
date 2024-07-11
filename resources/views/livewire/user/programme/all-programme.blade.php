<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Programme's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Programme">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.programme.programme-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Programme">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.programme.programme-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Programme's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('programme_name')" name="programme_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Programme</x-table.th>
                <x-table.th wire:click="sort_column('active')" name="active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($programmes as $programme)
                <x-table.tr wire:key="{{ $programme->id }}">
                  <x-table.td>{{ $programme->id }} </x-table.td>
                  <x-table.td>{{ $programme->programme_name }} </x-table.td>
                  <x-table.td>
                    @if (!$programme->deleted_at)
                      @if ($programme->active === 1)
                        <x-table.active wire:click="status({{ $programme->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $programme->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($programme->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $programme->id }})" />
                      <x-table.restore wire:click="restore({{ $programme->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $programme->id }})" />
                      <x-table.archive wire:click="delete({{ $programme->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$programmes" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
