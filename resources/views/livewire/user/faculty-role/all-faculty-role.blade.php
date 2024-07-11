<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Faculty Role's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'edit')
    <div>
      @include('livewire.user.faculty-role.faculty-role-form')
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="Faculty Role's" />
      <x-table.frame x='0' sw='40'>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('faculty_name')" name="faculty_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name</x-table.th>
                <x-table.th> Roles </x-table.th>
                <x-table.th wire:click="sort_column('active')" name="active" :sort="$sortColumn" :sort_by="$sortColumnBy">Is Active </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($faculties as $faculty)
                <x-table.tr wire:key="{{ $faculty->id }}">
                  <x-table.td>{{ $faculty->id }} </x-table.td>
                  <x-table.td>{{ $faculty->faculty_name }} </x-table.td>
                  <x-table.td class="whitespace-normal">
                    <div class="flex flex-wrap gap-1">
                      @foreach ($faculty->roles as $role)
                        @if ($role->pivot->status)
                          <x-status type="success" class="rounded-xl w-fit">{{ $role->role_name }}</x-status>
                        @else
                          <x-status type="danger" class="rounded-xl w-fit">{{ $role->role_name }}</x-status>
                        @endif
                      @endforeach
                    </div>
                  </x-table.td>
                  <x-table.td>
                    @if ($faculty->active === 1)
                      <x-table.active wire:click="status({{ $faculty->id }})" />
                    @else
                      <x-table.inactive wire:click="status({{ $faculty->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    <x-table.edit wire:click="edit({{ $faculty->id }})" />
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='5' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$faculties" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
