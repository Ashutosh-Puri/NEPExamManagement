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
          Member Id : <strong>{{ $studentordinace163->student->memid }}</strong>
        </td>
        <td colspan="40" style="text-align: center; ">
          {{ $studentordinace163->exam->exam_name }} Ordinace 163 Form Fee Receipt
        </td>
        <td colspan="30" style="text-align: right; ">
          Form ID : {{ $studentordinace163->id }}
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
          {{ $studentordinace163->student->student_name }}
        </td>
        <td colspan="20" style="text-align: right;  border: 1px solid #000;">
          <strong>
            PRN :
          </strong>
        </td>
        <td colspan="30" style="text-align: left;  border: 1px solid #000;">
          @if (isset($studentordinace163->student->prn))
            {{ $studentordinace163->student->prn }}
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
          {{ get_pattern_class_name($studentordinace163->patternclass_id) }}
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
          @if (isset($studentordinace163->transaction_id))
            @if ($studentordinace163->transaction->status == 'captured')
              <x-status> {{ isset($studentordinace163->transaction->razorpay_payment_id) ? $studentordinace163->transaction->razorpay_payment_id : '' }}</x-status>
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

          @if (isset($studentordinace163->transaction->status) && $studentordinace163->transaction->status == 'captured')
            <x-status> {{ isset($studentordinace163->transaction->payment_date) ? Carbon\Carbon::parse($studentordinace163->transaction->payment_date)->format('d / m / Y') : '' }}</x-status>
          @else
            {{ date('d / m / Y', strtotime($studentordinace163->payment_date)) }}
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
          @if (isset($studentordinace163->transaction->status) && $studentordinace163->transaction->status == 'captured')
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
          @if ($studentordinace163->is_fee_paid)
            @if (isset($studentordinace163->transaction->status) && $studentordinace163->transaction->status == 'captured')
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
          Total Fee : <strong style="font-family: DejaVu Sans; text-align: right; sans-serif; font-size:15px;">{{ INR($studentordinace163->fee) }}/-</strong>
        </td>
        <td colspan="50" style="text-align: left; border: 1px solid #000;">
          Paid Fee :
          <strong style="font-family: DejaVu Sans; text-align: right; sans-serif;font-size:15px;">
            @if (isset($studentordinace163->transaction->status) && $studentordinace163->transaction->status == 'captured')
              {{ INR($studentordinace163->transaction->amount) }}/-
            @else
              {{ INR($studentordinace163->fee) }}/-
            @endif
          </strong>
        </td>
      </tr>
      <tr>
        <td colspan="15" style="text-align: left; border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;">
          Fees In Words :
        </td>
        <td colspan="85" style="border-right: 1px solid #000;  border-bottom: 1px solid #000; font-family: DejaVu Sans;  sans-serif;">
          <strong>{{ amount_to_word($studentordinace163->fee) }}</strong>
        </td>
      </tr>
    </table>

  </body>

</html>
