{{-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Block Allocation</title>
  <style>
    table,
    td,
    th {
      border-collapse: collapse;
      border: 1px solid #000000;
    }
  
    td {
      font-size: 10px;
      font-weight: bold;
    }
  
    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body>
  <table align="center" cellspacing="0" cellpadding="5" style="border-bottom:1pt solid gray;">
    <tr>
      <td align="center" colspan="4"> Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
    </tr>
    <tr>
      <td align="center" colspan="4">
        {{ 'EXAM ' . $exam->exam_name }}
      </td>
    </tr>
    <tr>
      <td colspan="2">Exam Date : {{ date('d/m/Y', strtotime($examdate)) }}</td>
      <td  colspan="2" align="right">Time Slot : {{ $time_slot }}</td>
    </tr>
  </table>
  
  <table cellspacing="0" cellpadding="5" border="1">
    <tr>
      <th>Block</th>
      <th>Class</th>
      <th>Subject and Seatno </th>
      <th>Total</th>
    </tr>
  
    @foreach ($exam_time_tables as $exam_time_table)
      @foreach ($exam_time_table->exampatternclass->blockallocations->where('subject_id', $exam_time_table->subject_id)->where('exampatternclass_id', $exam_time_table->exam_patternclasses_id) as $block)
        <tr>
          <td align="center">{{ $block->classroom->class_name }} ( {{ $block->classroom->noofbenches }} ) </td>
          <td align="center">
            {{ $exam_time_table->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $exam_time_table->exampatternclass->patternclass->courseclass->course->course_name }} {{ $exam_time_table->exampatternclass->patternclass->pattern->pattern_name }}
          </td>
          <td align="center  word-wrap:break-word;">
            {{ '( SEM: ' . $exam_time_table->subject->subject_sem . ')  ' }} {{ $exam_time_table->subject->subject_code }}{{ ' : ' . $exam_time_table->subject->subject_name }}
          </td>
        </tr>
        <tr>
          <td></td>
          <td align="center">{{ $time_slot }}</td>
          <td align="left">
            {{ $block->studentblockallocations->pluck('seatno')->implode(', ') }}
          </td>
          <td>
            {{ $block->studentblockallocations->pluck('seatno')->count() }}
          </td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      @endforeach
    @endforeach
    <tr>
      <td align="right" colspan="3">Printed Date : {{ date('d-M-Y h:i A', strtotime(now())) }}</td>
    </tr>
  </table>
</body>
</html>
 --}}
 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Block Allocation</title>
   <style>
     table, th, td {
       border: 1px solid #000;
       border-collapse: collapse;
     }
 
     th, td {
       padding: 5px;
       text-align: center;
       font-size: 10px;
       font-weight: bold;
     }
 
     .page-break {
       page-break-after: always;
     }
 
     .header-table {
       border-bottom: 1pt solid gray;
       margin-bottom: 10px;
     }
 
     .header-table td {
       border: none;
       font-weight: normal;
     }
 
     .center-text {
       text-align: center;
     }
 
     .right-text {
       text-align: right;
     }
   </style>
 </head>
 <body>
   <table align="center" width="100%">
     <tr>
       <td class="center-text" colspan="4">
         Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br>
         (Affiliated to Savitribai Phule Pune University)
       </td>
     </tr>
     <tr>
       <td class="center-text" colspan="4">
         {{ 'EXAM ' . $exam->exam_name }}
       </td>
     </tr>
     <tr>
       <td colspan="2">Exam Date : {{ date('d/m/Y', strtotime($examdate)) }}</td>
       <td colspan="2" class="right-text">Time Slot : {{ $time_slot }}</td>
     </tr>
   </table>
 
   <table align="center" width="100%">
     <tr>
       <th>Block</th>
       <th>Class</th>
       <th>Subject and Seatno</th>
       <th>Total</th>
     </tr>
 
     @foreach ($exam_time_tables as $exam_time_table)
       @foreach ($exam_time_table->exampatternclass->blockallocations->where('subject_id', $exam_time_table->subject_id)->where('exampatternclass_id', $exam_time_table->exam_patternclasses_id) as $block)
         <tr>
           <td>{{ $block->classroom->class_name }} ({{ $block->classroom->noofbenches }})</td>
           <td>
             {{ $exam_time_table->exampatternclass->patternclass->courseclass->classyear->classyear_name }} 
             {{ $exam_time_table->exampatternclass->patternclass->courseclass->course->course_name }} 
             {{ $exam_time_table->exampatternclass->patternclass->pattern->pattern_name }}
           </td>
           <td style="word-wrap:break-word;">
             {{ '(SEM: ' . $exam_time_table->subject->subject_sem . ') ' }} 
             {{ $exam_time_table->subject->subject_code }}: 
             {{ $exam_time_table->subject->subject_name }}
           </td>
           <td>
             {{ $block->studentblockallocations->pluck('seatno')->count() }}
           </td>
         </tr>
         <tr>
           <td></td>
           <td>{{ $time_slot }}</td>
           <td class="left-text">
             {{ $block->studentblockallocations->pluck('seatno')->implode(', ') }}
           </td>
           <td></td>
         </tr>
       @endforeach
     @endforeach
 
     <tr>
       <td colspan="4" class="right-text">Printed Date : {{ date('d-M-Y h:i A', strtotime(now())) }}</td>
     </tr>
   </table>
 </body>
 </html>
 