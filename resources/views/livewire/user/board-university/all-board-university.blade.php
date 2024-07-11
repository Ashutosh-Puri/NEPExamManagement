<div>
  <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Board's / University's" />
      </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Board / University">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.board-university.board-university-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Board / University">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.board-university.board-university-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
    
      <x-card-header heading="All Board's / University's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('boarduniversity_name')" name="boarduniversity_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Board / University</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($board_universities as $board_university)
                <x-table.tr wire:key="{{ $board_university->id }}">
                  <x-table.td>{{ $board_university->id }} </x-table.td>
                  <x-table.td>{{ $board_university->boarduniversity_name }} </x-table.td>
                  <x-table.td>
                    @if (!$board_university->deleted_at)
                      @if ($board_university->is_active === 1)
                        <x-table.active wire:click="status({{ $board_university->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $board_university->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($board_university->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $board_university->id }})" />
                      <x-table.restore wire:click="restore({{ $board_university->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $board_university->id }})" />
                      <x-table.archive wire:click="delete({{ $board_university->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$board_universities" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
