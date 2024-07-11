<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exam Form Fee Receipt</title>
    <style>
      table,
      td,
      th {
        border-collapse: collapse;
      }

      table {
        background-repeat: no-repeat;
        background-size: 30%;
        background-position: center center;
        background-color: rgba(255, 255, 255, 0.5);
        background-image: url('img/shikshan-logo.png');
        opacity: 0.5;
        /* Additional opacity property */
        filter: alpha(opacity=50);
      }
    </style>
  </head>

  <body>
    <table style="width: 100%" cellspacing="0">
      <tr>
        <td style="text-align:center;" colspan="100">
          SHIKSHAN PRASARAK SANSTHA'S
        </td>
      </tr>
      <tr>
        <td style="text-align:center;" colspan="100">
          <strong>ARTS, D.J. MALPANI COMMERCE & B.N. SARDA SCIENCE COLLEGE (AUTONOMOUS)</strong>
        </td>
      </tr>
      <tr>
        <td style="text-align:center;" colspan="100">
          A/p.GHULEWADI SANGAMNER
        </td>
      </tr>
      <tr>
        <td colspan="100">
          <hr style="color:black;" style="margin:0px; padding:0px;">
        </td>
      </tr>
      <tr>
        <td colspan="30" style="text-align: left; ">
          Member Id : <strong>{{ $exam_form_master->student->memid }}</strong>
        </td>
        <td colspan="40" style="text-align: center; ">
          {{ $exam_form_master->exam->exam_name }} Exam Form Fee Receipt
        </td>
        <td colspan="30" style="text-align: right; ">
          Form ID : {{ $exam_form_master->id }}
        </td>
      </tr>
      <tr>
        <td colspan="100" style="text-align: center; border: 1px solid #000;  border-right: 1px solid #000;"">
          <strong>Student Information</strong>
        </td>
      </tr>
      <tr>
        <td colspan="20" style="text-align: right;border: 1px solid #000;  border-right: 1px solid #000;">
          <strong>
            Name :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;border: 1px solid #000;  border-right: 1px solid #000;">
          {{ $exam_form_master->student->student_name }}
        </td>
        <td colspan="20" style="text-align: right;  border: 1px solid #000;">
          <strong>
            PRN :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;  border: 1px solid #000;">
          @if (isset($exam_form_master->student->prn))
            {{ $exam_form_master->student->prn }}
          @else
            N.A.
          @endif <br>
        </td>
      </tr>
      <tr>
        <td colspan="20" style="text-align: right;border: 1px solid #000;  border-right: 1px solid #000;">
          <strong>
            Class :
          </strong>
        </td>
        <td colspan="80" style="text-align: left;border: 1px solid #000;  border-right: 1px solid #000;">
          {{ get_pattern_class_name($exam_form_master->patternclass_id) }}
        </td>
      </tr>
      <tr>
        <td colspan="100" style="text-align: center; border: 1px solid #000;  border-right: 1px solid #000;"">
          <strong> Payment Information</strong>
        </td>
      </tr>
      <tr>
        <td colspan="20" style="text-align: right;border: 1px solid #000;  border-right: 1px solid #000;">
          <strong>
            ID :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;border: 1px solid #000;  border-right: 1px solid #000;">
          @if (isset($exam_form_master->transaction_id))
            @if ($exam_form_master->transaction->status == 'captured')
              <x-status> {{ isset($exam_form_master->transaction->razorpay_payment_id) ? $exam_form_master->transaction->razorpay_payment_id : '' }}</x-status>
            @endif
          @else
          @endif
        </td>
        <td colspan="20" style="text-align: right;  border: 1px solid #000;">
          <strong>
            Date :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;  border: 1px solid #000;">

          @if (isset($exam_form_master->transaction->status) && $exam_form_master->transaction->status == 'captured')
            <x-status> {{ isset($exam_form_master->transaction->payment_date) ? Carbon\Carbon::parse($exam_form_master->transaction->payment_date)->format('d / m / Y') : '' }}</x-status>
          @else
            {{ date('d / m / Y', strtotime($exam_form_master->inwarddate)) }}
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="20" style="text-align: right;border: 1px solid #000;  border-right: 1px solid #000;">
          <strong>
            Method :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;border: 1px solid #000;  border-right: 1px solid #000;">
          @if (isset($exam_form_master->transaction->status) && $exam_form_master->transaction->status == 'captured')
            Online
          @else
            Cash
          @endif
        </td>
        <td colspan="20" style="text-align: right;  border: 1px solid #000;">
          <strong>
            Status :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;  border: 1px solid #000;">
          @if ($exam_form_master->feepaidstatus)
            @if (isset($exam_form_master->transaction->status) && $exam_form_master->transaction->status == 'captured')
              Paid
            @else
              Paid
            @endif
          @else
            Not Paid
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="50" style="text-align: left; border: 1px solid #000;">
          Total Fee : <strong style="font-family: DejaVu Sans; text-align: right; sans-serif; font-size:15px;">{{ INR($exam_form_master->totalfee) }}/-</strong>
        </td>
        <td colspan="50" style="text-align: left; border: 1px solid #000;">
          Paid Fee :
          <strong style="font-family: DejaVu Sans; text-align: right; sans-serif;font-size:15px;">
            @if (isset($exam_form_master->transaction->status) && $exam_form_master->transaction->status == 'captured')
              {{ INR($exam_form_master->transaction->amount) }}/-
            @else
              {{ INR($exam_form_master->totalfee) }}/-
            @endif
          </strong>
        </td>
      </tr>
      <tr>
        <td colspan="15" style="text-align: left; border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;">
          Fees In Words :
        </td>
        <td colspan="85" style="border-right: 1px solid #000;  border-bottom: 1px solid #000; font-family: DejaVu Sans;  sans-serif;">
          <strong>{{ amount_to_word($exam_form_master->totalfee) }}</strong>
        </td>
      </tr>
    </table>

  </body>

</html>
