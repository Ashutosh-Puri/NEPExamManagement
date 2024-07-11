<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Faculty">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.faculty.faculty-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Faculty">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $faculty_id }})">
      @include('livewire.faculty.faculty.faculty-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Faculty">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.faculty.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Facultie's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Facultie's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('faculty_name')" name="faculty_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Name</x-table.th>
                <x-table.th wire:click="sort_column('role_id')" name="role_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Designation</x-table.th>
                <x-table.th wire:click="sort_column('mobile_no')" name="mobile_no" :sort="$sortColumn" :sort_by="$sortColumnBy">Mobile No</x-table.th>
                <x-table.th>Status</x-table.th>
                <x-table.th>Action</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($faculties as $faculty)
                <x-table.tr wire:key="{{ $faculty->id }}">
                  <x-table.td>{{ $faculty->id }} </x-table.td>
                  <x-table.td>{{ isset($faculty->faculty_name) ? $faculty->faculty_name : '' }} </x-table.td>
                  <x-table.td> {{ isset($faculty->designation->designation_name) ? $faculty->designation->designation_name : '' }} </x-table.td>
                  <x-table.td> {{ $faculty->mobile_no }} </x-table.td>
                  <x-table.td>
                    @if (!$faculty->deleted_at)
                      @if ($faculty->active === 1)
                        <x-table.active wire:click="changestatus({{ $faculty->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $faculty->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($faculty->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $faculty->id }})" />
                      <x-table.restore wire:click="restore({{ $faculty->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $faculty->id }})" />
                      <x-table.edit wire:click="edit({{ $faculty->id }})" />
                      <x-table.archive wire:click="softdelete({{ $faculty->id }})" />
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
          <x-table.paginate :data="$faculties" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
