<style>
  @page {
    margin: 15px 60px 10px 60px;
    overflow: hidden;
  }

  .page-break {
    page-break-inside: avoid;
    overflow: hidden;
    page-break-after: always;
  }

  table,
  td,
  th {
    border: 1px solid gray;
    border-collapse: collapse;
    padding: 5px;
    font-size: 12px !important;
  }
</style>
@foreach ($pages as $allpages)
  <div style="text-align: center; font-weight:bold;color:gray; ">
    Sealbag Report- {{ $allpages->first()->first()->exampatternclass->capmaster->cap_name ?? '' }} Date : {{ $allpages->first()->first()->examdate ?? '' }}
  </div>
  <table style="border-bottom:1pt solid gray;width:100%; ">
    <tr>
      <td width="10px">
        <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px  ;">
      </td>
      <td width="650px" align="center" colspan="3"> Sangamner Nagarpalika Arts, D. J. Malpani Commerce and B. N.
        Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
      <td width="20px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png'))) }}" style="width: 100px; height: 60px  ;"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        Examination:{{ $allpages->first()->first()->exampatternclass->exam->exam_name ?? '' }}
      </td>
      <td colspan="3" align="center">
        College Code:30
      </td>
    </tr>
  </table>

  @foreach ($allpages as $page)
    <table style="border-bottom:1pt solid gray; width:680px">
      <thead>
        <tr>
          <th>Sr.No.</th>
          <th>Date</th>
          <th>Examination / Class</th>
          <th>Subject Name</th>
          <th>Total Student</th>
          <th>Total Bag</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($page->where('total_present', '!=', '0') as $pagedata)
          <tr>
            <td style=" text-align:  center;"> {{ $loop->iteration }}</td>
            <td>
              <div> {{ $pagedata->examdate }} </div>
              <div>
                {{ $pagedata->timetableslot->timeslot }}
              </div>
            </td>
            <td>
              {{ $pagedata->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $pagedata->exampatternclass->patternclass->courseclass->course->course_name }}
            </td>
            <td>
              {{ $pagedata->subject->subject_code ?? '' }} {{ $pagedata->subject->subject_name ?? '' }}
            </td>
            <td>
              {{ $pagedata->total_present ?? '' }}
            </td>
            <td></td>
          </tr>
        @endforeach
        <tr>
          <td colspan="4"style=" text-align:  center;">
            <h4>TOTAL</h4>
          </td>
          <td> {{ $page->sum('total_present') }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  @endforeach
  <div class="page-break"></div>
@endforeach
@php
  $cnt = 1;
@endphp
@foreach ($pages as $pagedata)
  @foreach ($pagedata as $page)
    @foreach ($page as $p)
      <table style="border-bottom:1pt solid gray; margin-bottom :80px;">
        <tr>
          <td width="10px">
            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px  ;">
          </td>
          <td width="650px" align="center" colspan="3"> Sangamner Nagarpalika Arts, D. J. Malpani Commerce and B. N.
            Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
          <td width="20px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png'))) }}" style="width: 100px; height: 60px  ;"></td>
        </tr>
        <tr>
          <th colspan="5" style="align-items: center;">
            CAP ACKNOWLEDGEMENT REPORT

          </th>
        </tr>
        <tr>
          <td colspan="2"> <b>Examination:</b><span style="font-style: normal;">{{ $p->exampatternclass->exam->exam_name ?? '' }}</span> </td>
          <td><b>Class Name : </b><span style="font-style: normal;"> {{ $p->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $p->exampatternclass->patternclass->courseclass->course->course_name }} </span> </td>
          <td colspan="2"><b>SUBJECT : </b>{{ $p->subject->subject_code ?? '' }} {{ $p->subject->subject_name ?? '' }}</td>
        </tr>
        <tr>
          <td colspan="5"></td>
        </tr>
        <tr>
          <td colspan="3">Date : {{ date('d-M-Y', strtotime($p->examdate)) }}</td>
          <td colspan="2">{{ $p->timetableslot->timeslot }} </td>
        </tr>
        <tr>
        <tr>
          <th>Block No.</th>
          <th> </th>
          <th> </th>
          <th> </th>
          <th> </th>
        </tr>
        <tr>
          <th>No. of Answer Books</th>
          <th> </th>
          <th> </th>
          <th> </th>
          <th> </th>
        </tr>
        <tr>
          <td colspan="5"></td>
        </tr>
        <tr>
          <th>Block No.</th>
          <th> </th>
          <th> </th>
          <th> </th>
          <th> </th>
        </tr>
        <tr>
          <th>No. of Answer Books</th>
          <th> </th>
          <th> </th>
          <th> </th>
          <th> </th>
        </tr>
        <tr>
          <td colspan="5"></td>
        </tr>
        <tr>
        <tr>
          <th>TOTAL No. of Answer Books</th>
          <th colspan="4"></th>
        </tr>
        <tr>
          <td colspan="5"></td>
        </tr>
        <th colspan="2" style="height:60px"> </th>
        <th colspan="3"> Signature of Senior Supervisor</th>
        </tr>
      </table>
      @if ($cnt == 2)
        <div class="page-break"> </div>
        @php $cnt=1; @endphp
      @else
        @php $cnt++; @endphp
      @endif
    @endforeach
  @endforeach
@endforeach
