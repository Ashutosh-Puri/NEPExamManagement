<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="student.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Payments" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Student Payments">
  </x-card-header>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Payments
    </div>
    <section>
      <div class="overflow-x-scroll pb-4">
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th>No.</x-table.th>
              <x-table.th>Name</x-table.th>
              <x-table.th>Exam </x-table.th>
              <x-table.th>Fee</x-table.th>
              <x-table.th>Fee Status</x-table.th>
              <x-table.th>Payment Status</x-table.th>
              <x-table.th>Form Status</x-table.th>
              <x-table.th>Action</x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @php
              $count = 1;
            @endphp
            @foreach ($exam_form_masters->where('feepaidstatus', 0) as $exm_form)
              <x-table.tr>
                <x-table.td>{{ $count++ }} </x-table.td>
                <x-table.td>Exam Form</x-table.td>
                <x-table.td>{{ $exm_form->exam->exam_name }}</x-table.td>
                <x-table.td>{{ $exm_form->totalfee }} Rs.</x-table.td>
                <x-table.td>
                  @if ($exm_form->feepaidstatus)
                    <x-status type="success"> Paid</x-status>
                    @if (isset($exm_form->transaction->status) && $exm_form->transaction->status === 'captured')
                      <x-status type="success"> Online</x-status>
                    @else
                      <x-status type="danger">Cash</x-status>
                    @endif
                  @else
                    <x-status type="danger">Not Paid</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if (isset($exm_form->transaction->status))
                    @if ($exm_form->transaction->status == 'captured')
                      <x-status type="success"> Success</x-status>
                    @elseif ($exm_form->transaction->status == 'refunded')
                      <x-status type="info">Refunded</x-status>
                    @elseif ($exm_form->transaction->status == 'failed')
                      <x-status type="danger">Failed</x-status>
                    @elseif ($exm_form->transaction->status == 'created')
                      <x-status>Order Created</x-status>
                    @elseif ($exm_form->transaction->status == 'authorized')
                      <x-status type="warning">Processing</x-status>
                    @endif
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($exm_form->inwardstatus == 0)
                    @if (isset($exm_form->verified_at))
                      <x-status type="info">Verified</x-status>
                    @else
                      @if ($exm_form->printstatus == 0)
                        <x-status type="warning">Print To Confirm</x-status>
                      @else
                        <x-status type="danger"> Pending</x-status>
                      @endif
                    @endif
                  @else
                    <x-status type="success"> Approved</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($exm_form->exam->status == 1)
                    @if ($exm_form->feepaidstatus == 1)
                      <x-dashboard.form-button class="bg-orange-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_exam_form_fee_recipet') }}" name="Fee Reciept" />
                    @else
                      @if ($exm_form->verified_at)
                        <x-dashboard.form-button class="bg-green-500 h-7 rounded-md border-none" action="{{ route('student.student_pay_exam_form_fee') }}" name="Pay Fee">
                          <input type="hidden" name="exam_form_master_id" value="{{ $exm_form->id }}">
                        </x-dashboard.form-button>
                      @endif
                    @endif
                    @if ($exm_form->inwardstatus == 0)
                      @if ($exm_form->feepaidstatus == 0)
                        @if ($exm_form->printstatus == 0)
                          <x-dashboard.form-button class="bg-pink-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_preview_exam_form') }}" name="Preview" />
                          <x-dashboard.form-button class="bg-red-500 h-7 rounded-md border-none" action="{{ route('student.student_delete_exam_form') }}" onclick="return confirm('Are You Sure You Want Delete Exam Form.')" name="Delete" />
                          <x-dashboard.form-button class="bg-blue-500 h-7 rounded-md border-none" target="_blank" wire:click='$refresh' action="{{ route('student.student_print_final_exam_form') }}" onclick="return confirm('Once Printed, the form cannot be edited. Confirm if you wish to print it.')" name="Confirm & Print" />
                        @else
                          <x-dashboard.form-button class="bg-blue-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_final_exam_form') }}" name="Print" />
                        @endif
                      @endif
                    @endif
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
            @foreach ($student_ordinace_163s->where('is_fee_paid', 0) as $student_ordinace_163)
              <x-table.tr>
                <x-table.td>{{ $count++ }} </x-table.td>
                <x-table.td>Ordinace 163 Form</x-table.td>
                <x-table.td>{{ $student_ordinace_163->exam->exam_name }}</x-table.td>
                <x-table.td>{{ $student_ordinace_163->fee }} Rs.</x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163->is_fee_paid)
                    <x-status type="success"> Paid</x-status>
                    @if (isset($student_ordinace_163->transaction->status) && $student_ordinace_163->transaction->status === 'captured')
                      <x-status type="success"> Online</x-status>
                    @else
                      <x-status type="danger">Cash</x-status>
                    @endif
                  @else
                    <x-status type="danger">Not Paid</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if (isset($student_ordinace_163->transaction->status))
                    @if ($student_ordinace_163->transaction->status == 'captured')
                      <x-status type="success"> Success</x-status>
                    @elseif ($student_ordinace_163->transaction->status == 'refunded')
                      <x-status type="info">Refunded</x-status>
                    @elseif ($student_ordinace_163->transaction->status == 'failed')
                      <x-status type="danger">Failed</x-status>
                    @elseif ($student_ordinace_163->transaction->status == 'created')
                      <x-status>Order Created</x-status>
                    @elseif ($student_ordinace_163->transaction->status == 'authorized')
                      <x-status type="warning">Processing</x-status>
                    @endif
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163->is_applicable == 0)
                    <x-status type="danger">Not Approved</x-status>
                  @else
                    <x-status type="success">Approved</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163->status == 0)
                    @if ($student_ordinace_163->is_fee_paid == 1)
                      <x-dashboard.form-button class="bg-orange-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_ordinace_163_form_fee_recipet') }}" name="Fee Reciept">
                        <input type="hidden" name="student_ordinace_163_id" value="{{ $student_ordinace_163->id }}">
                      </x-dashboard.form-button>
                    @else
                      <x-dashboard.form-button class="bg-green-500 h-7 rounded-md border-none" action="{{ route('student.student_pay_ordinace_163_form_fee') }}" name="Pay Fee">
                        <input type="hidden" name="student_ordinace_163_id" value="{{ $student_ordinace_163->id }}">
                      </x-dashboard.form-button>
                    @endif
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
            <x-table.tr>
              @php
                $count = 1;
              @endphp
              <x-table.td colspan="8" class='text-center bg-primary-darker'>Payment History</x-table.td>
            </x-table.tr>
            @foreach ($exam_form_masters->where('feepaidstatus', 1) as $exm_form_1)
              <x-table.tr>
                <x-table.td>{{ $count++ }} </x-table.td>
                <x-table.td>Exam Form</x-table.td>
                <x-table.td>{{ $exm_form_1->exam->exam_name }}</x-table.td>
                <x-table.td>{{ $exm_form_1->totalfee }} Rs.</x-table.td>
                <x-table.td>
                  @if ($exm_form_1->feepaidstatus)
                    <x-status type="success"> Paid</x-status>
                    @if (isset($exm_form_1->transaction->status) && $exm_form_1->transaction->status === 'captured')
                      <x-status type="success"> Online</x-status>
                    @else
                      <x-status type="danger">Cash</x-status>
                    @endif
                  @else
                    <x-status type="danger">Not Paid</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if (isset($exm_form_1->transaction->status))
                    @if ($exm_form_1->transaction->status == 'captured')
                      <x-status type="success"> Success</x-status>
                    @elseif ($exm_form_1->transaction->status == 'refunded')
                      <x-status type="info">Refunded</x-status>
                    @elseif ($exm_form_1->transaction->status == 'failed')
                      <x-status type="danger">Failed</x-status>
                    @elseif ($exm_form_1->transaction->status == 'created')
                      <x-status>Order Created</x-status>
                    @elseif ($exm_form_1->transaction->status == 'authorized')
                      <x-status type="warning">Processing</x-status>
                    @endif
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($exm_form_1->inwardstatus == 0)
                    @if (isset($exm_form_1->verified_at))
                      <x-status type="info">Verified</x-status>
                    @else
                      @if ($exm_form_1->printstatus == 0)
                        <x-status type="warning">Print To Confirm</x-status>
                      @else
                        <x-status type="danger"> Pending</x-status>
                      @endif
                    @endif
                  @else
                    <x-status type="success"> Approved</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($exm_form_1->exam->status == 1)
                    @if ($exm_form_1->feepaidstatus == 1)
                      <x-dashboard.form-button class="bg-orange-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_exam_form_fee_recipet') }}" name="Fee Reciept" />
                    @else
                      @if ($exm_form_1->verified_at)
                        <x-dashboard.form-button class="bg-green-500 h-7 rounded-md border-none" action="{{ route('student.student_pay_exam_form_fee') }}" name="Pay Fee">
                          <input type="hidden" name="exam_form_master_id" value="{{ $exm_form_1->id }}">
                        </x-dashboard.form-button>
                      @endif
                    @endif
                    @if ($exm_form_1->inwardstatus == 0)
                      @if ($exm_form_1->feepaidstatus == 0)
                        @if ($exm_form_1->printstatus == 0)
                          <x-dashboard.form-button class="bg-pink-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_preview_exam_form') }}" name="Preview" />
                          <x-dashboard.form-button class="bg-red-500 h-7 rounded-md border-none" action="{{ route('student.student_delete_exam_form') }}" onclick="return confirm('Are You Sure You Want Delete Exam Form.')" name="Delete" />
                          <x-dashboard.form-button class="bg-blue-500 h-7 rounded-md border-none" target="_blank" wire:click='$refresh' action="{{ route('student.student_print_final_exam_form') }}" onclick="return confirm('Once Printed, the form cannot be edited. Confirm if you wish to print it.')" name="Confirm & Print" />
                        @else
                          <x-dashboard.form-button class="bg-blue-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_final_exam_form') }}" name="Print" />
                        @endif
                      @endif
                    @endif
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
            @foreach ($student_ordinace_163s->where('is_fee_paid', 1) as $student_ordinace_163_1)
              <x-table.tr>
                <x-table.td>{{ $count++ }} </x-table.td>
                <x-table.td>Ordinace 163 Form</x-table.td>
                <x-table.td>{{ $student_ordinace_163_1->exam->exam_name }}</x-table.td>
                <x-table.td>{{ $student_ordinace_163_1->fee }} Rs.</x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163_1->is_fee_paid)
                    <x-status type="success"> Paid</x-status>
                    @if (isset($student_ordinace_163_1->transaction->status) && $student_ordinace_163_1->transaction->status === 'captured')
                      <x-status type="success"> Online</x-status>
                    @else
                      <x-status type="danger">Cash</x-status>
                    @endif
                  @else
                    <x-status type="danger">Not Paid</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if (isset($student_ordinace_163_1->transaction->status))
                    @if ($student_ordinace_163_1->transaction->status == 'captured')
                      <x-status type="success"> Success</x-status>
                    @elseif ($student_ordinace_163_1->transaction->status == 'refunded')
                      <x-status type="info">Refunded</x-status>
                    @elseif ($student_ordinace_163_1->transaction->status == 'failed')
                      <x-status type="danger">Failed</x-status>
                    @elseif ($student_ordinace_163_1->transaction->status == 'created')
                      <x-status>Order Created</x-status>
                    @elseif ($student_ordinace_163_1->transaction->status == 'authorized')
                      <x-status type="warning">Processing</x-status>
                    @endif
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163_1->is_applicable == 0)
                    <x-status type="danger">Not Approved</x-status>
                  @else
                    <x-status type="success">Approved</x-status>
                  @endif
                </x-table.td>
                <x-table.td>
                  @if ($student_ordinace_163_1->status == 1)
                    @if ($student_ordinace_163_1->is_fee_paid == 1)
                      <x-dashboard.form-button class="bg-orange-500 h-7 rounded-md border-none" target="_blank" action="{{ route('student.student_print_ordinace_163_form_fee_recipet') }}" name="Fee Reciept">
                        <input type="hidden" name="student_ordinace_163_id" value="{{ $student_ordinace_163_1->id }}">
                      </x-dashboard.form-button>
                    @else
                      <x-dashboard.form-button class="bg-green-500 h-7 rounded-md border-none" action="{{ route('student.student_pay_ordinace_163_form_fee') }}" name="Pay Fee">
                        <input type="hidden" name="student_ordinace_163_id" value="{{ $student_ordinace_163_1->id }}">
                      </x-dashboard.form-button>
                    @endif
                  @endif
                </x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
      </div>
    </section>
  </div>
</div>
