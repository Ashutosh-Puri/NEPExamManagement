<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Academic Year's" />
  </x-breadcrumb.breadcrumb>  

  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Academic Year">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.academic-year.academic-year-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Academic Year">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.academic-year.academic-year-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Academic Year's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('year_name')" name="query_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('active')" name="active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($academic_years as $academicyear)
                <x-table.tr wire:key="{{ $academicyear->id }}">
                  <x-table.td>{{ $academicyear->id }} </x-table.td>
                  <x-table.td>{{ $academicyear->year_name }} </x-table.td>
                  <x-table.td>
                    @if (!$academicyear->deleted_at)
                      @if ($academicyear->active === 1)
                        <x-table.active wire:click="status({{ $academicyear->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $academicyear->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($academicyear->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $academicyear->id }})" />
                      <x-table.restore wire:click="restore({{ $academicyear->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $academicyear->id }})" />
                      <x-table.archive wire:click="delete({{ $academicyear->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$academic_years" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
