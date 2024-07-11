<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Credit's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Credit">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.credit.credit-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Credit">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $credit_id }})">
      @include('livewire.user.credit.credit-form')
    </x-form>
  @elseif($mode == 'all')
    <div>

      <x-card-header heading=" All Credit's">
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
                <x-table.th wire:click="sort_column('credit')" name="credit" :sort="$sortColumn" :sort_by="$sortColumnBy">Credit </x-table.th>
                <x-table.th wire:click="sort_column('marks')" name="marks" :sort="$sortColumn" :sort_by="$sortColumnBy">Marks </x-table.th>
                <x-table.th wire:click="sort_column('passing')" name="passing" :sort="$sortColumn" :sort_by="$sortColumnBy">Passing</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($SubCredits as $credits)
                <x-table.tr wire:key="{{ $credits->id }}">
                  <x-table.td> {{ $credits->id }}</x-table.td>
                  <x-table.td>
                    {{ $credits->credit }}
                  </x-table.td>
                  <x-table.td> {{ $credits->marks }} </x-table.td>
                  <x-table.td>
                    {{ $credits->passing }}
                  </x-table.td>
                  <x-table.td>
                    @if ($credits->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $credits->id }})" />
                      <x-table.restore wire:click="restore({{ $credits->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $credits->id }})" />
                      <x-table.archive wire:click="delete({{ $credits->id }})" />
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
          <x-table.paginate :data="$SubCredits" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
