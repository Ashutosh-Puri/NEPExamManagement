<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Instruction Type's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Instruction Type">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.instruction.instructiontype-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Instruction Type">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $instruction_id }})">
      @include('livewire.user.instruction.instructiontype-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Instruction Type's">
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
                <x-table.th wire:click="sort_column('instruction_type')" name="instruction_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Instruction Type</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($instructions as  $inst)
                <x-table.tr wire:key="{{ $inst->id }}">
                  <x-table.td> {{ $inst->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $inst->instruction_type }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($inst->deleted_at)
                    @elseif($inst->is_active == 1)
                      <x-table.active wire:click="update_status({{ $inst->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $inst->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($inst->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $inst->id }})" />
                      <x-table.restore wire:click="restore({{ $inst->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $inst->id }})" />
                      <x-table.archive wire:click="delete({{ $inst->id }})" />
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
          <x-table.paginate :data="$instructions" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
