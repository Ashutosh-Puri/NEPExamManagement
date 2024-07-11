<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Form's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'all')
    <div>

      <x-card-header heading="All Exam Form's" />
      <x-table.frame>
        {{-- <x-slot:header>
                    <div class="flex gap-x-0.5">
                        <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center  h-10">
                            <x-select-option class="text-start" hidden>Year </x-select-option>
                            @foreach ($academic_years as $a_id)
                                <x-select-option wire:key="{{ $a_id->id }}" value="{{ $a_id->id }}" class="text-start">{{ $a_id->year_name }}</x-select-option>
                            @endforeach
                        </x-input-select>
                        <x-input-select id="exam_id" wire:model.live="exam_id" name="exam_id" class="text-center h-10 ">
                            <x-select-option class="text-start" hidden> Exam </x-select-option>
                            @foreach ($exams as $exam)
                                <x-select-option wire:key="{{ $exam->id }}" value="{{ $exam->id }}" class="text-start">{{ $exam->exam_name }}</x-select-option>
                            @endforeach
                        </x-input-select>
                        <span class="h-10">
                            <x-table.cancel class="mx-0.5 py-0.5 h-10" wire:click='clear()' i="0"> Clear</x-table.cancel>
                        </span>
                    </div>
                </x-slot> --}}
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('exam.academicyear_id')" name="exam.academicyear_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('exam_id')" name="exam_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam</x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Patternclass</x-table.th>
                <x-table.th wire:click="sort_column('inwardstatus')" name="inwardstatus" :sort="$sortColumn" :sort_by="$sortColumnBy">Total Forms</x-table.th>
                <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Inward Forms</x-table.th>
                <x-table.th wire:click="sort_column('inwardstatus')" name="inwardstatus" :sort="$sortColumn" :sort_by="$sortColumnBy">Not Inward Forms</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              {{-- @forelse ($exam_form_masters as $examformmaster)
                                <x-table.tr wire:key="{{ $examformmaster->id }}">
                                    <x-table.td>{{ $examformmaster->id }} </x-table.td>
                                    <x-table.td>{{ isset($examformmaster->exam->academicyear->year_name) ? $examformmaster->exam->academicyear->year_name : '' }} </x-table.td>
                                    <x-table.td>{{ $examformmaster->exam->exam_name }}</x-table.td>
                                    <x-table.td><x-table.text-scroll> {{ isset($examformmaster->patternclass->courseclass->classyear->classyear_name) ? $examformmaster->patternclass->courseclass->classyear->classyear_name : '' }} {{ isset($examformmaster->patternclass->courseclass->course->course_name) ? $examformmaster->patternclass->courseclass->course->course_name : '' }} {{ isset($examformmaster->patternclass->pattern->pattern_name) ? $examformmaster->patternclass->pattern->pattern_name : '' }}</x-table.text-scroll></x-table.td>
                                    <x-table.td></x-table.td>
                                    <x-table.td></x-table.td>
                                    <x-table.td></x-table.td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <x-table.td colspan='7' class="text-center">No Data Found</x-table.td>
                                </x-table.tr>
                            @endforelse --}}

              @forelse ($groupedExamFormMasters as $patternclassId => $examFormMasters)
                <x-table.tr wire:key="{{ $examFormMasters->first()->id }}">

                  <x-table.td>{{ $examFormMasters->first()->id }}</x-table.td>

                  <x-table.td>{{ isset($examFormMasters->first()->exam->academicyear->year_name) ? $examFormMasters->first()->exam->academicyear->year_name : '' }}</x-table.td>

                  <x-table.td>{{ isset($examFormMasters->first()->exam->exam_name) ? $examFormMasters->first()->exam->exam_name : '' }}</x-table.td>

                  <x-table.td>
                    <x-table.text-scroll>
                      {{ isset($examFormMasters->first()->patternclass->courseclass->classyear->classyear_name) ? $examFormMasters->first()->patternclass->courseclass->classyear->classyear_name : '' }}
                      {{ isset($examFormMasters->first()->patternclass->courseclass->course->course_name) ? $examFormMasters->first()->patternclass->courseclass->course->course_name : '' }}
                      {{ isset($examFormMasters->first()->patternclass->pattern->pattern_name) ? $examFormMasters->first()->patternclass->pattern->pattern_name : '' }}
                    </x-table.text-scroll>
                  </x-table.td>

                  <x-table.td>{{ $examFormMasters->count() }}</x-table.td>

                  <x-table.td>{{ $examFormMasters->where('inwardstatus', 1)->count() }}</x-table.td>

                  <x-table.td>{{ $examFormMasters->where('inwardstatus', 0)->count() }}</x-table.td>

                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='7' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          {{-- <x-table.paginate :data="$exam_form_masters" /> --}}
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
