<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Body's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Body">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exambody.exambody-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Exam Body">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $exambody_id }})">
      @include('livewire.user.exambody.exambody-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Exam Body's">
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
                <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty</x-table.th>
                <x-table.th wire:click="sort_column('role_id')" name="role_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Role</x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($exambody as  $body)
                <x-table.tr wire:key="{{ $body->id }}">
                  <x-table.td> {{ $body->id }}</x-table.td>
                  <x-table.td>
                    {{ $body->faculty->faculty_name }}
                  </x-table.td>
                  <x-table.td>
                    {{ $body->role->role_name }}
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $body->college->college_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($body->deleted_at)
                    @elseif($body->is_active == 1)
                      <x-table.active wire:click="status({{ $body->id }})" />
                    @else
                      <x-table.inactive wire:click="status({{ $body->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($body->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $body->id }})" />
                      <x-table.restore wire:click="restore({{ $body->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $body->id }})" />
                      <x-table.archive wire:click="delete({{ $body->id }})" />
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
          <x-table.paginate :data="$exambody" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
