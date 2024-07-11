<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Timetable</title>
    <style>
      @page {
        margin: 15px 60px 10px 60px;
      }

      .header {
        text-align: center;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
      }

      th {
        padding: 5px;
        font-size: 12px !important;
        text-align: center;
      }

      td {
        border: 1px solid black;
        border-width: 1.5px;
        font-weight: bold;
        ;
        padding: 5px;
        font-size: 12px;
        text-align: center;
      }

      .center-content {
        text-align: center;
        margin-bottom: 20px;
      }

      .center-content-text {
        font-weight: bold;
        text-decoration: underline;
      }

      .list-container {
        text-align: justify;
        line-height: 1;
      }

      .list-container li {
        margin-bottom: 10px;
      }

      .bold {
        font-weight: bold;
      }

      .no-border {
        border-color: transparent;
      }

      .image-container {
        text-align: center;
        margin-top: 20px;
      }

      .image-container img {
        width: 140px;
        height: 80px;
        margin: 0 10px;
      }

      .left-image {
        float: left;
      }

      .right-image {
        float: right;
      }
    </style>
  </head>

  <body>
    <table>
      <tr>
        <th><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px;"></th>
        <th class="bold" colspan="2" style="white-space: nowrap; line-height: 1.5;">Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner
          <br> (Affiliated to Savitribai Phule Pune University)
          <br>Examination of {{ $exam->first()->exam_name }}
          <br>EXAMINATION CIRCULAR NO. 217 OF 2024
          <br>{{ $name }}

        <th><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" style="width: 100px; height: 80px;"></th>
      </tr>
    </table>
    <table>
      <thead>
        <tr>
          <th style="border: 2px solid black; border-width: 1.5px; font-weight: bold;">Day & Date</th>
          <th style="border: 2px solid black; border-width: 1.5px; font-weight: bold;">Time</th>
          <th style="border: 2px solid black; border-width: 1.5px; font-weight: bold;">Subject Code</th>
          <th style="border: 2px solid black; border-width: 1.5px; font-weight: bold;">Subject</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($exam_time_table_data as $row)
          <tr>
            <td>{{ date('l', strtotime($row->examdate)) }} <br>
              {{ date('j/n/Y', strtotime($row->examdate)) }}</td>
            <td>{{ $row->timetableslot->timeslot }}</td>
            <td>{{ $row->subject->subject_code }}</td>
            <td>{{ $row->subject->subject_name }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="list-container bold">
      <ol>
        <b style="text-align:justify ; font-size: 12px;color:black;">INSTRUCTIONS FOR CANDIDATES:</b> <br><br>
        @foreach ($instructions as $key => $instruction)
          <li style="text-align:justify ; font-size: 12px;color:black;"> {{ $instruction->instruction_name }} </li>
        @endforeach
        @if (isset($exampatternclass->patternclass->pattern->pattern_valid))
          <li><b style="text-align:justify ; font-size: 12px;color:black;">Last Attempt For this Course Valid Upto {{ $exampatternclass->patternclass->pattern->pattern_valid }}</b></li>
        @endif
      </ol>
    </div>

    <br>
    <div class="image-container">
      @if (isset($ceo->sign_photo_path))
        <img class="left-image" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path($ceo->sign_photo_path))) }}">
      @endif
      @if (isset($principal->sign_photo_path))
        <img class="right-image" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path($principal->sign_photo_path))) }}">
      @endif
    </div>
  </body>
</html>
