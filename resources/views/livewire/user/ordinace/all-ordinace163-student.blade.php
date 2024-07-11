<div>
  <div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Ordinace 163 Students" />
    </x-breadcrumb.breadcrumb>
    <x-card-header heading="All Ordinace 163 Student's">
    </x-card-header>
    <x-table.frame>
      <x-slot:body>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
              <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
              <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
              <x-table.th wire:click="sort_column('seatno')" name="seatno" :sort="$sortColumn" :sort_by="$sortColumnBy">Seatno</x-table.th>
              <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student</x-table.th>
              <x-table.th wire:click="sort_column('ordinace163master_id')" name="ordinace163master_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Activity</x-table.th>
              <x-table.th wire:click="sort_column('marks')" name="marks" :sort="$sortColumn" :sort_by="$sortColumnBy">Marks</x-table.th>
              <x-table.th wire:click="sort_column('marksused')" name="marksused" :sort="$sortColumn" :sort_by="$sortColumnBy">marks used</x-table.th>
              <x-table.th wire:click="sort_column('fee')" name="fee" :sort="$sortColumn" :sort_by="$sortColumnBy">Fee</x-table.th>
              <x-table.th wire:click="sort_column('is_fee_paid')" name="is_fee_paid" :sort="$sortColumn" :sort_by="$sortColumnBy">Paid</x-table.th>
              <x-table.th wire:click="sort_column('is_applicable')" name="is_applicable" :sort="$sortColumn" :sort_by="$sortColumnBy">Apply</x-table.th>
              <x-table.th wire:click="sort_column('transaction_id ')" name="transaction_id " :sort="$sortColumn" :sort_by="$sortColumnBy">Transaction ID</x-table.th>
              <x-table.th wire:click="sort_column('payment_date ')" name="payment_date " :sort="$sortColumn" :sort_by="$sortColumnBy">Paid Date</x-table.th>
              <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($studentordinace163s as $studentordinace163)
              <x-table.tr wire:key="{{ $studentordinace163->id }}">
                <x-table.td>{{ $studentordinace163->id }} </x-table.td>
                <x-table.td>{{ $studentordinace163->exam->exam_name }} </x-table.td>
                <x-table.td> {{ isset($studentordinace163->patternclass->courseclass->classyear->classyear_name) ? $studentordinace163->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($studentordinace163->patternclass->courseclass->course->course_name) ? $studentordinace163->patternclass->courseclass->course->course_name : '-' }} {{ isset($studentordinace163->patternclass->pattern->pattern_name) ? $studentordinace163->patternclass->pattern->pattern_name : '-' }} </x-table.td>
                <x-table.td>{{ $studentordinace163->seatno }} </x-table.td>
                <x-table.td>{{ $studentordinace163->student->student_name }} </x-table.td>
                <x-table.td>{{ $studentordinace163->ordinace163master->activity_name }} </x-table.td>
                <x-table.td>{{ $studentordinace163->marks }} </x-table.td>
                <x-table.td>{{ $studentordinace163->marksused }} </x-table.td>
                <x-table.td>{{ $studentordinace163->fee }} </x-table.td>
                <x-table.td>
                  @if ($studentordinace163->is_fee_paid)
                    Y
                  @else
                    N
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($studentordinace163->is_applicable)
                    Y
                  @else
                    N
                  @endif
                </x-table.td>
                <x-table.td>
                  @if (isset($studentordinace163->transaction->razorpay_payment_id) && $studentordinace163->transaction->status == 'captured')
                    {{ $studentordinace163->transaction->razorpay_payment_id }}
                  @endif
                </x-table.td>
                <x-table.td>
                  {{-- @dump($studentordinace163->payment_date) --}}
                  @if (isset($studentordinace163->payment_date) && $studentordinace163->payment_date)
                    @php
                      $paymentDate = new DateTime($studentordinace163->payment_date);
                    @endphp
                    {{ $paymentDate->format('Y-m-d') }}
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($studentordinace163->status === 1)
                    <x-table.active wire:click="change_status({{ $studentordinace163->id }})" />
                  @else
                    <x-table.inactive wire:click="change_status({{ $studentordinace163->id }})" />
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
      </x-slot>
      <x-slot:footer>
        <x-table.paginate :data="$studentordinace163s" />
      </x-slot>
    </x-table.frame>
  </div>
</div>
