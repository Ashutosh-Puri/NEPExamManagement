<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Paper Set's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Paper Set">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.paper-set.paper-set-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Paper Set">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $paper_id }})">
      @include('livewire.user.paper-set.paper-set-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Paper Set's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('set_name')" name="set_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Set Name</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($papersets as $paperset)
                <x-table.tr wire:key="{{ $paperset->id }}">
                  <x-table.td>{{ $paperset->id }} </x-table.td>
                  <x-table.td>{{ $paperset->set_name }} </x-table.td>
                  <x-table.td>
                    @if ($paperset->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $paperset->id }})" />
                      <x-table.restore wire:click="restore({{ $paperset->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $paperset->id }})" />
                      <x-table.archive wire:click="delete({{ $paperset->id }})" />
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
          <x-table.paginate :data="$papersets" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
