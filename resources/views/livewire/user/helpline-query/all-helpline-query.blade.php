<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Helpline Query's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Helpline Query">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.helpline-query.helpline-query-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Helpline Query">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.helpline-query.helpline-query-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      
      <x-card-header heading="All Helpline Query's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('query_name')" name="query_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Query</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($student_helpline_queries as $helpline_query)
                <x-table.tr wire:key="{{ $helpline_query->id }}">
                  <x-table.td>{{ $helpline_query->id }} </x-table.td>
                  <x-table.td>{{ $helpline_query->query_name }} </x-table.td>
                  <x-table.td>
                    @if (!$helpline_query->deleted_at)
                      @if ($helpline_query->is_active === 1)
                        <x-table.active wire:click="status({{ $helpline_query->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $helpline_query->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($helpline_query->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $helpline_query->id }})" />
                      <x-table.restore wire:click="restore({{ $helpline_query->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $helpline_query->id }})" />
                      <x-table.archive wire:click="delete({{ $helpline_query->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$student_helpline_queries" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
