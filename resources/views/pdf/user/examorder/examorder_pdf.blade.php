<html>

  <head>
    <meta charset="UTF-8" />
    <title>Exam Order </title>
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

      .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        font-size: 12px !important;
        font-weight: lighter !important;
      }

      .page-number:before {
        content: counter(page);
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
    </style>

  </head>

  <body>

    <table style="border-bottom:1pt solid gray;">
      <tr>
        <td width="10px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px  ;"></td>
        <td width="300px" align="center"> Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
        <td width="20px"><img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" style="width: 100px; height: 60px  ;"></td>
      </tr>
      <tr>
        <td colspan="2"> Web: http://www.sangamnercollege.edu.in </td>
        <td>Phone: 02425-223181</td>
      </tr>

    </table>
    <div style="border-bottom:1pt solid gray;">

      <div style="float:right; font-size: 12px;"> {{ $examorder->created_at->format('d-M-Y') }} </div>
      <div style="clear: left;" />

    </div>

    <div style="  padding-top:10;  padding-bottom: 10px;">
      To,
    </div>
    <div class="bold">

      {{ $examorder->exampanel?->faculty->faculty_name }}

    </div>

    <div style="  text-align: justify;  padding-bottom: 20px;">
      College: {{ $examorder->exampanel->faculty->college?->college_name }}
    </div>

    <div>
      <div style="float:left;" class="bold">Mobile No. :{{ $examorder->exampanel->faculty->mobile_no }}</div>
      <div style="float:right;" class="bold">Email : {{ $examorder->exampanel->faculty->email }}</div>
      <div style="clear: left;"></div>
    </div>

    <div style="margin-top: 25px;">
      Sir/ Madam,
    </div>
    <div style="margin-left: 25px;text-align: justify;">
      The College Authorities are pleased to appoint you as the {{ $examorder->exampanel->examorderpost->post_name }},as per the details below
    </div>
    <table border="1" cellpadding="10" cellspacing="0" width="680px">
      <tr>
        <th>Post Name</th>
        <th>Examination</th>
        <th>Paper / Subject</th>
        <th>Chairman</th>
        <th>Important
          Dates</th>
      </tr>
      <tr>
        <td>
          <div> {{ $examorder->exampanel->examorderpost->post_name }}</div>
          <div>Appt. No.: EC/SCA/ {{ $examorder->id }}</div>
        </td>
        <td>
          <div>
            {{ $examorder->exampanel->subject->patternclass->pattern->pattern_name }}<br>
            {{ $examorder->exampatternclass->patternclass->courseclass->classyear->classyear_name }} {{ $examorder->exampatternclass->patternclass->courseclass->course->course_name }}<br>
            {{ $examorder->exampatternclass->exam->exam_name }}{{ $examorder->exampatternclass->exam->exam_sessions == 1 ? '(FIRST HALF)' : '(SECOND HALF)' }}
          </div>
        </td>
        <td>
          {{ $examorder->exampanel->subject->subject_code }}{{ ' ' . $examorder->exampanel->subject->subject_name }}

        </td>
        <td>
          @php
              $orer_data = $examorder->exampanel->subject->exampanels->where('examorderpost_id', 1)->where('active_status', 1)->first();
          @endphp
          <div>{{ isset($orer_data->faculty->faculty_name)?$orer_data->faculty->faculty_name : '-'; }}</div>
          <div style="font-weight:regular;">{{ isset($orer_data->faculty->college->college_name)?$orer_data->faculty->college->college_name : '-'; }}</div>
          <div> Mob:{{ isset($orer_data->faculty->mobile_no)?$orer_data->faculty->mobile_no : '-'; }}</div>
          <div> Email:{{ isset($orer_data->faculty->email)?$orer_data->faculty->email : '-';  }}</div>
        </td>
        <td>

          <div> From:<br> {{ date('d-M-Y', strtotime($examorder->exampatternclass->papersettingstart_date)) }} </div>
          <div>E-Mode/ Manuscript Submission: <br> {{ date('d-M-Y', strtotime($examorder->exampatternclass->papersubmission_date)) }} </div>

        </td>
      </tr>

    </table>

    <ol style="  text-align: justify;line-height: 1.2;">
      <li>The appointment is based on certain assumptions and subject to the respective provisions of the Maharashtra Public Universities Act, 2016 and Statues/Ordinances, Rules and Regulations framed there under.</li>
      <li>It shall be obligatory on every faculty member of autonomous college to render necessary assistance and services in respect of examinations of the college.</li>
      <li>As per the decision taken by the Board of Examination and Evaluation, the question paper format for different faculties/courses has been attached with this order.</li>
      <li>The paper setter should make three sets of question papers covering 100 percent syllabus across first year to final year programs.</li>
      <li style="  text-align: justify;">Remuneration as prevailing rules and regulations shall be paid to paper setters for paper setting and providing model answers. Chairman of paper setting panel shall collect the bank details of all paper setters in prescribed format and shall provide the same to respective section to process their remuneration. </li>
    </ol>

    <div>
      I seek your co-operation.

    </div>
    <div>
      <div style="float:left;" class="bold">Thanking You </div>
      <div style="float:right;" class="bold">Yours faithfully,</div>

      <div style="clear: right;" />
      <div style="float:right;" class="bold">For CEO,</div>
      <div style="clear: right;" />
      <div style="float:right;" class="bold"> Board of Examination and Evaluation</div>
      <div style="clear: right;" />
    </div>

    <div>
      To,
    </div>
    <div style="line-height: 1.3; font-weight:bold">

      <div>
        The Principal/Directors,
      </div>
      <div style="  text-align: justify;">
        You are requested to relieve the teachers for paper setting. You are also requested to communicate names of the teacher/s who remain absent for the work of paper setting. The said information is required for submission to the College Authorities for necessary action under the provisions of section 48(4) of the Maharashtra Public Universities Act, 2016.
      </div>
      <div style="  text-align: justify; font-weight:normal">

        <p> For early payment of examination remuneration kindly cooperate with the chairman of paper setting panel by providing Bank Account details in the prescribed format.
      </div>

    </div>

    <div style="text-align: justify; font-size: 13px;">
      THIS IS A COMPUTER-GENERATED DOCUMENT AND IT DOES NOT REQUIRE A SIGNATURE. THIS
      DOCUMENT SHALL NOT BE INVALIDATED SOLELY ON THE GROUND THAT IT IS NOT SIGNED.
    </div>
    </div>
    <footer style="text-align: center;line-height: 1.3;padding: 5px;">
      <div style="float:left;"> Page : 1</div>
      <div style="float:right;font-size: 12px;padding: 15px;"> {{ now()->format('d M Y h:i:s A') }}</div>
      <div style="clear: left;" />
      <div style="font-size: 9px;">Designed and developed jointly by the Department of Computer Science and ANVI Software Solutions </div>
    </footer>

  </body>

</html>
