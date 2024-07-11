<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Cap's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Cap">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.cap.cap-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Cap">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.cap.cap-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
     
      <x-card-header heading="All Cap's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('cap_name')" name="cap_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Cap Name</x-table.th>
                <x-table.th wire:click="sort_column('place')" name="place" :sort="$sortColumn" :sort_by="$sortColumnBy">Place</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
                <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($caps as $cap)
                <x-table.tr wire:key="{{ $cap->id }}">
                  <x-table.td>{{ $cap->id }} </x-table.td>
                  <x-table.td>{{ $cap->cap_name }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ $cap->place }} </x-table.text-scroll></x-table.td>
                  <x-table.td>{{ $cap->exam->exam_name }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($cap->college->college_name) ? $cap->college->college_name : '' }} </x-table.text-scroll></x-table.td>
                  <x-table.td>
                    @if (!$cap->deleted_at)
                      @if ($cap->status === 1)
                        <x-table.active wire:click="updatestatus({{ $cap->id }})" />
                      @else
                        <x-table.inactive wire:click="updatestatus({{ $cap->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($cap->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $cap->id }})" />
                      <x-table.restore wire:click="restore({{ $cap->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $cap->id }})" />
                      <x-table.archive wire:click="delete({{ $cap->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$caps" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
