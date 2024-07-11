<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Block Allocation" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'edit')
    <div>
      <x-card-header heading="Allocate Exam Building">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      @include('livewire.user.block-allocation.block-allocation-form')
    </div>
  @elseif ($mode == 'merge')
    <div>
      <x-card-header heading="Merge Exam Blocks">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      @include('livewire.user.block-allocation.block-allocation-merge-form')
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="Exam Block Allocation">
        <x-spinner class="mx-3" />
      </x-card-header>
      <x-table.frame a='0'>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>Exam Date</x-table.th>
                <x-table.th>Time Slot</x-table.th>
                <x-table.th>Block</x-table.th>
                <x-table.th>Barcode</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @php
                $i = 1;
              @endphp
              @forelse ($grouptimetable as $examdate => $date_time_tables)
                @php
                  $time_slot_time_tables = $date_time_tables->groupBy('timeslot_id');
                @endphp
                @foreach ($time_slot_time_tables as $timeslot_id => $examtimetable)
                  <x-table.tr wire:key="{{ $examdate . '_' . $timeslot_id }}">
                    @if ($loop->first)
                      <x-table.td rowspan="{{ $time_slot_time_tables->count() }}" class="border border-primary-darker">
                        {{ $i++ }}
                      </x-table.td>
                      <x-table.td rowspan="{{ $time_slot_time_tables->count() }}" class="border border-primary-darker">
                        {{ date('d-M-Y', strtotime($examdate)) }}
                      </x-table.td>
                    @endif
                    <x-table.td>{{ $examtimetable->first()->timetableslot->timeslot }}</x-table.td>
                    <x-table.td>
                      @php
                        $flag = $examtimetable->first()->checkblockallocation($examtimetable->first()->timeslot_id);
                      @endphp
                      @if ($flag == 0)
                        <x-table.edit i="0" class=" w-[237px]  !bg-orange-500" wire:click="select_class_room('{{ $examtimetable->first()->examdate }}', {{ $examtimetable->first()->timeslot_id }})">Select Class Room</x-table.edit>
                      @else
                        <x-table.download wire:click="download_pdf('{{ $examtimetable->first()->examdate }}', {{ $examtimetable->first()->timeslot_id }})">PDF</x-table.download>
                        <x-table.download wire:click="download_excel('{{ $examtimetable->first()->examdate }}', {{ $examtimetable->first()->timeslot_id }})" class="!bg-blue-700">EXCEL</x-table.download>
                        <x-table.merge wire:click="merge_block('{{ $examtimetable->first()->examdate }}', {{ $examtimetable->first()->timeslot_id }})" class="!bg-red-700">MERGE</x-table.merge>
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @php
                        $flag1 = $examtimetable->first()->checkbarcode($examtimetable->first()->timeslot_id);
                      @endphp
                      @if ($flag1 !== 0)
                        <a href="{{ route('user.download_barcode', ['examdate' => $examtimetable->first()->examdate, 'timeslot_id' => $examtimetable->first()->timeslot_id]) }}">
                          <x-table.download class="!bg-blue-700">Barcode</x-table.download>
                        </a>
                      @else
                        <a href="{{ route('user.generate_barcode', ['examdate' => $examtimetable->first()->examdate, 'timeslot_id' => $examtimetable->first()->timeslot_id]) }}">
                          <x-table.create>Barcode</x-table.create>
                        </a>
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
