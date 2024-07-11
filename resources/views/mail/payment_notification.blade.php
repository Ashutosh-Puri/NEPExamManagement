@component('mail::message')
#  Payment @if($status) {{ $status }} @endif !

Your Payment Has Been @if($status) {{ $status }} @endif .

## Payment Details:

@if($payment_data['payment_status'] == 'processed')
- **Refund ID:** {{ $payment_data['refund_id'] }}
@endif
- **Payment ID:** {{ $payment_data['payment_id'] }}
@if($payment_data['payment_status'] != 'processed')
- **Payment Method:** {{ ucfirst($payment_data['payment_method']) }}
@endif
- **Payment Amount:** {{ number_format($payment_data['payment_amount'], 2) }} Rs.
@if($payment_data['payment_status'] == 'failed')
- **Payment Status:** {{ $status }}
- **Payment Failed Reason:** {{ $payment_data['payment_fail_reason'] }}
- **Payment Failed Description:** {{ $payment_data['payment_fail_description'] }}
@endif

@if($payment_data['payment_status'] == 'failed')
Try Again!
@endif

Thank You!
@endcomponent
