<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Inward Exam Form" />
  </x-breadcrumb.breadcrumb>
  @if ($page == 1)
    <div>
      <x-card-header heading="Exam Form Regular Inward Process" />
      <x-form wire:submit="inward_class_form()">
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
          <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Exam Form Regular Inward Process <x-spinner />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-1">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="course_id" :value="__('Select Course')" />
              <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
                @forelse ($courses as $course)
                  <x-select-option wire:key="{{ $course->id }}" value="{{ $course->id }}" class="text-start"> {{ $course->course_name ?? '-' }} </x-select-option>
                @empty
                  <x-select-option class="text-start">Course's Not Found</x-select-option>
                @endforelse
              </x-input-select>
              <x-input-error :messages="$errors->get('course_id')" class="mt-1" />
            </div>
            <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="patternclass_id" :value="__('Select Class')" />
              <x-input-select id="patternclass_id" wire:model="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
                <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                @forelse ($pattern_classes as $pattern_class)
                  <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start"> {{ $pattern_class->classyear_name }} {{ $pattern_class->course_name }}  {{ $pattern_class->pattern_name }} </x-select-option>
                @empty
                  <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                @endforelse
              </x-input-select>
              <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3">
              @if ($list_by == 'o')
                <div class="px-5 py-2 col-span-1 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="application_id" :value="__('Enter Application ID')" />
                  <x-text-input id="application_id" type="text" wire:model="application_id" placeholder="{{ __('Enter Application ID') }}" name="application_id" class="w-full mt-1" :value="old('application_id', $application_id)" autocomplete="application_id" />
                  <x-input-error :messages="$errors->get('application_id')" class="mt-1" />
                </div>
              @endif
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <br>
                <x-input-radio id="list_by" value="o" wire:model.live="list_by" name="list_by" />
                <x-input-label for="list_by" class="inline mb-1 mx-2" :value="__('One By One')" />
                <x-input-radio id="list_by_2" value="m" wire:model.live="list_by" name="list_by" />
                <x-input-label for="list_by_2" class="inline mb-1 mx-2" :value="__('By List')" />
                <x-input-error :messages="$errors->get('list_by')" class="mt-2" />
              </div>
            </div>
          </div>
          <x-form-btn wire:loading.attr="disabled">Next</x-form-btn>
        </div>
      </x-form>
    </div>
  @elseif($page == 2)
    <div>
      <x-card-header :heading="get_pattern_class_name($patternclass_id) . ' Inward Exam Form'">
        <x-status type="button" class="py-1 mx-2" wire:click='setpage(1)' type="success" wire:loading.attr="disabled">Back</x-status>
        <x-status type="button" class="py-1 mx-2" wire:click='resetinput()' type="danger" wire:loading.attr="disabled">Cancel</x-status>
      </x-card-header>
      <x-table.frame x="0">
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th>No.</x-table.th>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">Application ID</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student Name</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">PRN</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Eligibility No</x-table.th>
                <x-table.th wire:click="sort_column('created_at')" name="created_at" :sort="$sortColumn" :sort_by="$sortColumnBy">Date</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($exam_form_masters as $key => $examformmaster)
                <x-table.tr wire:key="{{ $examformmaster->id }}">
                  <x-table.td>{{ $key + 1 }} </x-table.td>
                  <x-table.td>{{ $examformmaster->id }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($examformmaster->student->student_name) ? $examformmaster->student->student_name : '' }} </x-table.text-scroll> </x-table.td>
                  <x-table.td>{{ $examformmaster->student->prn }} </x-table.td>
                  <x-table.td>{{ $examformmaster->student->eligibilityno   }} </x-table.td>
                  <x-table.td>{{ isset($examformmaster->created_at) ? $examformmaster->created_at->format('Y-m-d') : '' }} </x-table.td>
                  <x-table.td>
                    <x-spinner class="!float-start mx-2 h-8" wire:target='procced_to_approve({{ $examformmaster->id }})' />
                    <x-table.delete wire:loading.attr='disabled' i="0" wire:target='procced_to_approve({{ $examformmaster->id }})' wire:click="procced_to_approve({{ $examformmaster->id }})">Proceed For Approve</x-table.delete>
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan="7">
                    <p class="text-center">No Records Found</p>
                  </x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        @if ($this->list_by == 'm')
          <x-slot:footer>
            <x-table.paginate :data="$exam_form_masters" />
          </x-slot>
        @endif
      </x-table.frame>
      <x-table.frame a="0">
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.td colspan="7">
                  <p class="text-center">Inwarded Exam Forms</p>
                </x-table.td>
              </x-table.tr>
              <x-table.tr>
                <x-table.th>No.</x-table.th>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">Application ID</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student Name</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">PRN</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Eligibility No</x-table.th>
                <x-table.th wire:click="sort_column('created_at')" name="created_at" :sort="$sortColumn" :sort_by="$sortColumnBy">Date</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($exam_form_master_inwards as $key => $examformmaster_inward)
                <x-table.tr wire:key="{{ $examformmaster_inward->id }}">
                  <x-table.td>{{ $key + 1 }} </x-table.td>
                  <x-table.td>{{ $examformmaster_inward->id }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($examformmaster_inward->student->student_name) ? $examformmaster_inward->student->student_name : '' }} </x-table.text-scroll> </x-table.td>
                  <x-table.td>{{ $examformmaster_inward->student->prn }} </x-table.td>
                  <x-table.td>{{ $examformmaster_inward->student->eligibilityno  }} </x-table.td>
                  <x-table.td>{{ isset($examformmaster_inward->created_at) ? $examformmaster_inward->created_at->format('Y-m-d') : '' }} </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan="7">
                    <p class="text-center">No Records Found</p>
                  </x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        @if ($this->list_by == 'm')
          <x-slot:footer>
            <x-table.paginate :data="$exam_form_master_inwards" />
          </x-slot>
        @endif
      </x-table.frame>
    </div>
  @elseif($page == 3)
    <div>
      <x-form wire:submit="inward_form({{ $inward_id }})">
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
          <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Exam Form Regular Inward Process <x-spinner />
          </div>
          <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
              Student Details
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="student_name" :value="__('Student Name')" />
                <x-input-show id="student_name" :value="$student_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="mother_name" :value="__('Mother Name')" />
                <x-input-show id="mother_name" :value="$mother_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="email" :value="__('Course Name')" />
                <x-input-show id="email" :value="$course_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="mobile_no" :value="__('Member ID')" />
                <x-input-show id="mobile_no" :value="$memid" />
              </div>
            </div>
          </div>
          <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
              Fee Details
            </div>
            <div class="overflow-x-scroll">
              <x-table.table style="width: 100%;">
                <x-table.thead>
                  <x-table.tr>
                    <x-table.th style="width:50%;">Head</x-table.th>
                    <x-table.th class=" w-20 text-end">Approved Fee</x-table.th>
                    <x-table.th style="width:30%;">Remark</x-table.th>
                  </x-table.tr>
                  @php
                    $total = 0;
                  @endphp
                </x-table.thead>
                <x-table.tbody>
                  @forelse ($student_exam_form_fees as $key => $examformfee)
                    <x-table.tr wire:key="{{ $examformfee->id }}">
                      <x-table.td>{{ $examformfee->examfee->fee_name }} </x-table.td>
                      <x-table.td class=" w-20 text-end">{{ INR($examformfee->fee_amount) }} </x-table.td>
                      <x-table.td>{{ $examformfee->examfee->remark }} </x-table.td>
                    </x-table.tr>
                    @php
                      $total = $total + $examformfee->fee_amount;
                    @endphp
                  @empty
                    <x-table.tr>
                      <x-table.td colspan="7">
                        <p class="text-center">No Records Found</p>
                      </x-table.td>
                    </x-table.tr>
                  @endforelse
                  <x-table.tr>
                    <x-table.td class="font-bold text-red-500">Total </x-table.td>
                    <x-table.td class="font-bold text-red-500 w-20 text-end">{{ INR($total) }} </x-table.td>
                    <x-table.td class="font-bold text-red-500"></x-table.td>
                  </x-table.tr>
                </x-table.tbody>
              </x-table.table>
            </div>
          </div>
          <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
              Subject Details
            </div>
            <div class="overflow-x-scroll">
              <x-table.table>
                <x-table.thead>
                  <x-table.tr>
                    <x-table.th colspan="9">
                      <p class="text-center">Regular & Backlog Subjects</p>
                    </x-table.th>
                  </x-table.tr>
                  <x-table.tr>
                    <x-table.th>No</x-table.th>
                    <x-table.th>Year / Sem</x-table.th>
                    <x-table.th>Subject Code</x-table.th>
                    <x-table.th>Subject Name</x-table.th>
                    <x-table.th>Internal</x-table.th>
                    <x-table.th>External / Theory </x-table.th>
                    <x-table.th>Practical </x-table.th>
                    <x-table.th>Grade </x-table.th>
                    <x-table.th>Project </x-table.th>
                  </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                  @php
                    $count=0;
                  @endphp
                  @forelse ($student_exam_form_subjects->sortByDESC('is_backlog')->sortBy('exam_id') as $examformsubject)
                    <x-table.tr wire:key="{{ $examformsubject->id }}">
                      <x-table.td>{{ ++$count }} </x-table.td>
                      <x-table.td>{{ $examformsubject->subject->subject_sem }} </x-table.td>
                      <x-table.td>{{ $examformsubject->subject->subject_code }} </x-table.td>
                      <x-table.td>{{ $examformsubject->subject->subject_name }} </x-table.td>

                      @if ($examformsubject->subject->subject_type == 'G' || $examformsubject->subject->subject_type == 'IG')
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->grade_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'IE')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->ext_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'IEG')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->ext_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'IP' && $examformsubject->subject->subjectcategory->subjectcategory != 'Project')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->ext_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'IP' && $examformsubject->subject->subjectcategory->subjectcategory == 'Project')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->ext_status == 1 ? 'Y' : 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'I')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                      @if ($examformsubject->subject->subject_type == 'IEP')
                        <x-table.td>{{ $examformsubject->int_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->ext_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ $examformsubject->int_practical_status == 1 ? 'Y' : 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                      @endif
                    </x-table.tr>
                  @empty
                    <x-table.tr>
                      <x-table.td colspan="9">
                        <p class="text-center">No Records Found</p>
                      </x-table.td>
                    </x-table.tr>
                  @endforelse
                  <x-table.tr>
                    <x-table.td colspan="4">
                      Regular Subjects : {{ $student_exam_form_subjects->where('is_backlog',0)->count() }}
                    </x-table.td>
                    <x-table.td colspan="4">
                      Backlog Subjects :  {{ $student_exam_form_subjects->where('is_backlog',1)->count() }}
                    </x-table.td>
                  </x-table.tr>
                </x-table.tbody>
              </x-table.table>
              @if (!$student_exam_form_extrcredit_subjects->isEmpty())
                <x-table.table>
                  <x-table.thead>
                    <x-table.tr>
                      <x-table.th colspan="8">
                        <p class="text-center">Extra Credit Subjects</p>
                      </x-table.th>
                    </x-table.tr>
                    <x-table.tr>
                      <x-table.th>No</x-table.th>
                      <x-table.th>Year / Sem</x-table.th>
                      <x-table.th>Subject Code</x-table.th>
                      <x-table.th>Subject Name</x-table.th>
                      <x-table.th>Internal</x-table.th>
                      <x-table.th>External / Theory </x-table.th>
                      <x-table.th>Practical </x-table.th>
                      <x-table.th>Grade </x-table.th>
                    </x-table.tr>
                  </x-table.thead>
                  <x-table.tbody>
                    @forelse ($student_exam_form_extrcredit_subjects as $no2 => $examformextrcreditsubject)
                      <x-table.tr wire:key="{{ $examformextrcreditsubject->id }}">
                        <x-table.td>{{ $no2 + 1 }} </x-table.td>
                        <x-table.td>{{ '-' }} </x-table.td>
                        <x-table.td>{{ $examformextrcreditsubject->subject->subject_code }} </x-table.td>
                        <x-table.td>{{ $examformextrcreditsubject->subject->subject_name }} </x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ 'N' }}</x-table.td>
                        <x-table.td>{{ $examformextrcreditsubject->subject->select_status == 1 ? 'Y' : 'N' }}</x-table.td>
                      </x-table.tr>
                    @empty
                      <x-table.tr>
                        <x-table.td colspan="8">
                          <p class="text-center">No Records Found</p>
                        </x-table.td>
                      </x-table.tr>
                    @endforelse
                  </x-table.tbody>
                </x-table.table>
              @endif
            </div>
          </div>
          <x-form-btn>Inward Exam Form</x-form-btn>
          <x-form-btn type="button" wire:click='verify({{ $inward_id }})'>Verify For Online Payment</x-form-btn>
          <x-form-btn type="button" wire:click='resetinput()'>Cancel</x-form-btn>
          @if ($this->list_by == 'o')
            <x-form-btn type="button" wire:click='setpage(1)'>Back</x-form-btn>
          @else
            <x-form-btn type="button" wire:click='setpage(2)'>Back</x-form-btn>
          @endif
        </div>
      </x-form>
    </div>
  @endif
</div>
