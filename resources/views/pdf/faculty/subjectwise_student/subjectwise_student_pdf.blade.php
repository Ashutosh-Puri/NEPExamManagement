<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjectwise Student Exam Summary</title>
    <style>
        @page {
            margin: 5px 10px 10px 10px;
        }

        .top_fs {
            font-size: 16px;
        }

        table,
        td,
        th {
            border-collapse: collapse;
        }

        .page-number:before {
            content: counter(page);
        }

        th {
            padding: 5px;
            font-size: 12px !important;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .no-border {
            border-color: transparent;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="10" class="left top_fs" style="padding-bottom: 10px;">Report Page: <span class="page-number"></span> of {{ $total_pages}}</td>
            {{-- <td>Page: <span class="page-number"></span> of {{ $total_pages }} </td> --}}
        </tr>
        <tr>
            <td colspan="10" class="center top_fs">Date : {{ date('d-M-Y h:i A', strtotime('now')) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="center top_fs">Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous)
                <br>Sangamner
                <br>(Affiliated to Savitribai Phule Pune University)
            </td>
        </tr>
        <tr>
            <td colspan="10" class="center top_fs bold">{{ get_pattern_class_name($subject_with_students->patternclass_id) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="center top_fs bold">
                {{isset($subject_with_students->studentexamforms->first()->exam->exam_name) ? $subject_with_students->studentexamforms->first()->exam->exam_name : ''}}
            </td>
        </tr>
        <tr>
            <td colspan="1" class="top_fs bold right"></td>
            <td colspan="2" class="top_fs bold center">Sem: {{ $subject_with_students->subject_sem }}</td>
            <td colspan="6" class="center top_fs bold">Subject Code & Name: {{ $subject_with_students->subject_code }} {{ $subject_with_students->subject_name }}</td>
            <td colspan="1" class="top_fs bold right"></td>
        </tr>
    </table>

    <table style="width: 92%; margin: 0 auto;" cellpadding="0">
        <tr>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
            <td width="1%"></td>
        </tr>
        <tr>
            <td class="top_fs bold" style="border: 1px solid #838383;" colspan="6">Sr.No</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="17">PRN / Seat No.</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="21">Name of the Student</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="16">Mobile</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="9">Internal</td>
            <td class="top_fs bold" style="border: 1px solid #838383;" colspan="9">External</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="11">Admission Year</td>
            <td class="top_fs bold center" style="border: 1px solid #838383;" colspan="13">Class</td>
        </tr>
        @php
            $counter=1;
        @endphp
        @forelse ($subject_with_students->studentexamforms as  $subject_student)
            <tr>
                <td class="top_fs center" style="border: 1px solid #838383;" colspan="6">{{ $counter++ }}</td>
                <td class="top_fs center" style="border: 1px solid #838383;" colspan="17">{{ $subject_student->student->prn }}</td>
                <td class="top_fs left" style="border: 1px solid #838383;" colspan="21">{{ $subject_student->student->student_name }}</td>
                <td class="top_fs left" style="border: 1px solid #838383;" colspan="16">{{ $subject_student->student->mobile_no }}</td>
                <td class="top_fs center" style="border: 1px solid #838383;" colspan="9">{{ $subject_student->int_status ? 'YES' : 'NO' }}</td>
                <td class="top_fs center" style="border: 1px solid #838383;" colspan="9">{{ $subject_student->ext_status ? 'YES' : 'NO' }}</td>
                <td class="top_fs left" style="border: 1px solid #838383;" colspan="11">{{ $subject_student->exam->academicyear->year_name }}</td>
                <td class="top_fs left" style="word-break: break-word; border: 1px solid #838383; border-bottom:1px solid #838383;" colspan="13">{{ $subject_student->student->patternclass->courseclass->classyear->classyear_name }} - ({{ $subject_student->student->patternclass->courseclass->course->course_name }})</td>
            </tr>
        @empty
            <tr>
                <td class="top_fs center" style="border: 1px solid #838383;" colspan="102">No Records Found</td>
            </tr>
        @endforelse
    </table>
</body>

</html>
