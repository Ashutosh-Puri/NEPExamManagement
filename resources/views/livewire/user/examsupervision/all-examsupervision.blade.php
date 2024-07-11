<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Exam Supervision <x-spinner/>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="selectedallsession" :value="__('Select Exam Session')" />
      <x-required />
      <x-input-select id="selectedallsession" wire:model.live="selectedallsession" name="selectedallsession" class="text-center w-full mt-1" required autocomplete="selectedallsession">
        <x-select-option class="text-start" hidden>-- Select Exam Session --</x-select-option>
        @foreach ($allsession as $type)
          <x-select-option value="{{ $type->id }}" class="text-start">
            From {{ date('d-M-Y', strtotime($type->from_date)) }} To {{ date('d-M-Y', strtotime($type->to_date)) }}
          </x-select-option>
        @endforeach
      </x-input-select>
      <x-input-error :messages="$errors->get('selectedallsession')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="selecteddepartment" :value="__('Select Department')" />
      <x-required />
      <x-input-select id="selecteddepartment" wire:model.live="selecteddepartment" name="selecteddepartment" class="text-center w-full mt-1" :value="old('selecteddepartment', $selecteddepartment)" required autofocus autocomplete="selecteddepartment">
        <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
        @foreach ($departments as $departmentid => $dept_name)
          <x-select-option value="{{ $departmentid }}" class="text-start"> {{ $dept_name }}</x-select-option>
        @endforeach
      </x-input-select>
      <x-input-error :messages="$errors->get('selecteddepartment')" class="mt-2" />
    </div>
  </div>
  <div class="overflow-x-scroll">
    @if ($dates)
      <x-table.table class="w-auto">
        <x-table.thead>
          <x-table.tr>
            <x-table.th> <span wire:loading > Proccessing... </span> </x-table.th>
            @foreach ($dates as $key => $dt)
              <x-table.th colspan="{{ sizeof($sessiontype) }}"> {{ date('d-M-y', strtotime($dt)) }}</x-table.th>
            @endforeach
          </x-table.tr>
          <x-table.tr>
            <x-table.th>Faculty Name</x-table.th>
            @foreach ($dates as $key => $dt)
              @foreach ($sessiontype as $st)
                <x-table.th>
                  <p class="text-center">
                    @if ($st->session_type == 1)
                      M
                    @elseif($st->session_type == 2)
                      E
                    @endif
                  </p>
                  <p class="text-center"> {{ $st->from_time }}</p>
                </x-table.th>
              @endforeach
            @endforeach
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @foreach ($faculties as $faculty_id => $faculty_name )
            <x-table.tr wire:key="{{ $faculty_id }}" class="hover:bg-primary-light">
              <x-table.td >

                {{ $faculty_name }}

                @if (isset($supervisionchart[$faculty_id]))
                  <span class="inline-flex justify-center items-center ml-1 w-5 h-5 text-xs font-semibold text-green-800 bg-blue-400 rounded-full">
                    @php
                      $cnt = 0;
                    @endphp

                    @foreach (collect($supervisionchart[$faculty_id])->flatten() as $d)
                      @if ($d)
                        @php $cnt++; @endphp
                      @endif
                    @endforeach
                    {{ $cnt }}
                  </span>
                @endif
              </x-table.td>
              @foreach ($dates as $key => $dt)
                @foreach ($sessiontype as $st)
                  <x-table.td class="text-center">
                    <x-input-checkbox wire:loading.class='cursor-not-allowed' wire.loading.attr="disabled"   wire:model.live="supervisionchart.{{ $faculty_id }}.{{ $dt }}.{{ $st->id }}"  value="{{ $dt }}"  name="supervisionchart.{{ $faculty_id }}.{{ $dt }}.{{ $st->id }}"   id="supervisionchart.{{ $faculty_id }}.{{ $dt }}.{{ $st->id }}"  class="h-6 w-6 mx-2 boder border-primary cursor-pointer" />
                  </x-table.td>
                @endforeach
              @endforeach
            </x-table.tr>
          @endforeach
        </x-table.tbody>
      </x-table.table>
    @endif
  </div>
</div>
