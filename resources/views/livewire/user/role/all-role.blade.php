<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Role's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Role">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.role.role-form')
      </x-form>
    </div>
  @elseif($mode == 'view')
    <x-card-header heading="View Role">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.role.view-form')
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Role">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $role_id }})">
      @include('livewire.user.role.role-form')
    </x-form>
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="All Role's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('role_name')" name="role_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Role</x-table.th>
                <x-table.th wire:click="sort_column('roletype_id')" name="roletype_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Role Type</x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($roles as $role)
                <x-table.tr wire:key="{{ $role->id }}">
                  <x-table.td>{{ $role->id }} </x-table.td>
                  <x-table.td>{{ $role->role_name }} </x-table.td>
                  <x-table.td>{{ $role->roletype->roletype_name ?? '' }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $role->college->college_name ?? '' }}</x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($role->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $role->id }})" />
                      <x-table.restore wire:click="restore({{ $role->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $role->id }})" />
                      <x-table.edit wire:click="edit({{ $role->id }})" />
                      <x-table.archive wire:click="softdelete({{ $role->id }})" />
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
          <x-table.paginate :data="$roles" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
