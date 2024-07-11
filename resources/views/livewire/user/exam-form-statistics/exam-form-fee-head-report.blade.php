<div>
  <div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Exam Form Fee Head Statistics" />
    </x-breadcrumb.breadcrumb>
    <x-card-header heading="{{ isset($active_exam->exam_name) ? $active_exam->exam_name : '' }} Exam Form Fee Head Statistics" />
    <x-table.frame s="0" x="0" p="0">
      <x-slot:header>
        <div class="flex gap-x-0.5 ">
          <div class="grid grid-cols-12">
            <span class="col-span-8  ">
              <x-input-select id="pattern_class_id" wire:model.live="pattern_class_id" name="pattern_class_id" class="text-center  w-full  h-10">
                <x-select-option class="text-start" hidden>-- Select Pattern Class --</x-select-option>
                @foreach ($patternclasses as $pc)
                  <x-select-option wire:key="{{ $pc->id }}" value="{{ $pc->id }}" class="text-start">{{ $pc->classyear_name }} {{ $pc->course_name }} {{ $pc->pattern_name }}</x-select-option>
                @endforeach
              </x-input-select>
            </span>
            <span class="col-span-2 ">
              <x-table.cancel class="mx-2" wire:click='clear()' i="0"> Clear</x-table.cancel>
            </span>
            <span class="p-2 col-span-2 ">
              <p wire:loading  class="float-end mx-2">Loading... </p>
              <x-spinner /> 
            </span>
          </div>
        </div>
      </x-slot>
      <x-slot:body>
        @if (count($examfeestatistics) > 0)
          <x-card-collapsible heading=" {{ get_pattern_class_name($pattern_class_id) }}">
            <div>
              <x-table.table>
                <x-table.thead>
                  <x-table.tr>
                    <x-table.th>ID</x-table.th>
                    <x-table.th>Head</x-table.th>
                    <x-table.th class="text-end">Fee</x-table.th>
                    <x-table.th>Count</x-table.th>
                    <x-table.th class="text-end">Total Fee</x-table.th>
                  </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                  @php
                    $total_fee = 0;
                  @endphp
                  @foreach ($examfeestatistics as $statistic)
                    <x-table.tr>
                      <x-table.td>{{ $statistic['exam_fees_id'] }}</x-table.td>
                      <x-table.td>
                        @if ($statistic['sem'])
                          {{ 'SEM-' . $statistic['sem'] }}
                        @endif {{ $statistic['fee_name'] }}
                      </x-table.td>
                      <x-table.td class="text-end">{{ INR($statistic['fee']) }}</x-table.td>
                      <x-table.td>{{ $statistic['form_count'] }}</x-table.td>
                      <x-table.td class="text-end">
                        {{ INR($statistic['total_fee']) }}

                        @php
                          $total_fee+=$statistic['total_fee'];
                        @endphp
                      </x-table.td>
                    </x-table.tr>
                  @endforeach
                  <x-table.tr>
                    <x-table.td colspan="4">Total Fee Received</x-table.td>
                    <x-table.td class="text-end">{{ INR($total_fee) }}</x-table.td>
                  </x-table.tr>
                </x-table.tbody>
              </x-table.table>
            </div>
          </x-card-collapsible>
        @endif
      </x-slot>
    </x-table.frame>
  </div>
</div>
