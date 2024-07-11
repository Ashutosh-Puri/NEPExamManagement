<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Appointed HOD's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Appoint HOD">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.hodappointsubject.hodappointsubject-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Appointed HOD">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $hodappointsubject_id }})">
      @include('livewire.user.hodappointsubject.hodappointsubject-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Appointed HOD">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.hodappointsubject.view-form')
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="All Appointed HOD's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:header>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
                <x-table.th wire:click="sort_column('appointby_id')" name="appointby_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Appoint By Name</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($hodappointsubjects as $hodappointsubject)
                <x-table.tr wire:key="{{ $hodappointsubject->id }}">
                  <x-table.td>{{ $hodappointsubject->id }} </x-table.td>
                  <x-table.td>{{ $hodappointsubject->faculty->faculty_name }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $hodappointsubject->subject->subject_name }}</x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $hodappointsubject->user->name ?? '' }}</x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    @if ($hodappointsubject->status === 1)
                      <x-table.active wire:click="changestatus({{ $hodappointsubject->id }})" />
                    @else
                      <x-table.inactive wire:click="changestatus({{ $hodappointsubject->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($hodappointsubject->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $hodappointsubject->id }})" />
                      <x-table.restore wire:click="restore({{ $hodappointsubject->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $hodappointsubject->id }})" />
                      <x-table.edit wire:click="edit({{ $hodappointsubject->id }})" />
                      <x-table.archive wire:click="softdelete({{ $hodappointsubject->id }})" />
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
          <x-table.paginate :data="$hodappointsubjects" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
