<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Blocks's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Block">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.block.block-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Block">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $block_id }})">
      @include('livewire.user.block.block-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Block's">
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
                <x-table.th wire:click="sort_column('building_id')" name="building_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Building Name </x-table.th>
                <x-table.th wire:click="sort_column('classname')" name="classname" :sort="$sortColumn" :sort_by="$sortColumnBy">Class Name </x-table.th>
                <x-table.th wire:click="sort_column('block')" name="block" :sort="$sortColumn" :sort_by="$sortColumnBy">Block</x-table.th>
                <x-table.th wire:click="sort_column('capacity')" name="capacity" :sort="$sortColumn" :sort_by="$sortColumnBy">Capacity </x-table.th>
                <x-table.th wire:click="sort_column('noofblocks')" name="noofblocks" :sort="$sortColumn" :sort_by="$sortColumnBy">No of Blocks </x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($blocks as $blok)
                <x-table.tr wire:key="{{ $blok->id }}">
                  <x-table.td> {{ $blok->id }}</x-table.td>
                  <x-table.td>
                    {{ $blok->building?->building_name }}
                  </x-table.td>
                  <x-table.td>
                    {{ $blok->classname }}
                  </x-table.td>
                  <x-table.td> {{ $blok->block }} </x-table.td>
                  <x-table.td>
                    {{ $blok->capacity }}
                  </x-table.td>
                  <x-table.td>
                    {{ $blok->noofblocks }}
                  </x-table.td>

                  <x-table.td>
                    @if ($blok->deleted_at)
                    @elseif($blok->status == 1)
                      <x-table.active wire:click="update_status({{ $blok->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $blok->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($blok->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $blok->id }})" />
                      <x-table.restore wire:click="restore({{ $blok->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $blok->id }})" />
                      <x-table.archive wire:click="delete({{ $blok->id }})" />
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
          <x-table.paginate :data="$blocks" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
