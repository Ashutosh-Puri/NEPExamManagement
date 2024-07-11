<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Notice's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Notice">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.notice.notice-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Notice">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.notice.notice-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
 
      <x-card-header heading="All Notice's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('title')" name="title" :sort="$sortColumn" :sort_by="$sortColumnBy">Title</x-table.th>
                <x-table.th wire:click="sort_column('type')" name="type" :sort="$sortColumn" :sort_by="$sortColumnBy">Notice Type</x-table.th>
                <x-table.th wire:click="sort_column('start_date')" name="start_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Start Date</x-table.th>
                <x-table.th wire:click="sort_column('end_date')" name="end_date" :sort="$sortColumn" :sort_by="$sortColumnBy">End Date</x-table.th>
                <x-table.th wire:click="sort_column('user_id')" name="user_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Created By</x-table.th>
                <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy">Description</x-table.th>
                <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($notices as $notice)
                <x-table.tr wire:key="{{ $notice->id }}">
                  <x-table.td>{{ $notice->id }} </x-table.td>
                  <x-table.td> <x-table.text-scroll>{{ $notice->title }} </x-table.text-scroll></x-table.td>
                  <x-table.td>
                    @if ($notice->type == 1)
                      Student
                    @elseif ($notice->type == 2)
                      Faculty
                    @elseif ($notice->type == 3)
                      User
                    @elseif ($notice->type == 4)
                      ALL
                    @else
                      Guest
                    @endif
                  </x-table.td>
                  <x-table.td>{{ isset($notice->start_date) ? date('d-m-Y', strtotime($notice->start_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($notice->end_date) ? date('d-m-Y', strtotime($notice->end_date)) : '' }} </x-table.td>
                  <x-table.td><x-table.text-scroll>{{ $notice->user->name }} </x-table.text-scroll></x-table.td>
                  <x-table.td><x-table.text-scroll> {{ $notice->description }} </x-table.text-scroll></x-table.td>
                  <x-table.td>
                    @if (!$notice->deleted_at)
                      @if ($notice->is_active == 1)
                        <x-table.active wire:click="changestatus({{ $notice->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $notice->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($notice->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $notice->id }})" />
                      <x-table.restore wire:click="restore({{ $notice->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $notice->id }})" />
                      <x-table.archive wire:click="delete({{ $notice->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$notices" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
