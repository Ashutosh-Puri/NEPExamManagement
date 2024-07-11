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
    @foreach ($pages as $page)
      @php
        $c = 0;
      @endphp
      @foreach ($page['barcode'] as $barcodes)
        <div style="text-align: center; font-weight:bold;color:gray">
          Mark List
        </div>
        <table style="border-bottom:1pt solid gray; ">
          <tr>
            <td width="10px">
              <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px  ;">
            </td>
            <td width="650px" align="center" colspan="3"> Sangamner Nagarpalika Arts, D. J. Malpani Commerce and B. N.
              Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
            <td width="20px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png'))) }}" style="width: 100px; height: 60px  ;"></td>
          </tr>
          <tr>
            <td colspan="3"> 
              {{ $page['subject']->subject->patternclass->courseclass->classyear->classyear_name }}
              {{ $page['subject']->subject->patternclass->courseclass->course->course_name }}
              {{ $page['subject']->subject->patternclass->pattern->pattern_name }} 
              <span> Exam: {{ $page['subject']->exampatternclass->exam->exam_name }}</span></td>
              <td>Lot No:
              {{ $page['barcode'][$loop->iteration - 1]->first()->paperassesment_id }}
            </td>
            <td>Page No:{{ $loop->iteration }} /{{ $page['barcode']->count() }}</td>
          </tr>
          <tr>
            <td colspan="4" align="center">Subject: {{ $page['subject']->subject->subject_code }}-{{ $page['subject']->subject->subject_name }}
              Semester: {{ $page['subject']->subject->subject_sem }}
            </td>
            <td>Max. Marks: {{ $page['subject']->subject->subject_maxmarks_ext }} </td>
          </tr>
        </table>
        <table style="border-bottom:1pt solid gray; width:680px">
          <thead>
            <th>Sr.No.</th>
            <th>Barcode No.</th>
            <th>Examiner Marks</th>
            <th>Moderator Marks</th>
            <th></th>
            <th>Sr.No.</th>
            <th>Barcode No.</th>
            <th>Examiner Marks</th>
            <th>Moderator Marks</th>
          </thead>
          <tbody>
            @for ($i = 0; $i < 30; $i++)
              <tr>
                <td style=" text-align:  center;">{{ sprintf('%02d', $i + 1) }}</td>
                <td style=" text-align:  center;">
                  {{ $barcodes[$c + $i]->id ?? '' }}
                </td>
                <td style=" text-align:  center;">
                  @if (isset($barcodes[$c + $i]))
                    {{ $barcodes[$c + $i]->status == 1 || $barcodes[$c + $i]->status == 2 ? 'AB' : '' }}
                  @endif
                </td>
                <td></td>
                <td>
                </td>
                <td style=" text-align:  center;">{{ sprintf('%02d', $i + 31) }}</td>
                <td style=" text-align:  center;">
                  @php
                    $cnt = $c + $i;
                    if (isset($barcodes[$cnt])) {
                        $barcodes[$cnt]->update(['paperassesment_id' => $page['barcode'][$loop->iteration - 1]->first()->paperassesment_id]);
                    }
                    if (isset($barcodes[$cnt + 30])) {
                        $barcodes[$cnt + 30]->update(['paperassesment_id' => $page['barcode'][$loop->iteration - 1]->first()->paperassesment_id]);
                    }
                  @endphp
                  {{ $barcodes[$c + $i + 30]->id ?? '' }}
                </td>
                <td style=" text-align:  center;">
                  @if (isset($barcodes[$c + $i + 30]))
                    {{ $barcodes[$c + $i + 30]->status == 1 || $barcodes[$c + $i + 30]->status == 2 ? 'AB' : '' }}
                  @endif
                </td>
                <td>
                </td>
              </tr>
            @endfor
            @php
              $c += 60;
            @endphp
          </tbody>
          <tfoot style="font-weight: bold;">
            <tr>
              <td align="left" colspan="4">
                <div>
                  Name &
                </div>
                <div> Sign of Examiner</div>
              </td>
              <td align="left" colspan="5">
                <div>
                  Name &
                </div>
                <div>Sign of Moderator </div>
              </td>
            </tr>
            <tr>
            </tr>
            <tr>
              <td colspan="4"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No. of Paper Assessed </td>
              <td colspan="2">
                @if ($page['subject']->subject->subject_maxmarks_ext == 25)
                  (03-10)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 30)
                  (05-11)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 50)
                  (10-19)
                @endif

                @if ($page['subject']->subject->subject_maxmarks_ext == 60)
                  (15- 23)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 70)
                  (20- 27)
                @endif
                <div>100%</div>
              </td>
              <td colspan="1">
                @if ($page['subject']->subject->subject_maxmarks_ext == 25)
                  (11-12)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 30)
                  (12-18)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 50)
                  (20-24)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 60)
                  (24- 29)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 70)
                  (28- 33)
                @endif
                <div>
                  5%
                </div>
              </td>
              <td colspan="1">
                @if ($page['subject']->subject->subject_maxmarks_ext == 25)
                  (13-25)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 30)
                  (19-30)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 50)
                  (25-50)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 60)
                  (30- 60)
                @endif
                @if ($page['subject']->subject->subject_maxmarks_ext == 70)
                  (34- 70)
                @endif
                <div>100%</div>
              </td>
              <td colspan="1">No. of Paper Moderated</td>
            </tr>
            <tr>
              <td colspan="4">Total </td>
              <td colspan="2"> </td>
              <td colspan="1"> </td>
              <td colspan="1"> </td>
              <td colspan="1"> </td>
            </tr>
          </tfoot>
        </table>
        <div class="page-break"></div>
      @endforeach
    @endforeach
