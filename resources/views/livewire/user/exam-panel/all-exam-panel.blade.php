<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Panel's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Panel">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-panel.exam-panel-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Exam Panel">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $edit_id }})">
      @include('livewire.user.exam-panel.exam-panel-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Exam Panel's">
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
                <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name </x-table.th>
                <x-table.th wire:click="sort_column('examorderpost_id')" name="examorderpost_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Post Name </x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name </x-table.th>
                <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy">Description </x-table.th>
                <x-table.th wire:click="sort_column('active_status')" name="active_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($panels as $panel)
                <x-table.tr wire:key="{{ $panel->id }}">
                  <x-table.td> {{ $panel->id }}</x-table.td>
                  <x-table.td> {{ $panel->faculty->faculty_name }} </x-table.td>
                  <x-table.td> {{ $panel->examorderpost->post_name }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ $panel->subject->subject_name }} </x-table.text-scroll> </x-table.td>
                  <x-table.td> {{ $panel->description }} </x-table.td>
                  <x-table.td>
                    @if ($panel->deleted_at)
                    @elseif($panel->active_status == 1)
                      <x-table.active wire:click="status({{ $panel->id }})" />
                    @else
                      <x-table.inactive wire:click="status({{ $panel->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($panel->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $panel->id }})" />
                      <x-table.restore wire:click="restore({{ $panel->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $panel->id }})" />
                      <x-table.archive wire:click="delete({{ $panel->id }})" />
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
          <x-table.paginate :data="$panels" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
