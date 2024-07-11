<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="User's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add User">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.user.user-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit User">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $user_id }})">
      @include('livewire.user.user.user-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All User's">
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
                <x-table.th wire:click="sort_column('name')" name="name" :sort="$sortColumn" :sort_by="$sortColumnBy"> Name </x-table.th>
                <x-table.th wire:click="sort_column('email')" name="email" :sort="$sortColumn" :sort_by="$sortColumnBy"> Email </x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College </x-table.th>
                <x-table.th wire:click="sort_column('department_id')" name="department_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Department </x-table.th>
                <x-table.th wire:click="sort_column('role_id')" name="role_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Role </x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($users as $key => $user)
                <x-table.tr wire:key="{{ $user->id }}">
                  <x-table.td> {{ $key + 1 }}</x-table.td>
                  <x-table.td> {{ $user->name }} </x-table.td>
                  <x-table.td> {{ $user->email }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($user->college->college_name) ? $user->college->college_name : '-' }} </x-table.text-scroll> </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($user->department->dept_name) ? $user->department->dept_name : '-' }} </x-table.text-scroll></x-table.td>
                  <x-table.td> {{ isset($user->role->role_name) ? $user->role->role_name : '-' }} </x-table.td>
                  <x-table.td>
                    @if ($user->deleted_at)
                    @elseif($user->is_active == 1)
                      <x-table.active wire:click="update_status({{ $user->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $user->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($user->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $user->id }})" />
                      <x-table.restore wire:click="restore({{ $user->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $user->id }})" />
                      <x-table.archive wire:click="delete({{ $user->id }})" />
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
          <x-table.paginate :data="$users" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
