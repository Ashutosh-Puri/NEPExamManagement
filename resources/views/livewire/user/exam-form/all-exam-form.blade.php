<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Form's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'all')
    <div>
     
      <x-card-header heading="All Exam Form's" />
      <x-table.frame>
        <x-slot:header>
          <div class="flex gap-x-0.5">
            <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center  h-10">
              <x-select-option class="text-start" hidden>Year </x-select-option>
              @foreach ($academic_years as $a_id => $a_name)
                <x-select-option wire:key="{{ $a_id }}" value="{{ $a_id }}" class="text-start">{{ $a_name }}</x-select-option>
              @endforeach
            </x-input-select>
            <x-input-select id="exam_id" wire:model.live="exam_id" name="exam_id" class="text-center h-10 ">
              <x-select-option class="text-start" hidden> Exam </x-select-option>
              @foreach ($exams as $examid => $examname)
                <x-select-option wire:key="{{  $examid }}" value="{{ $examid }}" class="text-start">{{ $examname }}</x-select-option>
              @endforeach
            </x-input-select>
            <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-80 h-10 ">
              <x-select-option class="text-start" hidden> Pattern Class </x-select-option>
              @foreach ($patternclasses as $pc)
                <x-select-option wire:key="{{ $pc->id }}" value="{{ $pc->id }}" class="text-start"> {{ $pc->classyear_name ?? '-' }} {{ $pc->course_name ?? '-' }} {{ $pc->pattern_name ?? '-' }}</x-select-option>
              @endforeach
            </x-input-select>
            <x-input-select id="fee_status" wire:model.live="fee_status" name="fee_status" class="text-center h-10 ">
              <x-select-option class="text-start" hidden> Fee Status </x-select-option>
              <x-select-option class="text-start" value="1"> Paid </x-select-option>
              <x-select-option class="text-start" value="2"> Not Paid </x-select-option>
            </x-input-select>
            <x-input-select id="payment_status" wire:model.live="payment_status" name="payment_status" class="text-center h-10 ">
              <x-select-option class="text-start" hidden> Payment Status </x-select-option>
              <x-select-option class="text-start" value="created"> Order Created </x-select-option>
              <x-select-option class="text-start" value="authorized"> Authorized </x-select-option>
              <x-select-option class="text-start" value="captured"> Capture </x-select-option>
              <x-select-option class="text-start" value="refunded"> Refund </x-select-option>
              <x-select-option class="text-start" value="failed"> Failed </x-select-option>
            </x-input-select>
            <span class="h-10">
              <x-table.cancel class="mx-0.5 py-0.5 h-10" wire:click='clear()' i="0"> Clear</x-table.cancel>
            </span>
          </div>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('examformmasters.id')" name="examformmasters.id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.exam_id')" name="examformmasters.exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.exam_id')" name="examformmasters.exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.patternclass_id')" name="examformmasters.patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.student_id')" name="examformmasters.student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">PRN</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.student_id')" name="examformmasters.student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Member ID</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.student_id')" name="examformmasters.student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student Name</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.totalfee')" name="examformmasters.totalfee" :sort="$sortColumn" :sort_by="$sortColumnBy">Fee Amount</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.feepaidstatus')" name="examformmasters.feepaidstatus" :sort="$sortColumn" :sort_by="$sortColumnBy">Fee Paid</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.inwardstatus')" name="examformmasters.inwardstatus" :sort="$sortColumn" :sort_by="$sortColumnBy">Inward</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.transaction_id')" name="examformmasters.transaction_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Payment ID</x-table.th>
                <x-table.th wire:click="sort_column('transactions.status')" name="transactions.status" :sort="$sortColumn" :sort_by="$sortColumnBy">Payment Status</x-table.th>
                <x-table.th wire:click="sort_column('transactions.payment_date')" name="transactions.payment_date" :sort="$sortColumn" :sort_by="$sortColumnBy">Payment Date</x-table.th>
                <x-table.th wire:click="sort_column('examformmasters.created_at')" name="examformmasters.created_at" :sort="$sortColumn" :sort_by="$sortColumnBy">Form Date</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($exam_form_masters as $key => $examformmaster)
              <x-table.tr wire:key="{{ $examformmaster->id }}">
                <x-table.td>{{ $examformmaster->id }} </x-table.td>
                <x-table.td>{{ isset($examformmaster->exam->academicyear->year_name) ? $examformmaster->exam->academicyear->year_name : '' }} </x-table.td>
                <x-table.td> <x-table.text-scroll> {{ $examformmaster->exam->exam_name }} </x-table.text-scroll></x-table.td>
                <x-table.td> <x-table.text-scroll> {{ isset($examformmaster->patternclass->courseclass->classyear->classyear_name) ? $examformmaster->patternclass->courseclass->classyear->classyear_name : '' }} {{ isset($examformmaster->patternclass->courseclass->course->course_name) ? $examformmaster->patternclass->courseclass->course->course_name : '' }} {{ isset($examformmaster->patternclass->pattern->pattern_name) ? $examformmaster->patternclass->pattern->pattern_name : '' }}</x-table.text-scroll></x-table.td>
                  <x-table.td>{{ isset($examformmaster->student->memid) ? $examformmaster->student->prn : '' }} </x-table.td>
                  <x-table.td>{{ isset($examformmaster->student->memid) ? $examformmaster->student->memid : '' }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($examformmaster->student->student_name) ? $examformmaster->student->student_name : '' }} </x-table.text-scroll> </x-table.td>
                  <x-table.td>{{ INR($examformmaster->totalfee) }} </x-table.td>
                  <x-table.td>
                    @if ($examformmaster->feepaidstatus)
                      <x-status type="success">Paid</x-status>
                      @if (isset($examformmaster->transaction->status) && $examformmaster->transaction->status == 'captured')
                        <x-status type="success"> Online </x-status>
                      @else
                        <x-status type="success"> Chash </x-status>
                      @endif
                    @else
                      <x-status type="danger">Not Paid</x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($examformmaster->inwardstatus)
                      <x-status type="success">Yes</x-status>
                    @else
                      <x-status type="danger">No</x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (isset($examformmaster->transaction_id))
                      @if ($examformmaster->transaction->status == 'captured')
                        <x-status> {{ isset($examformmaster->transaction->razorpay_payment_id) ? $examformmaster->transaction->razorpay_payment_id : '' }}</x-status>
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (isset($examformmaster->transaction->status))
                      @if ($examformmaster->transaction->status == 'captured')
                        <x-status type="success"> Captured</x-status>
                      @elseif ($examformmaster->transaction->status == 'refunded')
                        <x-status type="info">Refunded</x-status>
                      @elseif ($examformmaster->transaction->status == 'failed')
                        <x-status type="danger">Failed</x-status>
                      @elseif ($examformmaster->transaction->status == 'created')
                        <x-status>Order Created</x-status>
                      @elseif ($examformmaster->transaction->status == 'authorized')
                        <x-status type="warning">Authorized</x-status>
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (isset($examformmaster->transaction->status) && $examformmaster->transaction->status == 'captured')
                      <x-status> {{ isset($examformmaster->transaction->payment_date) ? Carbon\Carbon::parse($examformmaster->transaction->payment_date)->format('Y-m-d h:i:s A') : '' }}</x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>{{ isset($examformmaster->created_at) ? $examformmaster->created_at->format('Y-m-d') : '' }} </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan="13">
                    <p class="text-center">No Records Found</p>
                  </x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$exam_form_masters" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
