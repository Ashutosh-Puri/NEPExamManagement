<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table,
        td,
        th {
            border-collapse: collapse;
        }

        section {
            opacity: 0.2;
            background-image: url('{{ public_path('img/logo-20.png') }}');
            background-size: 200px 200px;
            background-repeat: repeat;
            background-color: rgba(255, 255, 255, 0.5);
        }

    </style>
    <title>Seat Number</title>
</head>
<body>
    <section>
        <div style="text-align: center;">

            <table align="center" cellspacing="0" cellpadding="5" border="1">
                <tr>
                    <th><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px;"></th>
                    <th align="center" colspan="3">Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</th>
                    <th><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo-50.png'))) }}" style="width: 100px; height: 60px;"></th>
                </tr>
                <tr>
                    <th align="center" colspan="4">
                        {{ $examseatnodata->first()->exampatternclass->patternclass->courseclass->classyear->classyear_name }}
                        {{ $examseatnodata->first()->exampatternclass->patternclass->courseclass->course->course_name }}
                        {{ $examseatnodata->first()->exampatternclass->patternclass->pattern->pattern_name }}
                    </th>
                    <th>Date : {{ date('d-M-Y h:i A', strtotime(now()))}}</th>
                </tr>
                <tr>
                    <th align="center" colspan="5">
                        {{"Examination : "}}{{$examseatnodata->first()->exampatternclass->exam->exam_name}}
                    </th>
                </tr>
                <tr>
                    <td align="center" colspan="5">{{""}}</td>
                </tr>
                <tr>
                    <th>Sr. No.</th>
                    <th>Seatno</th>
                    <th>Stud Name</th>
                    <th>Mother Name</th>
                    <th colspan="1">PRN</th> <!-- Use colspan to extend border -->
                </tr>
                @foreach($examseatnodata->sortBy('seatno') as $examseatno)
                <tr>
                    <td align="center">{{$loop->iteration}}</td>
                    <td width="20%" align="center">{{$examseatno->seatno}}</td>
                    <td width="30%">{{ $examseatno->student->student_name}}</td>
                    {{-- <td width="10%">{{ $examseatno->student->student_name}}</td> --}}
                    <td width="20%">{{ $examseatno->student->mother_name}}</td>
                    <td width="20%">{{ $examseatno->prn}}</td>
                </tr>
                @endforeach
            </table>

        </div>
    </section>

</body>
</html>
