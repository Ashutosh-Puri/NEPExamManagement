<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Internal Tool's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Internal Tool">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.internal-audit.internal-tool.internaltool-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Internal Tool">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $internaltool_id }})">
      @include('livewire.user.internal-audit.internal-tool.internaltool-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Internal Tool">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.internal-audit.internal-tool.view-form')
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="All Internal Tool's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('toolname')" name="toolname" :sort="$sortColumn" :sort_by="$sortColumnBy">Tool Name</x-table.th>
                <x-table.th wire:click="sort_column('course_type')" name="course_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Programme Type</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($internal_tools as $internal_tool)
                <x-table.tr wire:key="{{ $internal_tool->id }}">
                  <x-table.td>{{ $internal_tool->id }} </x-table.td>
                  <x-table.td>{{ $internal_tool->toolname }} </x-table.td>
                  <x-table.td>{{ $internal_tool->course_type }} </x-table.td>
                  <x-table.td>
                    @if (!$internal_tool->deleted_at)
                      @if ($internal_tool->status === 1)
                        <x-table.active wire:click="changestatus({{ $internal_tool->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $internal_tool->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($internal_tool->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $internal_tool->id }})" />
                      <x-table.restore wire:click="restore({{ $internal_tool->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $internal_tool->id }})" />
                      <x-table.edit wire:click="edit({{ $internal_tool->id }})" />
                      <x-table.archive wire:click="softdelete({{ $internal_tool->id }})" />
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
          <x-table.paginate :data="$internal_tools" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
