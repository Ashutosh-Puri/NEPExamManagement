<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font-family: 'Noto Sans', sans-serif;
            font-style: normal;
            font-weight: 400;
        }

        .page {
            padding: 2cm;
            border: 1px solid #333;
            background: white;
        }

        p {
            text-align: justify;
        }

    </style>

</head>
<title>Performance Cancel Report</title>
<body>
    @foreach($unfaircases as $unfaircase)
    <div class="page" style="padding-top: 160px;">

        <div >
            जा.क्र./एस.सी/परीक्षा/{{$exam->exam_name}}/{{$unfaircase->id}}
            <p>
                प्रति,
                <div>
                    Mr./ Miss {{$unfaircase->student->student_name}}
                </div>
                {{-- <div>
     {{$unfaircase->students->studentaddress->address}}
        </div> --}}
        </p><br>

        <div >
            <b> विषय:- " प्रथम वर्ष कला, वाणिज्य, विज्ञान, विज्ञान संगणक, बी. व्होक " {{$exam->exam_name}} परीक्षा गैरप्रकाराबाबत. </b>

        </div><br>

        <div>
            पुणे विद्यापीठ परिपत्रक क्र. १८३/२०११ व विद्यापीठ परीक्षा गैरप्रकार परिनियम ९.

        </div>
        <p>
            महाविद्यालयाने गठीत केलेली परीक्षा गैरप्रकार चौकशी समितीचा अहवाल दि.{{date('d/M/Y', (strtotime($unfaircase->unfairmeans->date)))}}

        </p>
        <p style="text-indent: 30px;">
            वरील विषयाच्या अनुषंगाने आपण महाविद्यालयातील परीक्षा गैरप्रकार चौकशी समिती समोर दि. {{date('d/M/Y', (strtotime($unfaircase->unfairmeans->time)))}} रोजी समक्ष उपस्थित राहून दिलेल्या साक्षीनुसार संदर्भाधिन नियमानुसार खालीलप्रमाणे आपल्यावर दंड व शिक्षेची कारवाई करण्यात आलेली आहे.

        </p>
        <p>
            १) दंड रु. {{$unfaircase->punishment}}/- (रुपये @switch($unfaircase->punishment)
            @case(1000)
            एक
            @break;
            @case(2000)
            दोन
            @break;
            @case(3000)
            तीन
            @break;
            @case(4000)
            चार
            @break;
            @case(5000)
            पाच
            @break;
            @case(6000)
            सहा
            @break;
            @case(7000)
            सात
            @break;
            @endswitch
            हजार मात्र )
        </p>
        <p>
            २) वर्ग-{{ $unfaircase->exampatternclass->patternclass->courseclass->classyear->classyear_name }}{{$unfaircase->exampatternclass->patternclass->courseclass->course->course_name}}
            {{-- @foreach($unfaircase->subjects() as $subject)  --}}
            <span>{{$unfaircase->subject->subject_code}} - {{ $unfaircase->subject->subject_name }}</span>
            {{-- @endforeach --}}
            विषयाचा पेपर ( performance ) रद्द करण्यात येत आहे.

        </p>
        <p style="text-indent: 30px;">
            वरील दंडाची रक्कम दि. {{date('d/M/Y', strtotime("+4 day", strtotime($unfaircase->unfairmeans->date)))}} पुर्वी महाविद्यालयाच्या अकौंट विभागात जमा करावी. तसेच दंड भरल्याच्या पावतीची सत्यप्रत परीक्षा विभागात जमा करावी, अन्यथा आपणास संबंधित वर्गाचे गुणपत्रक मिळणार नाही. याची नोंद घ्यावी.
        </p>
        <p>
            कळावे,
        </p>
        <p style="text-align: right;">आपला विश्वासू,
        </p>
        <div style="text-align: right;">
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/sign.jpg'))) }}" style="width: 140px; height: 80px;">
        </div>
    </div>
    </div>
    @endforeach

</body>
</html>
