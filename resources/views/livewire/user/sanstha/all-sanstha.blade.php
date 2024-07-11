<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Sanstha's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Sanstha">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.sanstha.sanstha-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Sanstha">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $sanstha_id }})">
      @include('livewire.user.sanstha.sanstha-form')
    </x-form>
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="  All Sanstha's">
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
                <x-table.th wire:click="sort_column('sanstha_name')" name="sanstha_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha Name </x-table.th>
                <x-table.th wire:click="sort_column('sanstha_chairman_name')" name="sanstha_chairman_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha Chairman Name </x-table.th>
                <x-table.th wire:click="sort_column('sanstha_address')" name="sanstha_address" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha Address</x-table.th>
                <x-table.th wire:click="sort_column('sanstha_contact_no')" name="sanstha_contact_no" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha Contact No.</x-table.th>
                <x-table.th wire:click="sort_column('sanstha_website_url')" name="sanstha_website_url" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha website url.</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($sansthas as  $sanstha)
                <x-table.tr wire:key="{{ $sanstha->id }}">
                  <x-table.td> {{ $sanstha->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $sanstha->sanstha_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>{{ $sanstha->sanstha_chairman_name }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $sanstha->sanstha_address }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td> {{ $sanstha->sanstha_contact_no }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $sanstha->sanstha_website_url }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($sanstha->deleted_at)
                    @elseif($sanstha->status == 1)
                      <x-table.active wire:click="Status({{ $sanstha->id }})" />
                    @else
                      <x-table.inactive wire:click="Status({{ $sanstha->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($sanstha->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $sanstha->id }})" />
                      <x-table.restore wire:click="restore({{ $sanstha->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $sanstha->id }})" />
                      <x-table.archive wire:click="delete({{ $sanstha->id }})" />
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
          <x-table.paginate :data="$sansthas" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
