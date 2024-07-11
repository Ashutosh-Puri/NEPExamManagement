<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Instruction's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Instruction">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.instruction.instruction-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Instruction">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $instruction_id }})">
      @include('livewire.user.instruction.instruction-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Instruction's">
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
                <x-table.th wire:click="sort_column('instructiontype_id')" name="instructiontype_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Instruction Type</x-table.th>
                <x-table.th wire:click="sort_column('instruction_name')" name="instruction_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Instructions</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($instructions as  $instruction)
                <x-table.tr wire:key="{{ $instruction->id }}">
                  <x-table.td> {{ $instruction->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ isset($instruction->instructiontype->instruction_type)?$instruction->instructiontype->instruction_type:''; }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $instruction->instruction_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($instruction->deleted_at)
                    @elseif($instruction->is_active == 1)
                      <x-table.active wire:click="status({{ $instruction->id }})" />
                    @else
                      <x-table.inactive wire:click="status({{ $instruction->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($instruction->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $instruction->id }})" />
                      <x-table.restore wire:click="restore({{ $instruction->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $instruction->id }})" />
                      <x-table.archive wire:click="delete({{ $instruction->id }})" />
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
