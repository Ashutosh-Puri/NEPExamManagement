<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Time Table Slot's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Time Table Slot">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.time-table-slot.time-table-slot-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Time Table Slot">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.time-table-slot.time-table-slot-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
   
      <x-card-header heading="All Time Table Slot's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('timeslot')" name="timeslot" :sort="$sortColumn" :sort_by="$sortColumnBy">Time Slot</x-table.th>
                <x-table.th wire:click="sort_column('start_time')" name="start_time" :sort="$sortColumn" :sort_by="$sortColumnBy"> Start Time</x-table.th>
                <x-table.th wire:click="sort_column('end_time')" name="end_time" :sort="$sortColumn" :sort_by="$sortColumnBy"> End Time</x-table.th>
                <x-table.th wire:click="sort_column('slot')" name="slot" :sort="$sortColumn" :sort_by="$sortColumnBy">Slot</x-table.th>
                <x-table.th wire:click="sort_column('isactive')" name="isactive" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($time_table_slots as $time_table_slot)
                <x-table.tr wire:key="{{ $time_table_slot->id }}">
                  <x-table.td>{{ $time_table_slot->id }} </x-table.td>
                  <x-table.td>{{ $time_table_slot->timeslot }} </x-table.td>
                  <x-table.td>{{ isset($time_table_slot->start_time)?date('h:i:s A',strtotime($time_table_slot->start_time)):''; }} </x-table.td>
                  <x-table.td>{{ isset($time_table_slot->end_time)?date('h:i:s A',strtotime($time_table_slot->end_time)):''; }} </x-table.td>
                  <x-table.td>{{ $time_table_slot->slot }} </x-table.td>
                  <x-table.td>
                    @if (!$time_table_slot->deleted_at)
                      @if ($time_table_slot->isactive === 1)
                        <x-table.active wire:click="status({{ $time_table_slot->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $time_table_slot->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($time_table_slot->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $time_table_slot->id }})" />
                      <x-table.restore wire:click="restore({{ $time_table_slot->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $time_table_slot->id }})" />
                      <x-table.archive wire:click="delete({{ $time_table_slot->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$time_table_slots" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
