<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="CGPA's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add CGPA">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.cgpa.cgpa-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit CGPA">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $cgpa_id }})">
      @include('livewire.user.cgpa.cgpa-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All CGPA's">
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
                <x-table.th wire:click="sort_column('max_gp')" name="max_gp" :sort="$sortColumn" :sort_by="$sortColumnBy">Max Grade Point </x-table.th>
                <x-table.th wire:click="sort_column('min_gp')" name="min_gp" :sort="$sortColumn" :sort_by="$sortColumnBy">Min Grade Point </x-table.th>
                <x-table.th wire:click="sort_column('grade')" name="grade" :sort="$sortColumn" :sort_by="$sortColumnBy">Grade</x-table.th>
                <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy">Description </x-table.th>

                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($cgpas as  $cgpa)
                <x-table.tr wire:key="{{ $cgpa->id }}">
                  <x-table.td> {{ $cgpa->id }}</x-table.td>
                  <x-table.td>
                    {{ $cgpa->max_gp }}
                  </x-table.td>
                  <x-table.td> {{ $cgpa->min_gp }} </x-table.td>
                  <x-table.td>
                    {{ $cgpa->grade }}
                  </x-table.td>
                  <x-table.td>
                    {{ $cgpa->description }}
                  </x-table.td>
                  <x-table.td>
                    @if ($cgpa->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $cgpa->id }})" />
                      <x-table.restore wire:click="restore({{ $cgpa->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $cgpa->id }})" />
                      <x-table.archive wire:click="delete({{ $cgpa->id }})" />
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
          <x-table.paginate :data="$cgpas" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
