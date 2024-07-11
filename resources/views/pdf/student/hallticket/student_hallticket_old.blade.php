<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Ticket</title>
    <style>
      body {
        background-image: url('{{ public_path('img/logo-20.png') }}');
        background-size: 100px 100px;
        background-repeat: repeat;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1pt solid gray;
        padding: 10px;
      }

      table,
      td,
      th {
        border-collapse: collapse;
      }

      td {
        font-size: 10px;
        font-weight: bold
      }

      .page-break {
        page-break-after: always;
      }

      .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        font-size: 70%;
      }
    </style>
  </head>

  <body>
    <section>
      <div style="height: 86%;">
        <table align="center" cellspacing="0" cellpadding="5" style="border-bottom:1pt solid gray;">
          <tr>
            <td width="10px">
              <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 50px  ;">
            </td>
            <td width="450px" align="center"> Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarada Science College (Autonomous), Sangamner, Dist.-Ahmednagar-422605 <br> (Affiliated to Savitribai Phule Pune University)</td>
            <td width="20px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" style="width: 100px; height: 50px  ;"></td>
          </tr>

          <tr>
            <td align="center" colspan="3">
              {{ 'Hall Ticket For ' . get_pattern_class_name($examseatno->exampatternclass->patternclass_id) }} {{ $examseatno->exampatternclass->exam->exam_name }}
            </td>
          </tr>
        </table>

        <table align="center" border="1" width="100%">
          <tr>
            <th colspan="1">Seat No</th>
            <th colspan="1"> PRN</th>
            <th colspan="1">College Code</th>
            <th colspan="1"> PUN CODE</th>
          </tr>
          <tr>
            <td align="center" colspan="1">{{ $examseatno->seatno }}</td>
            <td align="center" colspan="1">{{ $examseatno->prn }}</td>
            <td align="center" colspan="1">{{ '030' }}</td>
            <td align="center" colspan="1">{{ 'CAAA016060' }}</td>
          </tr>
        </table>

        <div style="margin-top:5px;">
          <div style="float:left;width: 100%;height:80;"><b>Student Name : </b> {{ Str::upper($examseatno->student->student_name) }}
            <div style="margin-top:20px;"><b>Mother Name :</b> {{ Str::upper($examseatno->student->mother_name) }}</div>
          </div>
          <div style="float:right;" class="bold"><img style="border:2px solid black;" height="100" width="80" src="{{ 'data:image/png;base64,' . base64_encode(@file_get_contents(public_path($examseatno->student->studentprofile->profile_photo_path))) }}"></img></div>
          <div style="clear: left;"></div>
        </div>

        <table align="center" cellspacing="0" cellpadding="5" border="1" width="100%">
          <tr>
            <td align="center" style="width:5%"><b> Sem </b></td>
            <td align="center"style="width:10%"><b> Subject Code </b></td>
            <td align="center"style="width:10%"><b> Subject Prefix </b></td>
            <td align="center" style="width:40%"><b> Subject Name </b></td>
            <td align="center" style="width:5%"><b> Type </b></td>
            <td align="left" style="width:20%"><b> Exam Date </b></td>
            <td align="center" style="width:20%"><b> Exam Time </b></td>
          </tr>
          @foreach ($sortdata as $examform)
            <tr>
              <td align="center"><b> {{ $examform['subject_sem'] }} </b></td>
              <td align="center"><b> {{ $examform['subject_code'] }} </b></td>
              <td align="center"><b> {{ $examform['subject_prefix'] ?? '-' }} </b></td>
              <td align="left"><b> {{ $examform['subject_name'] }} </b></td>

              @php
                $str = '';
                if ($examform['int_status'] == 1) {
                    $str = 'I';
                }
                if ($examform['ext_status'] == '1') {
                    if ($examform['subject_type'] == 'IP') {
                        $str = $str . 'P';
                    } else {
                        $str = $str . 'E';
                    }
                }
                if ($examform['int_practical_status'] == '1') {
                    $str = $str . 'P';
                }
                if ($examform['subject_code'] == 'GR. 4B') {
                    $str = 'I';
                }
                if ($examform['subject_type'] == 'IG') {
                    $str = 'IG';
                } elseif ($examform['subject_type'] == 'G') {
                    $str = 'G';
                } elseif ($examform['subject_type'] == 'IEG') {
                    $str = $str . 'G';
                }

              @endphp
              <td align="center"><b> {{ $str }} </b></td>



              @if ($examform['subject_type'] == 'IEG' && ($str == 'IE' || $str == 'I' || $str == 'E'))
                <td align="center"><b>{{ '-' }}</b></td>
                <td align="center"><b>{{ '@Department' }}</b></td>
              @else
                @if (strpos($examform['subject_prefix'], 'VSC') !== false && ($str == 'IP' || $str == 'I' || $str == 'P'))
                  @if (!is_null($examform))
                    <td align="left"><b>  @if (is_null($examform['examdate']))  {{ '-' }}   @else  {{ date('l, d-m-Y', strtotime($examform['examdate'])) }}   @endif  </b></td>
                    <td align="center"><b> {{ $examform['timeslot'] }} {{ '@Department' }}</b></td>
                  @endif
                @else
                  @if (strpos($examform['subject_prefix'], 'VSC') !== false && $str == 'I')
                    @if (!is_null($examform))
                      <td align="center"><b>{{ '-' }}</b></td>
                      <td align="center"><b>{{ '@Department' }}</b></td>
                    @endif
                  @else
                    @if ($str == 'IP' || $str == 'I' || $str == 'IG' || $str == 'G' || $str == 'P' || $str == 'IEG')
                      <td align="center"><b>{{ '-' }}</b></td>
                      <td align="center"><b>{{ $str == 'G' ? '@Gymkhana Department' : '@Department' }}</b></td>
                    @else
                      @if (!is_null($examform))
                        <td align="left"><b>  @if (is_null($examform['examdate'])) {{ '-' }}  @else  {{ date('l, d-m-Y', strtotime($examform['examdate'])) }}  @endif  </b></td>
                        <td align="center"><b> {{ $examform['timeslot'] }} </b></td>
                      @endif
                    @endif
                  @endif
                @endif
              @endif
            </tr>
          @endforeach
        </table>

        <div>
          <b style="text-align:justify ; font-size: 12px;color:black;">NOTE:</b> <br>
          @foreach ($instructions as $instruction)
            <p style="text-align:justify ; font-size: 12px;color:black;"> {{ $instruction->instruction_name }} </p>
          @endforeach
          @if (isset($examseatno->exampatternclass->patternclass->pattern->pattern_valid))
            <b style="text-align:justify ; font-size: 12px;color:black;">Last Attempt For this Course Valid Upto {{ $examseatno->exampatternclass->patternclass->pattern->pattern_valid }}</b>
          @endif
        </div>

      </div>
      <div>
        <table style="margin-top: auto; " width="100%" align="center" cellspacing="0" cellpadding="20">
          <tr>
            <td align="left">
              <div>
                <img src="{{ 'data:image/png;base64,' . base64_encode(@file_get_contents(public_path($examseatno->student->studentprofile->sign_photo_path))) }}" style="width: 140px; height: 60px  ;">
                <p>{{ 'Signature of Student' }}</p>
              </div>
            </td>
            <td align="right">
              <div>
                <img src="{{ 'data:image/png;base64,' . base64_encode(@file_get_contents(public_path('img/sign.jpg'))) }}" style="width: 140px; height: 60px  ;">
                <p style="margin-right: 60px;">{{ 'PRINCIPAL' }}</p>
              </div>
            </td>
          </tr>
        </table>
        <div class="footer">
          <p align="right " style="margin-right: 20px;"> {{ date('d-m-Y h:m A', strtotime(Carbon\Carbon::now())) }}</p>
        </div>
      </div>
    </section>
  </body>

</html>
