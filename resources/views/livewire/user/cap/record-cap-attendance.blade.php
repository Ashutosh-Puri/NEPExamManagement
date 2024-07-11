<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Cap Attendance" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Cap Attendance" />
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Cap Attendance <x-spinner />
      <x-status class="py-1 mx-2 float-right" type="danger" wire:click='attendance_report()'>Cap Attendance Report</x-status>
    </div>
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="selecteddepartment" :value="__('Select Department')" />
      <x-input-select id="selecteddepartment" wire:model.live="selecteddepartment" name="selecteddepartment" class="text-center w-full mt-1" :value="old('selecteddepartment', $selecteddepartment)" autocomplete="selecteddepartment">
        <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
        @forelse ($departments as $department)
          <x-select-option wire:key="{{ $department->id }}" value="{{ $department->id }}" class="text-start"> {{ $department->dept_name }} </x-select-option>
        @empty
          <x-select-option class="text-start">Departments Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('selecteddepartment')" class="mt-1" />
    </div>
    @if ($dates)
      <div class="overflow-x-scroll">
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th>Faculty Name </x-table.th>
              @foreach ($dates as $key => $dt)
                <x-table.th>{{ date('d-M-y', strtotime($dt)) }}</x-table.th>
              @endforeach
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($faculties as $faculty)
              <x-table.tr wire:key="{{ $faculty->id }}" class="hover:bg-primary-light">
                <x-table.td>
                  {{ $faculty->faculty_name }}
                  @if (isset($supervisionchart[$faculty->id]))
                    <span class="inline-flex justify-center items-center ml-1 w-5 h-5 text-xs font-semibold text-white bg-primary-darker rounded-full">
                      @php
                        $cnt = 0;
                      @endphp
                      @foreach (collect($supervisionchart[$faculty->id])->flatten() as $d)
                        @if ($d)
                          @php $cnt++; @endphp
                        @endif
                      @endforeach
                      {{ $cnt }}
                    </span>
                  @endif
                </x-table.td>
                @foreach ($dates as $key => $dt)
                  <x-table.td>
                    <x-input-checkbox wire:loading.class='cursor-not-allowed' name="supervisionchart.{{ $faculty->id }}.{{ $dt }}" wire:model.live="supervisionchart.{{ $faculty->id }}.{{ $dt }}" id="supervisionchart.{{ $faculty->id }}.{{ $dt }}" class="h-6 w-6 mx-2 cursor-pointer" />
                  </x-table.td>
                @endforeach
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
      </div>
    @endif
  </div>
</div>
