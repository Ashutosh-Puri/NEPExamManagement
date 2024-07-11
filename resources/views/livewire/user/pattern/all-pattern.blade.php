<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Pattern's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Pattern">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.pattern.pattern-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Pattern">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $pattern_id }})">
      @include('livewire.user.pattern.pattern-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Pattern's">
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
                <x-table.th wire:click="sort_column('pattern_name')" name="pattern_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Name </x-table.th>
                <x-table.th wire:click="sort_column('pattern_startyear')" name="pattern_startyear" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Start Year </x-table.th>
                <x-table.th wire:click="sort_column('pattern_valid')" name="pattern_valid" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Valid</x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($patterns as $pattern)
                <x-table.tr wire:key="{{ $pattern->id }}">
                  <x-table.td>{{ $pattern->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $pattern->pattern_name }}</x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>{{ $pattern->pattern_startyear }} </x-table.td>
                  <x-table.td> {{ $pattern->pattern_valid }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $pattern->college?->college_name }} </x-table.text-scroll>
                  </x-table.td>

                  <x-table.td>
                    @if ($pattern->deleted_at)
                    @elseif($pattern->status == 1)
                      <x-table.active wire:click="update_status({{ $pattern->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $pattern->id }})" />
                    @endif
                  </x-table.td>

                  <x-table.td>
                    @if ($pattern->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $pattern->id }})" />
                      <x-table.restore wire:click="restore({{ $pattern->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $pattern->id }})" />
                      <x-table.archive wire:click="delete({{ $pattern->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='6' class='text-center'>No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$patterns" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
