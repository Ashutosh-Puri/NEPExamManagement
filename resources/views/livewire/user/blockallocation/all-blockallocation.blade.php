<div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Block Allocation's" />
    </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
      <div>
        <x-card-header heading="Add Block Allocation">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.blockallocation.blockallocation-form')
        </x-form>
      </div>
    @elseif($mode == 'edit')
      <div>
        <x-card-header heading="Edit Block Allocation">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="update({{ $edit_id }})">
            @include('livewire.user.blockallocation.blockallocation-form')
        </x-form>
      </div>
    @elseif($mode == 'all')
      <div>
       
        <x-card-header heading="All Block Allocation's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('block_id')" name="block_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Block </x-table.th>
                  <x-table.th wire:click="sort_column('classroom_id')" name="classroom_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Class </x-table.th>
                  <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject</x-table.th>
                  <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty</x-table.th>
                  <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($blockallocations as $blockallocation)
                  <x-table.tr wire:key="{{ $blockallocation->id }}">
                    <x-table.td>{{ $blockallocation->id }} </x-table.td>
                    <x-table.td>{{ $blockallocation->block->block_name }} </x-table.td>
                    <x-table.td>{{ $blockallocation->classroom->class_name }}</x-table.td>
                    <x-table.td>{{ $blockallocation->subject->subject_name }} </x-table.td>
                    <x-table.td>{{ isset($blockallocation->faculty->faculty_name)?$blockallocation->faculty->faculty_name:''; }} </x-table.td>
           
                    <x-table.td>
                      @if (!$blockallocation->deleted_at)
                        @if ($blockallocation->status === 1)
                          <x-table.active wire:click="updatestatus({{ $blockallocation->id }})" />
                        @else
                          <x-table.inactive wire:click="updatestatus({{ $blockallocation->id }})" />
                        @endif
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @if ($blockallocation->deleted_at)
                        <x-table.delete wire:click="deleteconfirmation({{ $blockallocation->id }})" />
                        <x-table.restore wire:click="restore({{ $blockallocation->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $blockallocation->id }})" />
                        <x-table.archive wire:click="delete({{ $blockallocation->id }})" />
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$blockallocations" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
  </div>
  