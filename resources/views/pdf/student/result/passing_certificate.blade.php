<html>

  <head>
    <meta charset="UTF-8" />
    <title>Passing Certificate</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>
      @page {
        margin: 180px 60px 04px 60px;
      }

      .page-break {
        overflow: hidden;
        page-break-after: always;
      }

      .page-break:last-child {
        page-break-after: never;
      }

      div:last-child {
        page-break-after: never;
      }

      .header {
        text-align: center;
      }

      td {
        font-size: 13px;
        font-weight: bold
      }

      .rowspace {
        font-size: 5px !important;
      }

      .footer {
        position: fixed;
        left: 0;
        bottom: 40;
        width: 100%;
        font-size: 70%;
      }

      .aa {
        font-size: 13px;
        font-weight: normal
      }

      div {
        line-height: 2.5;
        letter-spacing: 1.5px;
        text-align: justify;
      }

      span {
        font-weight: bold;
      }
    </style>
  </head>

  <body>
    <div class="header space  ">

      @foreach ($student_certificates as $studentseatno)
        <div>
          <div style="font: size 12px; letter-spacing: 0.5px;  line-height: 2.0; text-align: justify;">
            <div>
              <div style="text-indent: 100px;">
                This is to certify that -
              </div>

              {{ $studentseatno->student->studentprofile->gender == 'F' ? 'Smt.' : 'Shri.' }}

              <span>{{ strtoupper($studentseatno->student->student_name) }} </span>
              Mother's Name <span> {{ strtoupper($studentseatno->student->mother_name) }} </span>
              has appeared for the <span>{{ strtoupper($exampatternclass->patternclass->courseclass->course->fullname) }}
                {{ '(' . strtoupper($exampatternclass->patternclass->pattern->pattern_name . ')') }} </span>
              examination held in month of
              <span> {{ strtoupper($exam->exam_name) }}</span> and declared to have
              passed the examination
              @if ($studentseatno->grade != 'Pass')
                with <span>'{{ $studentseatno->grade }}'</span> grade
              @endif
              .
            </div>
            <div style="justify-content: center ;text-indent: 100px;">

              @if (is_null($studentseatno->special_subject))
                This is further to certify that {{ $studentseatno->student->studentprofile->gender == 'F' ? 'she' : 'he' }} is eligible for the
                aforesaid Degree Certificate, whenever {{ $studentseatno->student->studentprofile->gender == 'F' ? 'she' : 'he' }} applies for the
                same at the Savitribai Phule Pune University Convocation.
              @else
                This is further to certify that {{ $studentseatno->student->studentprofile->gender == 'F' ? 'her' : 'his' }} special subject at the said
                examination is <span>{{ strtoupper(str_replace('SPECIAL SUBJECT : ', '', $studentseatno->special_subject)) }}</span>. {{ $studentseatno->student->studentprofile->gender == 'F' ? 'She' : 'He' }} is eligible for the
                aforesaid Degree Certificate, whenever {{ $studentseatno->student->studentprofile->gender == 'F' ? 'she' : 'he' }} applies for the
                same at the Savitribai Phule Pune University Convocation.
              @endif
            </div>
          </div>

          <div> <span> Seat No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>: {{ $studentseatno->seatno ?? '' }}</span></div>
          <div> <span> PERM. REG.NO.</span> <span> :{{ $studentseatno->student->prn }}</span></div>
          <div> <span> College code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>:0030 </span></div>
          <div class="footer">
            <p style="  letter-spacing: 1px;">
              DATE: {{ strtoupper(date('d F Y', strtotime($exampatternclass->result_date))) }} @for ($i = 1; $i <= 60; $i++)
                {!! '&nbsp;' !!}
              @endfor PRINCIPAL </p>
          </div>

          <div class="page-break">
          </div>
      @endforeach
  </body>

</html>
