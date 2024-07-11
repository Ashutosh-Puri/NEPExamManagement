<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title> Unfairmeans </title>
    <style>
        @page {
            margin: 15px 60px 10px 60px;
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
            font-size: 12px;
            font-weight: bold
        }

        .inst {
            font-size: 14px !important;
            padding: 0px;
            font-weight: normal;
        }

        table>table td,
        th {
            border: 1px solid gray;
            border-collapse: collapse;
            padding: 5px;
            font-size: 12px !important;

        }

        .rowspace {
            font-size: 14px !important;

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
            <td colspan="2"> Web: https://www.sangamnercollege.edu.in </td>
            <td>Phone: 02425-223181</td>
        </tr>

    </table>
    <div style="border-bottom:1pt solid gray;">

        <div style="float:right; font-size: 12px;">Date:{{(\Carbon\Carbon::now()->format('d-m-Y H:i:s'))}} </div>
        <div style="clear: left;" />

    </div>


    <div>
        Ref. No:Exam/{{$exam->exam_name}}/{{$unfaircases->first()->id}}
    </div>

    <div>
        Shri/Smt : {{$unfaircases->first()->student->student_name}}
    </div>
    {{-- <div>
        Address:{{$unfaircases->first()->student->studentaddress->address}}
    </div> --}}
    <div>
        Mobile No.:{{ $unfaircases->first()->student->mobile_no  }}
    </div>
    <div>
        Email:{{$unfaircases->first()->student->email}}
    </div>
    <p>
        Subject- Show Cause Notice (under clause 10(i) of Ordinance 09)in respect of Unfair means at the
        Autonomous College examination held in {{$exam->exam_name}}

    </p>
    <p>
        Sir/Madam,
    </p>
    <p>

        This is to inform you that the Autonomous College has received a report about unfair means case resorted by you during the examination held in {{$exam->exam_name}}.The details of the unfair means alleged to have been committed by you are given below:-

    </p>
    <table style="border:solid 1px gray; border-collapse:collapse; ">

        <tbody>
            <tr>
                <td style="border:solid 1px gray; padding:5px; ">
                    Case No
                </td>
                <td style="border:solid 1px gray; padding:5px;">
                    {{$unfaircases->first()->id}}
                </td>
            </tr>
            <tr>
                <td style="border:solid 1px gray;padding:5px; ">Examination</td>
                <td style="border:solid 1px gray;padding:5px; ">
                    {{$exam->exam_name}}
                </td>
            </tr>
            <tr>
                <td style="border:solid 1px gray; padding:5px;">Seat Number </td>
                <td style="border:solid 1px gray;padding:5px; "> 
                    {{ $unfaircases->first()->examstudentseatno->seatno }}
                </td>
            </tr>

            <tr>
                <td style="border:solid 1px gray;padding:5px; ">Subject</td>
                <td style="border:solid 1px gray; padding:5px;">
                    @foreach ($unfaircases as  $unfaircase)
                        <span>  {{ $unfaircase->subject->subject_code }} {{ $unfaircase->subject->subject_name }} </span> <br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td style="border:solid 1px gray;padding:5px; ">Nature of Unfair means </td>
                <td style="border:solid 1px gray; padding:5px;">{{"Student took screenshot(s) / used external device to capture photo of question(s) during exam for unfair usage"}}</td>
            </tr>
        </tbody>
    </table>
    <p>
        You are therefore asked to remain present before the said Unfair Means Committee in the {{$unfaircases->first()->unfairmeans->location}} on {{date('d-M-Y', strtotime($unfaircase->unfairmeans->date))}} at {{date('H:i A', strtotime($unfaircase->unfairmeans->time))}} along with your written explaination (as per the enclosed format) to this Show Cause Notice
    </p>
    <p>
        You should also bring your examination hall ticket and college identity card
    </p>
    <p style="text-align:right">
        Yours Faithfully
    </p>
    <p style="text-align:right; margin-right:20px">
        Principal
    </p>
    <div style="text-align: right;">

        <img src="{{'data:image/png;base64,'.base64_encode(@file_get_contents(public_path('img/sign.jpg')))}}" style="width: 140px; height: 80px  ;">
    </div>

    </div>
    <footer style="text-align: center;line-height: 1.3;padding: 5px;">
        <div class="page-number"></div>
        <div style="float:right;font-size: 12px;padding: 15px;"> {{now()->format('d M Y h:i:s A')}}</div>
        <div style="clear: left;" />
        <!-- <div style="font-size: 9px;">Designed and developed jointly by the Department of Computer Science and ANVI Software Solutions </div> -->
    </footer>




</body>

</html>
