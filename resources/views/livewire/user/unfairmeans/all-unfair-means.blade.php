<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Unfairmean's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Unfairmeans">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.unfairmeans.unfairmeans-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Unfairmeans">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $unfairmeans_id }})">
      @include('livewire.user.unfairmeans.unfairmeans-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Unfairmean's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('location')" name="location" :sort="$sortColumn" :sort_by="$sortColumnBy">Location</x-table.th>
                <x-table.th wire:click="sort_column('date')" name="date" :sort="$sortColumn" :sort_by="$sortColumnBy">Date</x-table.th>
                <x-table.th wire:click="sort_column('time')" name="time" :sort="$sortColumn" :sort_by="$sortColumnBy">Time</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($unfairmeans as $unfairmean)
                <x-table.tr wire:key="{{ $unfairmean->id }}">
                  <x-table.td>{{ $unfairmean->id }} </x-table.td>
                  <x-table.td>{{ $unfairmean->location }} </x-table.td>
                  <x-table.td>{{ $unfairmean->date }} </x-table.td>
                  <x-table.td>{{ $unfairmean->time }} </x-table.td>
                  <x-table.td>{{ isset($unfairmean->exam->exam_name)?$unfairmean->exam->exam_name:'-'; }} </x-table.td>
                  <x-table.td>
                    @if ($unfairmean->deleted_at)
                    @elseif($unfairmean->status == 1)
                      <x-table.active wire:click="Status({{ $unfairmean->id }})" />
                    @else
                      <x-table.inactive wire:click="Status({{ $unfairmean->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($unfairmean->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $unfairmean->id }})" />
                      <x-table.restore wire:click="restore({{ $unfairmean->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $unfairmean->id }})" />
                      <x-table.archive wire:click="delete({{ $unfairmean->id }})" />
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
          <x-table.paginate :data="$unfairmeans" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
