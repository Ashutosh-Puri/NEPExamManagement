<table>
    <thead>
      <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">SEM</th>
        <th rowspan="2">Pattern Class</th>
        <th rowspan="2">Subject Code</th>
        <th rowspan="2">Subject Name</th>
        <th rowspan="2">Total Sets</th>
        <th colspan="{{ count($papersets) + 1 }}" class="text-center">Not Used Sets</th>
        <th rowspan="2">Sets Used</th>
        <th rowspan="2">Total Students</th>
      </tr>
      <tr>
        @foreach ($papersets as $set)
          <th>Set {{ $set->set_name }}</th>
        @endforeach
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($exam_form_masters as $key => $examformmaster)
        @foreach ($examformmaster->studentexamforms()->get() as $key2 => $studentexamform)
          <tr>
            <td>{{ $key2 + 1 }} </td>
            <td>{{ $studentexamform->subject->subject_sem }} </td>
            <td> </td>
            <td>{{ $studentexamform->subject->subject_code }} </td>
            <td>{{ $studentexamform->subject->subject_name }} </td>
            @php
              $papersubmission = App\Models\Papersubmission::where('exam_id', $exam->id)
                  ->where('subject_id', $studentexamform->subject_id)
                  ->first();
            @endphp
            <td>
              @php
                if ($papersubmission) {
                    $set_count = $papersubmission->questionbanks()->count();
                } else {
                    $set_count = 0;
                }
              @endphp
              {{ $set_count }}
            </td>
            @foreach ($papersets as $set)
              <td>
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
                    &nbsp;
                  @endif
                @else
                  &nbsp;
                @endif
              </td>
            @endforeach
            <td>
              @php
                if ($papersubmission) {
                    $set_not_used_count = $papersubmission->questionbanks()->where('is_used', 0)->count();
                } else {
                    $set_not_used_count = 0;
                }
              @endphp
              {{ $set_not_used_count }}
            </td>
            <td>
              @php
                if ($papersubmission) {
                    $set_used_count = $papersubmission->questionbanks()->where('is_used', 1)->count();
                } else {
                    $set_used_count = 0;
                }
              @endphp
              {{ $set_used_count }}
            </td>
            <td>
              {{ $studd_count= App\Models\Studentexamform::whereIn('examformmaster_id', $exam_form_masters->pluck('id'))->distinct('subject_id')->where('subject_id', $studentexamform->subject_id)->distinct('student_id')->count('student_id') }}
            </td>
          </tr>
        @endforeach
      @empty
      @endforelse
    </tbody>
  </table>