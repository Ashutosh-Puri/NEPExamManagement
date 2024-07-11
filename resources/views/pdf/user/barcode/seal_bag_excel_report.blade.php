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
      <table style="border-bottom:1pt solid gray; ">
        <tr>
          <td></td>
          <td colspan="4">Sealbag Report- {{ $allpages->first()->first()->exampatternclass->capmaster->cap_name ?? '' }} Date : {{ $allpages->first()->first()->examdate }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td width="10px"></td>
          <td width="650px" align="center" colspan="3"> Sangamner Nagarpalika Arts, D. J. Malpani Commerce and B. N.
            Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
          <td width="20px"></td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            Examination:{{ $allpages->first()->first()->exampatternclass->exam->exam_name }}
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
              <th width="30px">Sr.No.</th>
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
                <td> {{ $loop->iteration }}</td>
                <td>
                  {{ $pagedata->examdate }}
                  {{ $pagedata->timetableslot->timeslot }}
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
