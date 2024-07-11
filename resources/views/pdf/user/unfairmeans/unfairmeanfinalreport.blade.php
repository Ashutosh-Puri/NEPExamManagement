<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Unfairmeans Report </title>
    <style>
        @page {
            margin: 15px 10px 10px 10px;
        }

        .page-break {
            page-break-after: always;

        }

        div:last-child {
            page-break-after: never;
        }

        .header {
            text-align: center;
        }

        td {
            font-size: 10px;
            font-weight: bold
        }

        .inst {
            font-size: 12px !important;
            padding: 0px;
            font-weight: normal;
        }

        table>table td,
        th {
            border: 1px solid gray;
            border-collapse: collapse;
            padding: 5px;
            font-size: 10px !important;

        }

        .rowspace {
            font-size: 12px !important;

        }


        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;


        }

        .main {
            display: flex;
            justify-content: space-between;
        }

        .alignleft {
            float: left;
        }

        .alignright {
            float: right;
        }

        .bold {
            font-weight: bold;
        }

        .page-number:before {
            content: "Page "counter(page);
        }

        p {
            text-align: justify;
            text-indent: 10px;

        }

    </style>


</head>

<body>

    <table style="border-bottom:1pt solid gray;" width="100%">
        <tr>
            <td width="10px"><img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('img/unipune.png')))}}" style="width: 100px; height: 60px  ;"></td>
            <td width="450px" align="center">
                <div>Shikshan Prasarak Sanstha's</div>
                Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)
            </td>
            <td width="20px"><img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('img/logo.jpg')))}}" style="width: 100px; height: 60px  ;"></td>
        </tr>
        <tr>
            <td colspan="2"> Web: https://www.sangamnercollege.edu.in &nbsp;&nbsp; Phone: 02425-223181</td>
            <td>Date:{{(\Carbon\Carbon::now()->format('d-m-Y'))}}</td>
        </tr>

    </table>

<br><br>

    <div></div>

    <table style="border:solid 1px gray; border-collapse:collapse;width:100%; ">
        <thead>
            <tr>
                <th style="border: solid 1px black;   padding: 15px;">Sr.No</th>
                <th style="border: solid 1px black;   padding: 15px;">Seat No.</th>
                <th style="border: solid 1px black;   padding: 15px;">Class</th>
                <th style="border: solid 1px black;   padding: 15px;">Student Name</th>
                <th style="border: solid 1px black;   padding: 15px;">Subject</th>
                <th style="border: solid 1px black;   padding: 15px;">Punishment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unfaircases as $unfaircase)
            <tr>
                <td style="border: solid 1px black;  padding: 15px;">{{$loop->iteration}}</td>
                <td style="border: solid 1px black;  padding: 15px;">{{$unfaircase->examstudentseatno->seatno}}</td>
                <td style="border: solid 1px black;  padding: 15px;">{{ $unfaircase->exampatternclass->patternclass->courseclass->classyear->classyear_name }}{{$unfaircase->exampatternclass->patternclass->courseclass->course->course_name}}</td>
                <td style="border: solid 1px black;  padding: 15px;">
                    <div>
                        {{$unfaircase->student->student_name}}
                    </div>
                </td>
                <td style="border: solid 1px black;  padding: 15px;">
                    <div>{{$unfaircase->subject->subject_code}} - {{ $unfaircase->subject->subject_name }}</div>
                </td>
                <td style="border: solid 1px black;  padding: 15px;">
                    Fine Rs.{{$unfaircase->punishment}}
                     & Performance of same subject is Cancelled
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer style="text-align: center;line-height: 1.3;padding: 5px;">
        <div class="page-number"></div>
        <div style="float:right;font-size: 12px;padding: 15px;"> {{now()->format('d M Y h:i:s A')}}</div>
        <div style="clear: left;" />
        <!-- <div style="font-size: 9px;">Designed and developed jointly by the Department of Computer Science and ANVI Software Solutions </div> -->
    </footer>




</body>

</html>
