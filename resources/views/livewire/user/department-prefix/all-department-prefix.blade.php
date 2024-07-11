<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Department Prefixes" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Department Prefix">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.department-prefix.department-prefix-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Department Prefix">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $deptprefix_id }})">
      @include('livewire.user.department-prefix.department-prefix-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Department Prefix">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.department-prefix.view-form')
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Department Prefixes">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('dept_id')" name="dept_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Department Name</x-table.th>
                <x-table.th wire:click="sort_column('pattern_id')" name="pattern_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Name</x-table.th>
                <x-table.th wire:click="sort_column('prefix')" name="prefix" :sort="$sortColumn" :sort_by="$sortColumnBy">Prefix</x-table.th>
                <x-table.th wire:click="sort_column('postfix')" name="postfix" :sort="$sortColumn" :sort_by="$sortColumnBy">Postfix</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($deptprefixes as $deptprefix)
                <x-table.tr wire:key="{{ $deptprefix->id }}">
                  <x-table.td>{{ $deptprefix->id }} </x-table.td>
                  <x-table.td>{{ $deptprefix->department->dept_name }} </x-table.td>
                  <x-table.td>{{ $deptprefix->pattern->pattern_name }} </x-table.td>
                  <x-table.td>{{ $deptprefix->prefix }} </x-table.td>
                  <x-table.td>{{ $deptprefix->postfix }} </x-table.td>
                  <x-table.td>
                    @if (!$deptprefix->deleted_at)
                      @if ($deptprefix->status === 1)
                        <x-table.active wire:click="changestatus({{ $deptprefix->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $deptprefix->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($deptprefix->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $deptprefix->id }})" />
                      <x-table.restore wire:click="restore({{ $deptprefix->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $deptprefix->id }})" />
                      <x-table.edit wire:click="edit({{ $deptprefix->id }})" />
                      <x-table.archive wire:click="softdelete({{ $deptprefix->id }})" />
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
          <x-table.paginate :data="$deptprefixes" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
