<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Class Year's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Class Year">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.class-year.class-year-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Class Year">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.class-year.class-year-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
  
      <x-card-header heading="All Class Year's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('classyear_name')" name="classyear_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Class Year Name</x-table.th>
                <x-table.th wire:click="sort_column('class_degree_name')" name="class_degree_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Class Degree Name</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($class_years as $class_year)
                <x-table.tr wire:key="{{ $class_year->id }}">
                  <x-table.td>{{ $class_year->id }} </x-table.td>
                  <x-table.td>{{ $class_year->classyear_name }} </x-table.td>
                  <x-table.td>{{ $class_year->class_degree_name }} </x-table.td>
                  <x-table.td>
                    @if (!$class_year->deleted_at)
                      @if ($class_year->status == 1)
                        <x-table.active wire:click="changestatus({{ $class_year->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $class_year->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($class_year->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $class_year->id }})" />
                      <x-table.restore wire:click="restore({{ $class_year->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $class_year->id }})" />
                      <x-table.archive wire:click="delete({{ $class_year->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$class_years" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
