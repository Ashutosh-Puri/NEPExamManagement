<style>
  table,
  td,
  th {
    border-collapse: collapse;
  }
</style>

<table style="width: 100%" cellspacing="0" cellpadding="5" border="1">

  <tr>
    <th align="center" colspan="10">
      Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)
    </th>
  </tr>
  <tr>
    <th align="center" colspan="10">

      @if ($flag != 'Admission')
        {{ $data->first()->exam->exam_name }}
      @endif
    </th>
  </tr>
  <tr>
    <th align="center" colspan="10">
      {{ 'Report Type : ' . $flag }}
    </th>
  </tr>
  <tr>
    <td align="center" colspan="10">
      @if ($flag != 'Admission')
        {{ 'Class Name :  ' . $data->first()->patternclass->courseclass->course->course_name }}
        {{ $data->first()->patternclass->pattern->pattern_name }}
      @else
        {{ 'Class Name :  ' . $data->first()->patternclass->courseclass->course->course_name }}
        {{ $data->first()->patternclass->pattern->pattern_name }}
      @endif
    </td>
  </tr>
  @if ($flag != 'Admission')
    <tr>
      <th>Sr. No.</th>
      <th>Application Id</th>
      <th> PRN</th>
      <th colspan="3">Stud Name </th>
      <th>Mother Name </th>
      <th>Email </th>
      <th>Mobile </th>
      <th>Fee Total</th>
    </tr>
  @else
  <tr>
    <th>Sr. No.</th>
    <th>MEM. Id</th>
    <th>ABC Id</th>
    <th>YEAR</th>
    <th colspan="3">Stud Name </th>
    <th colspan="3">Form Status </th>
  </tr>
  @endif

  @php
    $totfee = 0;
    $studentdata = collect();

    if ($flag != 'Admission') 
    {
        foreach ($data as $examformmaster) 
        {
            $studentdata->add([
                'id' => $examformmaster->id,
                'prn' => ($examformmaster->student->prn == null or $examformmaster->student->prn == '') ? 'Fresh' : $examformmaster->student->prn,
                'student_name' => $examformmaster->student->student_name,
                'mother_name' => strtoupper( $examformmaster->student->mother_name),
                'email' => $examformmaster->student->email,
                'mobile_no' => $examformmaster->student->mobile_no,
                'totalfee' => $examformmaster->totalfee,
            ]);
            $totfee += $examformmaster->totalfee;
        }
    } else 
    {
        foreach ($data as $examformmaster) {
            $studentdata->add([
                'id' => $examformmaster->memid,
                'abcid' => isset($examformmaster->student->abcid)?$examformmaster->student->abcid:'',
                'prn' => $examformmaster->academicyear->year_name,
                'student_name' => $examformmaster->stud_name,
                'mother_name' => $examformmaster->checkexamform(),
                'email' => '',
                'mobile_no' => '',
                'totalfee' => '',
            ]);
        }
    }
  @endphp
  @foreach ($studentdata->sortBy('student_name') as $examformmaster)
    @if ($flag != 'Admission')
    <tr>
      <td align="center">{{ $loop->iteration }}</td>
      <td align="center">{{ $examformmaster['id'] }}</td>
      <td>{{ $examformmaster['prn'] }}</td>
      <td colspan="3">{{ $examformmaster['student_name'] }}</td>
      <td>{{ strtoupper($examformmaster['mother_name']) }}</td>
      <td>{{ $examformmaster['email'] }}</td>
      <td align="center">{{ $examformmaster['mobile_no'] }}</td>
      <td align="center"> {{ isset($examformmaster['totalfee'])?$examformmaster['totalfee']:''; }}</td>
    </tr>
    @else
    <tr>
      <td align="center">{{ $loop->iteration }}</td>
      <td align="center">{{ $examformmaster['id'] }}</td>
      <td align="center">{{ isset($examformmaster['abcid'])?$examformmaster['abcid']:''; }}</td>
      <td>{{ $examformmaster['prn'] }}</td>
      <td colspan="3">{{ $examformmaster['student_name'] }}</td>
      <td colspan="3">{{ strtoupper($examformmaster['mother_name']) }}</td>
    </tr>
    @endif
  @endforeach
  @if ($flag != 'Admission')
    <tr style="font-weight:bold;">
      <td colspan="9" >TOTAL :</td>
      <td align="center">{{ $totfee }}</td>
    </tr>
  @else
    <tr style="font-weight:bold;">
      <td colspan="6">TOTAL EXAM FORM FILLED - </td>
      <td colspan="4"> {{ $studentdata->where('mother_name', 'Filled')->count() }}</td>
    </tr>
    <tr style="font-weight:bold;">
      <td colspan="6">TOTAL EXAM FORM NOT FILLED -</td>
      <td colspan="4"> {{ $studentdata->where('mother_name', 'Not Filled')->count() }}</td>
    </tr>
    <tr style="font-weight:bold;">
      <td colspan="6">TOTAL NOT REGISTERED - </td>
      <td colspan="4">{{ $studentdata->where('mother_name', 'Not Registered')->count() }}</td>
    </tr>
    <tr style="font-weight:bold;">
      <td colspan="6">TOTAL NOT REGISTERED - </td>
      <td colspan="4"> {{ $studentdata->count() }}</td>
    </tr>
  @endif

</table>
