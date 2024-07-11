<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Rate Head's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add RateHead">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.ratehead.ratehead-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Rate Head">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $edit_id }})">
      @include('livewire.user.ratehead.ratehead-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="  All Rate Head's">
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
                <x-table.th wire:click="sort_column('headname')" name="headname" :sort="$sortColumn" :sort_by="$sortColumnBy">Head Name </x-table.th>
                <x-table.th wire:click="sort_column('type')" name="type" :sort="$sortColumn" :sort_by="$sortColumnBy">Type </x-table.th>
                <x-table.th wire:click="sort_column('noofcredit')" name="noofcredit" :sort="$sortColumn" :sort_by="$sortColumnBy">No of Credit </x-table.th>
                <x-table.th wire:click="sort_column('course_type')" name="course_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Course Type </x-table.th>
                <x-table.th wire:click="sort_column('amount')" name="amount" :sort="$sortColumn" :sort_by="$sortColumnBy">Amount </x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($rateheads as  $ratehead)
                <x-table.tr wire:key="{{ $ratehead->id }}">
                  <x-table.td> {{ $ratehead->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $ratehead->headname }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>{{ $ratehead->type }} </x-table.td>
                  <x-table.td>{{ $ratehead->noofcredit }} </x-table.td>
                  <x-table.td>{{ $ratehead->course_type }} </x-table.td>
                  <x-table.td>{{ $ratehead->amount }} </x-table.td>
                  <x-table.td>
                    @if ($ratehead->deleted_at)
                    @elseif($ratehead->status == 1)
                      <x-table.active wire:click="update_status({{ $ratehead->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $ratehead->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($ratehead->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $ratehead->id }})" />
                      <x-table.restore wire:click="restore({{ $ratehead->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $ratehead->id }})" />
                      <x-table.archive wire:click="delete({{ $ratehead->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='8' class='text-center'>No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$rateheads" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
