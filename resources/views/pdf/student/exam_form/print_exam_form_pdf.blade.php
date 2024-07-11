<html>

  <head>
    <meta charset="UTF-8" />
    <title>Exam Form </title>
    <style>
      @page {
        margin: 30px 30px 30px 30px;
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

      table>tbody>tr>td>table>tbody>tr>td,
      th {
        border: 1px dotted gray !important;
        border-collapse: collapse;
        padding: 3px;
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
    </style>
  </head>

  <body>
    <table style="width:100%; border-bottom:1pt  dotted gray; margin-bottom:3px;">
      <tr>
        <td width="10px">
          <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}" style="width: 100px; height: 60px;" />
        </td>
        <td width="300px" align="center"> Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
        <td width="20px">
          <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" style="width: 100px; height: 60px;" />
        </td>
        <td style="width: 150px;border:1px solid black">
          <div style="padding: 5px;">
            <div>Checked By : </div>
            <div>Sign with Date: </div>
          </div>
        </td>
      </tr>
    </table>
    <table width="100%" cellspacing="0">
      <tbody>
        <tr style="border-bottom:1pt  dotted gray; margin-bottom:3px;">
          <td colspan="3">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($exam_form_master->student->memid, 'C128',2,35,array(0,0,0), true) }}" alt="barcode" />
          </td>
          <td colspan="4">
          </td>
          <td colspan="3" style="text-align: right;">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG( "".str_pad($exam_form_master->id, 5, 0, STR_PAD_LEFT)."" , 'C128',2,35,array(0,0,0), true) }}" alt="barcode" />
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <b>Member-ID :</b> {{ $exam_form_master->student->memid }}
          </td>
          <td colspan="4" style="text-align: center;">
            <b>Examination Form :</b> {{ $exam_form_master->exam->exam_name }}
          </td>
          <td colspan="3" style="text-align: right;">Form No : {{ '16060-' . str_pad($exam_form_master->id, 5, 0, STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
          <td align="center" colspan="10">
            <b>Course Name:</b> {{  get_pattern_class_name($exam_form_master->patternclass_id) }}
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <b>PRN:</b>{{ is_null($exam_form_master->student->prn) ? 'Fresh' : $exam_form_master->student->prn }}
          </td>
          <td colspan="4" style="text-align: center;">
            <b>ELIGIBILITY NO.:</b> {{ $exam_form_master->student->eligibilityno }}
          </td>
          <td colspan="3" style="text-align: right;">
            <b>Total Fees to be Paid:</b> <span style="font-family: DejaVu Sans; sans-serif; font-weight:normal">₹</span> {{ ' ' . $exam_form_master->totalfee }}
          </td>
        </tr>
        <tr>
          <td colspan="8">To,</td>
          <td colspan="2" rowspan="4" style="text-align: center;">
            <img style="text-center" height="120" width="100" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(isset($exam_form_master->student->studentprofile->profile_photo_path) ? $exam_form_master->student->studentprofile->profile_photo_path : 'img/no-img.png'))) }}" />
          </td>
        </tr>
        <tr>
          <td colspan="8">Director</td>
        </tr>
        <tr>
          <td colspan="8">Board of Examination & Evaluation ,</td>
        </tr>
        <tr>
          <td colspan="8">Sir / Madam ,</td>
        </tr>
        <tr>
          <td colspan="8">I request permission to present myself at the examination courses, mentioned below.</td>
          <td colspan="2" rowspan="2" style="text-align: center;">
            <img height="40" width="100" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(isset($exam_form_master->student->studentprofile->sign_photo_path) ? $exam_form_master->student->studentprofile->sign_photo_path : 'img/no-img.png'))) }}" />
          </td>
        </tr>
        <tr>
          <td colspan="8">Instructions to the Candidate:</td>
        </tr>
        <tr>
          <td class="inst" colspan="10">1. This Exam form along with fee amount should be submitted to the College</td>
        </tr>
        <tr>
          <td class="inst" colspan="10">2. Repeater students should attach attested true copy of the latest mark sheet alongwith this form</td>
        </tr>
        <tr>
          <td class="inst" colspan="10">3. This form will be considerd ONLY AFTER APPROVAL from the college login.</td>
        </tr>
        @if (isset($flag))
          <tr>
            <td class="inst" style="color:red" colspan="10">4.THIS IS PREVIEW / DRAFT COPY. THIS IS NOT FINAL. PLEASE CLICK ON
              PRINT BUTTON AND THEN DOWNLOAD FINAL COPY. IF YOU DIDN'T
              CLICK ON PRINT BUTTON , EXAM FORM WILL NOT BE ACCEPTED</td>
          </tr>
        @endif
        <tr>
          <td colspan="4">1. Personal Details:</td>
        </tr>
        <tr>
          <td colspan="10">
            <table width="100%" cellspacing="0">
              <tbody>
                <tr>
                  <td>Name of the Applicant</td>
                  <td colspan="3">{{ $exam_form_master->student->student_name }}</td>
                </tr>
                <tr>
                  <td>Name of the Applicant's Mother</td>
                  <td colspan="2">{{ $exam_form_master->student->mother_name }}</td>
                  <td>ABC-ID : {{  $exam_form_master->student->abcid }}</td>
                </tr>
                <tr>
                  <td>Address for Communication</td>
                  <td colspan="3">{{ isset($exam_form_master->student->getpermanentaddress()->address) ? $exam_form_master->student->getpermanentaddress()->address : '' }}</td>
                </tr>
                <tr>
                  <td>Email-ID</td>
                  <td>{{ $exam_form_master->student->email }}</td>
                  <td>Contact Number</td>
                  <td>{{  $exam_form_master->student->mobile_no }}</td>
                </tr>
                <tr>
                  <td>Gender</td>
                  <td>{{ $exam_form_master->student->studentprofile->gender == 'M' ? 'MALE' : 'FEMALE' }}</td>
                  <td>Category</td>
                  <td>
                    {{ $exam_form_master->student->studentprofile->castecategory->caste_category }}
                  </td>
                </tr>
                <tr>
                  <td>Divyang/Learning Disable</td>
                  <td> {{ $exam_form_master->student->studentprofile->is_handicap == 1 ? 'YES' : 'NO' }}</td>
                  <td>Medium of Instruction</td>
                  <td>
                    @if ($exam_form_master->student->examformmasters->last()->medium_instruction === 'H')
                      {{ 'Hindi' }}
                    @elseif ($exam_form_master->student->examformmasters->last()->medium_instruction === 'M')
                      {{ 'Marathi' }}
                    @else
                      {{ 'English' }}
                    @endif
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="10">
            <table width="100%" cellspacing="0">
              <thead>
                <tr>
                  <td colspan="8">2. Applied Subject Information : </td>
                </tr>
                <tr>
                  <th style="width: 3%;">No</th>
                  <th style="width: 2%;">Year / Sem </th>
                  <th style="width: 15%;">Sub Code</th>
                  <th style="width: 40%;">Subject Name</th>
                  <th style="width: 8%;">Internal</th>
                  <th style="width: 8%;">External / Theory</th>
                  <th style="width: 8%;">Practical</th>
                  <th style="width: 8%;">Grade</th>
                  <th style="width: 8%;">Project</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $count=0;
                @endphp
                @foreach ($exam_form_master->studentexamforms->sortByDESC('is_backlog')->sortBy('exam_id') as  $d)
                  <tr>
                    <td align="center">{{  ++$count  }}</td>
                    <td align="center">{{ $d->subject->subject_sem }}</td>
                    <td>{{ $d->subject->subject_code }}</td>
                    <td>{{ $d->subject->subject_name }}</td>
                    @if ($d->subject->subject_type == 'G' || $d->subject->subject_type == 'IG')
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ $d->grade_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'IE')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ $d->ext_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'IEG')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ $d->ext_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'IP' && $d->subject->subjectcategory->subjectcategory != 'Project')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ $d->ext_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'IP' && $d->subject->subjectcategory->subjectcategory == 'Project')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ $d->ext_status == 1 ? 'Y' : 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'I')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                    @if ($d->subject->subject_type == 'IEP')
                      <td align="center">{{ $d->int_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ $d->ext_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ $d->int_practical_status == 1 ? 'Y' : 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                      <td align="center">{{ 'N' }}</td>
                    @endif
                  </tr>
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td  colspan="5">
            <br>
            Regular Subjects : {{ $exam_form_master->studentexamforms->where('is_backlog',0)->count() }}
          </td>
          <td style="text-align: right;" colspan="5">
            <br>
            Backlog Subjects : {{ $exam_form_master->studentexamforms->where('is_backlog',1)->count() }}
          </td>
        </tr>
      </tbody>
    </table>
    @if (!$exam_form_master->studentextracreditexamforms->IsEmpty())
      <div class="footer">
        <table style="width:100%;border-spacing:0">
          <tr>
            <td>Page: <span class="page-number"></span> </td>
            <td align="right">Print Date: {{ now()->format('d / m / Y') }}</td>
          </tr>
        </table>
      </div>
      <div class="page-break">
      </div>
      <div>
        <table style="width:100% ; border: 1px dotted black;" cellspacing="0">
          <tbody>
            <tr>
              <td colspan="8" style=" border: 1px dotted gray;">Extra Credit Subjects </td>
            </tr>
            <tr>
              <th style="width: 5%;">Year / Sem </th>
              <th style="width: 15%;">Sub Code</th>
              <th style="width: 40%;">Subject Name</th>
              <th style="width: 8%;">Internal</th>
              <th style="width: 8%;">External / Theory</th>
              <th style="width: 8%;">Practical</th>
              <th style="width: 8%;">Grade</th>
              <th style="width: 8%;">Project</th>
            </tr>
            @foreach ($exam_form_master->studentextracreditexamforms as $d)
              <tr>
                <td style=" border: 1px dotted gray;" align="center">{{ '-' }}</td>
                <td style="border: 1px dotted gray;" align="center">{{ $d->subject->subject_code }}</td>
                <td style=" border: 1px dotted gray;" align="center"> {{ $d->subject_id == 47 ? $d->subject->subject_name . ' (' . '2' . ' Credits)' : $d->subject->subject_name . ' (' . $d->subject->subject_credit . ' Credits)' }}</td>
                <td style=" border: 1px dotted gray;" align="center">{{ $d->subject->subject_credit }}</td>
                <td style=" border: 1px dotted gray;" align="center">{{ 'N' }}</td>
                <td style=" border: 1px dotted gray;" align="center">{{ 'N' }}</td>
                <td style=" border: 1px dotted gray;" align="center">{{ 'N' }}</td>
                <td style=" border: 1px dotted gray;" align="center">{{ $d->select_status == 1 ? 'Y' : 'N' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
    @endif
    <div class="footer">
      <table style="width:100%;border-spacing:0">
        <tr>
          <td>Page: <span class="page-number"></span> </td>
          <td align="right">Print Date: {{ now()->format('d / m / Y') }}</td>
        </tr>
      </table>
    </div>
    <div class="page-break">
    </div>
    <div>
      <table width="100%">
        <tbody>
          <tr>
            <td colspan="3">3. Fee Details:</td>
          </tr>
          <tr>
            <td width="100%">
              <table width="100%" cellspacing="0">
                <tbody>
                  <tr>
                    <td style="text-align: center;">
                      <b>Examination Form :</b> {{ $exam_form_master->exam->exam_name }}
                    </td >
                    <td style="text-align: center;" colspan="2">Form No : {{ $exam_form_master->id }}</td>
                  </tr>
                  <tr>
                    <td>Fee Type</td>
                    <td style="text-align: right;">Fee Amount</td>
                    <td style="text-align: center;">Remarks</td>
                  </tr>
                  @php
                    $totalfee = 0;
                  @endphp
                  @foreach ($exam_form_master->studentexamformfees->sortBy('examfees_id') as $d)
                    <tr>
                      <td>{{ $d->examfee->fee_name }}</td>
                      <td style="font-weight:normal ; text-align: right; font-family: DejaVu Sans; sans-serif;">{{ INR($d->fee_amount) }}</td>
                      @php
                        $totalfee = $totalfee + $d->fee_amount;
                      @endphp
                      <td style="font-weight:normal ">{{ $d->examfee->remark }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td style="font-family: DejaVu Sans; sans-serif; font-size:20px; font-weight:bold">Total Fee to Be Paid:</td>
                    <td  style="font-family: DejaVu Sans; text-align: right; sans-serif; font-size:20px; font-weight:bold">{{ INR($totalfee)  }}</td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <br><br><br><br>
      <p>
      <h3>Declaration </h3>
      I hereby declare that I have gone through the Syllabus and the list of books prescribed for the examination
      for which I am appearing.I SHALL BE RESPONSIBLE for any errors and wrong or incomplete entries made by me in the examination form .
      <br> I shall not request for special concession such as change in the time and/or day fixed for the examination etc.
      </p>
      <p><b>Note : Special Subjects should be verified by the subject teacher and signed . <br> Please , Select Optional subject(s) carefully , because optional subject(s) are not editable. </b> </p>
      <br>
      Yours faithfully,
      <br><br>

      <table style="width:100%;margin-bottom:2px;">
        <tr>
          <td style="width:33.33%;text-align: left;"></td>
          <td  style="width:33.33%; text-align: center;"></td>
          <td  style="width:33.33%; text-align: center">
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(isset($exam_form_master->student->studentprofile->sign_photo_path) ? $exam_form_master->student->studentprofile->sign_photo_path : 'img/no-img.png'))) }}" style="width: 100px; height: 50px;" />
          </td>
        </tr>
      </table>
      <table style="width:100%;border-spacing:0">
        <tr>
          <td style="width:33.33%;text-align: left;">Place : Sangamner </td>
          <td  style="width:33.33%; text-align: center;"> Date : {{ date('d / m / Y', strtotime($exam_form_master->created_at)) }}</td>
          <td  style="width:33.33%; text-align: right">Signature of the Candidate</td>
        </tr>
      </table>
      <br><br><br>
      <table style="width:100%;border-spacing:0">
        <tr>
          <td style="width:33.33%;text-align: left;">Place : Sangamner </td>
          <td  style="width:33.33%; text-align: center;"> Date : __________________</td>
          <td  style="width:33.33%; text-align: right">Stamp & Signature of the Principal</td>
        </tr>
      </table>
    </div>
    <div class="footer">
      <table style="width:100%;border-spacing:0">
        <tr>
          <td>Page: <span class="page-number"></span> </td>
          <td align="right">Print Date: {{ now()->format('d / m / Y') }}</td>
        </tr>
      </table>
    </div>
    </div>
  </body>

</html>
