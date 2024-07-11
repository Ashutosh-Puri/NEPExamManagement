<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preview Marks</title>
    <style>
        table,
        td,
        th {
            border-collapse: collapse;
        }

        @page {
            margin-bottom: 0px;

        }


        .page-break {
            page-break-before: always;

        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: -180px;
            right: 0px;
            height: 150px;
            background-color: lightblue;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            font-size: 11px !important;
            font-weight: lighter !important;
            text-align: right;
            border: 1px solid red;

        }

        table {
            width: 100%;
        }
    </style>
</head>
<body>
    <div>

        <table align="center" style="margin-bottom: 10px;" cellspacing="0" cellpadding="5" border="1">
            <tr>
                <th align="center" colspan="2">
                    Exam:{{ $exam->exam_name }}
                </th>
                <th align="right" colspan="2">
                    Date : {{ date('d-M-Y h:i A', strtotime($intbatch->updated_at)) }}
                </th>
            </tr>
            <tr>
                <th width="10%"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px  ;"></th>
                <th width="300px" align="center" colspan="2"> Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N.
                    Sarada Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</th>
                <th width="10%"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" style="width: 100px; height: 60px  ;"></th>

            </tr>

            <tr>
                <th align="center" colspan="2" style="font-size:12px">
                    {{ $intbatch->exam_patternclass->patternclass->courseclass->class_name }}
                    {{ $intbatch->exam_patternclass->patternclass->pattern->pattern_name }}

                </th>
                <th align="center" colspan="2">
                    {{ 'Batch Id : ' }}

                    {{ $intbatch->created_at->year . $intbatch->subject_id . str_pad($intbatch->id, 5, '0', STR_PAD_LEFT) }}
                </th>
            </tr>

            <tr>
                <th align="center" colspan="2" style="font-size:12px">
                    {{ $intbatch->subject->subject_name }}
                </th>
                <th align="center" colspan="2">
                    {{ $strmsg }}

                    {{ $intbatch->subject->subject_type != 'G' ? 'OUT OF : ' . $outofmarks : '' }}
                </th>
            </tr>

            <tr>
                <th align="center" colspan="4">
                    {{ 'Teacher Name : ' . $intbatch->faculty->faculty_name }}{{ '(' . $intbatch->faculty->mobile_no . ')' }}
                </th>
            </tr>

            <tr style=" background-color:#98AFC7">
                <td>Total Students</td>
                <td>Present Students</td>
                <td>Absent Students</td>
                <td>NA</td>
            </tr>

            <tr>
                <td>{{ $intbatch->totalBatchsize }}</td>
                <td>{{ $intbatch->totalMarksentry }}</td>
                <td>{{ $intbatch->totalAbsent }}</td>
                <td>{{ '-' }}</td>
            </tr>
        </table>


        @for ($i = 0; $i < $n; $i += 5)
            @if ($i <= $n - 5)
                <table class="marks" border=1 cellspacing="0" cellpadding="6" align="center">
                @else
                    <table style="width:40% ;border:1px black solid;" border=1 cellspacing="0" cellpadding="6" align="left">
            @endif
            @for ($j = 0; $j < 20; $j += 1)

                <tr style="border:1px black solid;">
                    @if ($i < $n && $j < count($a[$i]))
                        @if (isset($a[$i][$j]))
                            <td style=" border:1px black solid; text-align: center; ">
                                @if ($j === 0)
                                    <div style="font-weight:12px !important">
                                        No.=>Marks
                                    </div>
                                @endif {{ $a[$i][$j] ?? '' }}
                            </td>
                        @endif
                    @endif

                    @if ($i + 1 < $n && $j < count($a[$i + 1]))
                        @if (isset($a[$i + 1][$j]))
                            <td style="border:1px black solid;text-align: center;  ">
                                @if ($j === 0)
                                    <div>
                                        Seat No.=>Marks
                                    </div>
                                @endif {{ $a[$i + 1][$j] ?? '' }}

                            </td>
                        @endif
                    @endif


                    @if ($i + 2 < $n && $j < count($a[$i + 2]))
                        @if (isset($a[$i + 2][$j]))
                            <td style="border:1px black solid;text-align: center;  ">
                                @if ($j === 0)
                                    <div>
                                        Seat No.=>Marks
                                    </div>
                                @endif {{ $a[$i + 2][$j] ?? '' }}
                            </td>
                        @endif
                    @endif

                    @if ($i + 3 < $n && $j < count($a[$i + 3]))
                        @if (isset($a[$i + 3][$j]))
                            <td style="border:1px black solid;text-align: center;  ">
                                @if ($j === 0)
                                    <div>
                                        Seat No.=>Marks
                                    </div>
                                @endif {{ $a[$i + 3][$j] ?? '' }}
                            </td>
                        @endif
                    @endif

                    @if ($i + 4 < $n && $j < count($a[$i + 4]))
                        @if (isset($a[$i + 4][$j]))
                            <td style="border:1px black solid;text-align: center;  ">
                                @if ($j === 0)
                                    <div>
                                        Seat No.=>Marks
                                    </div>
                                @endif {{ $a[$i + 4][$j] ?? '' }}
                            </td>
                        @endif
                    @endif
                </tr>
            @endfor
        </table>
        @if ($i < $n - 5)
        </div>
        <div class="page-break"></div>
        @endif
    @endfor
    <p style="text-align:right;">
        Stamp & Authorized Signatory
    </p>
</body>
</html>
