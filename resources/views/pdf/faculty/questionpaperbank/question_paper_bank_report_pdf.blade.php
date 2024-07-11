<style>
  table,
  td,
  th {
    border-collapse: collapse;
  }
</style>

<table style="width: 100%" cellspacing="0" cellpadding="5" border="1">
  <thead>
    <tr>
      <th align="center" colspan="100">
        Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)
      </th>
    </tr>
    <tr>
      <th  align="left" colspan="60">
        Faculty : {{ auth()->guard('faculty')->user()->faculty_name }}
      </th>
      <th  align="left" colspan="40">
        Exam : {{ $exam->exam_name }}
      </th>
    </tr>
    <tr>
      <th align="center" colspan="100">
        Report : Question Paper Bank Report
      </th>
    </tr>
    <tr>
      <th align="left" colspan="10" >No</th>
      <th align="left" colspan="50" >Subject</th>
      <th align="left" colspan="10" >Sets</th>
      <th align="left" colspan="30" >Set Names</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($papersubmissions as $key => $papersubmission)
      <tr>
        <td colspan="10" style="text-align-left; ">{{ $key+1 }} </td>
        <td colspan="50" style="text-align-left; ">{{ $papersubmission->subject->subject_code }} {{ $papersubmission->subject->subject_name }} </td>
        <td colspan="10" style="text-align-left; ">{{ $papersubmission->noofsets }} </td>
        <td colspan="30" style="text-align-left; ">
          @foreach ($papersubmission->questionbanks()->get() as $k => $ss)
            @if ($k)
              ,
            @endif {{ $ss->paperset->set_name }}
          @endforeach
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
