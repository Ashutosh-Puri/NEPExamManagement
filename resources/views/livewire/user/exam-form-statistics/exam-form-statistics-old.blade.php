<div>
  <x-card-header heading="Exam Form Statistics" />
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      {{ $exam->exam_name }} Exam Form Statistics
    </div>
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>ID</x-table.th>
            <x-table.th class="text-start">Pattern Class</x-table.th>
            <x-table.th>Total Students</x-table.th>
            <x-table.th>Incomplete</x-table.th>
            <x-table.th>Yet To Inward</x-table.th>
            <x-table.th>Inward Completed</x-table.th>
            <x-table.th class="text-end">Total Fee Received</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @php
            $total_student_count = 0;
            $total_incomplete_forms = 0;
            $total_yet_to_inword_forms = 0;
            $total_inword_forms = 0;
            $total_fee = 0;
          @endphp
          @foreach ($statistics as $statistic)
              <x-table.tr wire:key="{{ $statistic['id'] }}" class="even:bg-primary even:text-white">
                  <x-table.td>{{ $statistic['id'] }}</x-table.td>
                  <x-table.td class="text-start">{{ $statistic['pattern_class'] }}</x-table.td>
                  <x-table.td>
                    @if ($statistic['total_students'])
                      <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic['id'], 4]) }}">
                        <x-table.download class="m-0 p-0">
                          <x-status type="danger">{{ $statistic['total_students'] }} </x-status> 
                        </x-table.download>
                      </a>
                    @else
                      <span class="px-3 py-1"> <x-status type="danger">{{ $statistic['total_students'] }} </x-status></span>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($statistic['incomplete_forms'])
                      <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic['id'], 0]) }}">
                        <x-table.download class="m-0 p-0">
                          <x-status type="danger">{{ $statistic['incomplete_forms'] }} </x-status>
                        </x-table.download>
                      </a>
                    @else
                      <span class="px-3 py-1"><x-status type="danger"> {{ $statistic['incomplete_forms'] }} </x-status></span>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($statistic['yet_to_inward_forms'])
                      <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic['id'], 0]) }}">
                        <x-table.download class="m-0 p-0">
                          <x-status type="danger">{{ $statistic['yet_to_inward_forms'] }} </x-status>
                        </x-table.download>
                      </a>
                    @else
                      <span class="px-3 py-1"><x-status type="danger"> {{ $statistic['yet_to_inward_forms'] }} </x-status></span>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($statistic['inward_completed_forms'])
                      <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic['id'], 0]) }}">
                        <x-table.download class="m-0 p-0">
                          <x-status type="danger">{{ $statistic['inward_completed_forms'] }} </x-status>
                        </x-table.download>
                      </a>
                    @else
                      <span class="px-3 py-1"><x-status type="danger"> {{ $statistic['inward_completed_forms'] }} </x-status></span>
                    @endif
                  </x-table.td>
                  <x-table.td class="text-end">{{ INR($statistic['total_fee_received']) }}</x-table.td>
                  @php
                       $total_student_count +=$statistic['total_students'];
                       $total_incomplete_forms +=$statistic['incomplete_forms'];
                       $total_yet_to_inword_forms+=$statistic['yet_to_inward_forms'];
                       $total_inword_forms+=$statistic['inward_completed_forms'];
                       $total_fee+=$statistic['total_fee_received'];
                  @endphp
              </x-table.tr>
          @endforeach
          <x-table.tr>
            <x-table.td colspan="7">&nbsp;</x-table.td>
          </x-table.tr>
          <x-table.tr class="h-10">
            <x-table.td class="font-bold text-md text-start" colspan="2"> TOTAL</x-table.td>
            <x-table.td><span class="font-bold text-md px-3 py-1"> {{ number_format($total_student_count) }} </span></x-table.td>
            <x-table.td><span class="font-bold text-md px-3 py-1"> {{ number_format($total_incomplete_forms) }} </span></x-table.td>
            <x-table.td><span class="font-bold text-md px-3 py-1"> {{ number_format($total_yet_to_inword_forms) }} </span></x-table.td>
            <x-table.td><span class="font-bold text-md px-3 py-1"> {{ number_format($total_inword_forms) }} </span></x-table.td>
            <x-table.td class="font-bold text-md text-end"> {{ INR($total_fee) }}</x-table.td>
          </x-table.tr>
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
</div>
