<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Question Paper Set's Report" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Question Paper Set's Report" />
  <x-table.frame p="0"  s="0">
    <x-slot:body>
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th rowspan="2">No</x-table.th>
            <x-table.th rowspan="2">SEM</x-table.th>
            <x-table.th rowspan="2">Pattern Class</x-table.th>
            <x-table.th rowspan="2">Subject Code</x-table.th>
            <x-table.th rowspan="2">Subject Name</x-table.th>
            <x-table.th rowspan="2">Total Sets</x-table.th>
            <x-table.th colspan="{{ count($papersets) + 1 }}" class="text-center">Not Used Sets</x-table.th>
            <x-table.th rowspan="2">Sets Used</x-table.th>
            <x-table.th rowspan="2">Total Students</x-table.th>
          </x-table.tr>
          <x-table.tr>
            @foreach ($papersets as $set)
              <x-table.th>Set {{ $set->set_name }}</x-table.th>
            @endforeach
            <x-table.th>Total</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @php
            $rowcount=0;
          @endphp
          @forelse ($exam_form_masters as  $examformmaster)
            @foreach ($examformmaster->studentexamforms()->get() as $studentexamform)
              <x-table.tr wire:key="{{ $studentexamform->id }}">
                <x-table.td>{{  ++$rowcount }} </x-table.td>
                <x-table.td>{{ $studentexamform->subject->subject_sem }} </x-table.td>
                <x-table.td> <x-table.text-scroll> {{ isset($examformmaster->patternclass->courseclass->classyear->classyear_name) ? $examformmaster->patternclass->courseclass->classyear->classyear_name : '' }} {{ isset($examformmaster->patternclass->courseclass->course->course_name) ? $examformmaster->patternclass->courseclass->course->course_name : '' }} {{ isset($examformmaster->patternclass->pattern->pattern_name) ? $examformmaster->patternclass->pattern->pattern_name : '' }}</x-table.text-scroll></x-table.td>
                <x-table.td>{{ $studentexamform->subject->subject_code }} </x-table.td>
                <x-table.td> <x-table.text-scroll class="!w-90">{{ $studentexamform->subject->subject_name }} </x-table.text-scroll></x-table.td>
                @php
                  $papersubmission = App\Models\Papersubmission::where('exam_id', $exam->id)
                      ->where('subject_id', $studentexamform->subject_id)
                      ->first();
                @endphp
                <x-table.td>
                  @php
                    if ($papersubmission) {
                        $set_count = $papersubmission->questionbanks()->count();
                    } else {
                        $set_count = 0;
                    }
                  @endphp
                  {{ $set_count }}
                </x-table.td>
                @foreach ($papersets as $set)
                  <x-table.td>
                    @if ($papersubmission)
                      @php
                        $is_useed = $papersubmission
                            ->questionbanks()
                            ->where('set_id', $set->id)
                            ->first();
                      @endphp
                      @if ($is_useed)
                        @if ($is_useed->is_used == 1)
                          N
                        @else
                          Y
                        @endif
                      @else
                        x
                      @endif
                    @else
                      x
                    @endif
                  </x-table.td>
                @endforeach
                <x-table.td>
                  @php
                    if ($papersubmission) {
                        $set_not_used_count = $papersubmission->questionbanks()->where('is_used', 0)->count();
                    } else {
                        $set_not_used_count = 0;
                    }
                  @endphp
                  {{ $set_not_used_count }}
                </x-table.td>
                <x-table.td>
                  @php
                    if ($papersubmission) {
                        $set_used_count = $papersubmission->questionbanks()->where('is_used', 1)->count();
                    } else {
                        $set_used_count = 0;
                    }
                  @endphp
                  {{ $set_used_count }}
                </x-table.td>
                <x-table.td>
                  @php
                   $studd_count= App\Models\Studentexamform::whereIn('examformmaster_id', $exam_form_masters->pluck('id'))->distinct('subject_id')->where('subject_id', $studentexamform->subject_id)->distinct('student_id')->count('student_id');
                  @endphp
                  {{  $studd_count }}
                </x-table.td>
              </x-table.tr>
            @endforeach
          @empty
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </x-slot>
    {{-- <x-slot:footer>
      <x-table.paginate :data="$exam_form_masters" />
    </x-slot> --}}
  </x-table.frame>
</div>
