<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Form Statistic's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Exam Form Statistics" />
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      {{ $exam->exam_name }} Exam Form Statistics
    </div>
    <x-table.frame s="0" p="0">
      <x-slot:body>
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
              <x-table.tr wire:key="{{ $statistic->id }}" class="even:bg-primary even:text-white">
                <x-table.td>{{ $statistic->id }}</x-table.td>
                <x-table.td class="text-start">
                  {{ $statistic->patternclass->courseclass->classyear->classyear_name }} {{ $statistic->patternclass->courseclass->course->course_name }} {{ $statistic->patternclass->pattern->pattern_name }}
                </x-table.td>
                <x-table.td class="text-center">
                  @if ($statistic->total_students)
                    <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic->id, 4]) }}">
                      <span class="p-1 justify-center text-center min-w-[60px] text-white bg-green-700 text-xs font-medium inline-flex items-center rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">
                          {{ $statistic->total_students }}
                        </p>
                      </span>
                    </a>
                  @else
                    <span class="p-1 justify-center text-center min-w-[60px] text-white bg-red-700 text-xs font-medium inline-flex items-center rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      <p class="ml-1">
                        {{ $statistic->total_students }}
                      </p>
                    </span>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($statistic->incomplete_forms)
                    <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic->id, 0]) }}">
                      <span class="p-1 justify-center text-center min-w-[60px] text-white bg-green-700 text-xs font-medium inline-flex items-center rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">
                          {{ $statistic->incomplete_forms }}
                        </p>
                      </span>
                    </a>
                  @else
                    <span class="p-1 justify-center text-center min-w-[60px] text-white bg-red-700 text-xs font-medium inline-flex items-center rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      <p class="ml-1">
                        {{ $statistic->incomplete_forms }}
                      </p>
                    </span>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($statistic->yet_to_inward_forms)
                    <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic->id, 1]) }}">
                      <span class="p-1 justify-center text-center min-w-[60px] text-white bg-green-700 text-xs font-medium inline-flex items-center rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">
                          {{ $statistic->yet_to_inward_forms }}
                        </p>
                      </span>
                    </a>
                  @else
                    <span class="p-1 justify-center text-center min-w-[60px] text-white bg-red-700 text-xs font-medium inline-flex items-center rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      <p class="ml-1">
                        {{ $statistic->yet_to_inward_forms }}
                      </p>
                    </span>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($statistic->inward_completed_forms)
                    <a class="cursor-pointer" href="{{ route('user.exam_form_report_view', [$statistic->id, 2]) }}">
                      <span class="p-1 justify-center text-center min-w-[60px] text-white bg-green-700 text-xs font-medium inline-flex items-center rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">
                          {{ $statistic->inward_completed_forms }}
                        </p>
                      </span>
                    </a>
                  @else
                    <span class="p-1 justify-center text-center min-w-[60px] text-white bg-red-700 text-xs font-medium inline-flex items-center rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      <p class="ml-1">
                        {{ $statistic->inward_completed_forms }}
                      </p>
                    </span>
                  @endif
                </x-table.td>
                <x-table.td class="text-end">{{ INR($statistic->total_fee_received) }}</x-table.td>
                @php
                  $total_student_count += $statistic->total_students;
                  $total_incomplete_forms += $statistic->incomplete_forms;
                  $total_yet_to_inword_forms += $statistic->yet_to_inward_forms;
                  $total_inword_forms += $statistic->inward_completed_forms;
                  $total_fee += $statistic->total_fee_received;
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
      </x-slot>
    </x-table.frame>
  </div>
</div>
