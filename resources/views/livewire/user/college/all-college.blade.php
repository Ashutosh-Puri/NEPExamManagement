<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="College's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add College">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.college.college-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit College">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="updateCollege({{ $college_id }})">
      @include('livewire.user.college.college-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All College's">
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
                <x-table.th wire:click="sort_column('college_name')" name="college_name" :sort="$sortColumn" :sort_by="$sortColumnBy">College Name </x-table.th>
                <x-table.th wire:click="sort_column('sanstha_id')" name="sanstha_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Sanstha </x-table.th>
                <x-table.th wire:click="sort_column('university_id')" name="university_id" :sort="$sortColumn" :sort_by="$sortColumnBy">University</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($colleges as $college)
                <x-table.tr wire:key="{{ $college->id }}">
                  <x-table.td> {{ $college->id }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll class="w-full"> {{ $college->college_name }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ isset($college->sanstha->sanstha_name) ? $college->sanstha->sanstha_name : '-' }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ isset($college->university->university_name) ? $college->university->university_name : '-' }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($college->deleted_at)
                    @elseif($college->status == 1)
                      <x-table.active wire:click="update_status({{ $college->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $college->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($college->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $college->id }})" />
                      <x-table.restore wire:click="restore({{ $college->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $college->id }})" />
                      <x-table.archive wire:click="delete({{ $college->id }})" />
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
          <x-table.paginate :data="$colleges" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
