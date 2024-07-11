<div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Block Master's" />
    </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
      <div>
        <x-card-header heading="Add Block">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.blockmaster.blockmaster-form')
        </x-form>
      </div>
    @elseif($mode == 'edit')
      <div>
        <x-card-header heading="Edit Block">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="update({{ $edit_id }})">
            @include('livewire.user.blockmaster.blockmaster-form')
        </x-form>
      </div>
    @elseif($mode == 'all')
      <div>
       
        <x-card-header heading="All Block Master's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('block_name')" name="block_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Block Name</x-table.th>
                  <x-table.th wire:click="sort_column('block_size')" name="block_size" :sort="$sortColumn" :sort_by="$sortColumnBy">Block Size</x-table.th>
                  <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($blocks as $block)
                  <x-table.tr wire:key="{{ $block->id }}">
                    <x-table.td>{{ $block->id }} </x-table.td>
                    <x-table.td>{{ $block->block_name }} </x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ $block->block_size }} </x-table.text-scroll></x-table.td>
                    <x-table.td>
                      @if (!$block->deleted_at)
                        @if ($block->status === 1)
                          <x-table.active wire:click="updatestatus({{ $block->id }})" />
                        @else
                          <x-table.inactive wire:click="updatestatus({{ $block->id }})" />
                        @endif
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @if ($block->deleted_at)
                        <x-table.delete wire:click="deleteconfirmation({{ $block->id }})" />
                        <x-table.restore wire:click="restore({{ $block->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $block->id }})" />
                        <x-table.archive wire:click="delete({{ $block->id }})" />
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$blocks" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
  </div>
  