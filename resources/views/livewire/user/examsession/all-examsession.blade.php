<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Session's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Session">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.examsession.examsession-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Exam Session">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $session_id }})">
      @include('livewire.user.examsession.examsession-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Exam Session's">
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
                <x-table.th wire:click="sort_column('from_date')" name="from_date" :sort="$sortColumn" :sort_by="$sortColumnBy">From Date </x-table.th>
                <x-table.th wire:click="sort_column('to_date')" name="to_date" :sort="$sortColumn" :sort_by="$sortColumnBy">To Date </x-table.th>
                <x-table.th wire:click="sort_column('session_type')" name="session_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Session Type</x-table.th>
                <x-table.th wire:click="sort_column('from_time')" name="from_time" :sort="$sortColumn" :sort_by="$sortColumnBy">From Time </x-table.th>
                <x-table.th wire:click="sort_column('to_time')" name="to_time" :sort="$sortColumn" :sort_by="$sortColumnBy">To Time</x-table.th>
                {{-- <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th> --}}
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($sessions as $session)
                <x-table.tr wire:key="{{ $session->id }}">
                  <x-table.td> {{ $session->id }}</x-table.td>

                  <x-table.td>{{ isset($session->from_date) ? date('d-m-Y', strtotime($session->from_date)) : '' }} </x-table.td>
                  <x-table.td>{{ isset($session->to_date) ? date('d-m-Y', strtotime($session->to_date)) : '' }} </x-table.td>
                  <x-table.td>
                    @if ($session->session_type == 1)
                      <x-status>M</x-status>
                    @elseif($session->session_type == 2)
                      <x-status>E</x-status>
                    @endif

                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>{{ $session->from_time }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll> {{ $session->to_time }} </x-table.text-scroll>
                  </x-table.td>

                  <x-table.td>
                    @if ($session->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $session->id }})" />
                      <x-table.restore wire:click="restore({{ $session->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $session->id }})" />
                      <x-table.archive wire:click="delete({{ $session->id }})" />
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
          <x-table.paginate :data="$sessions" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
