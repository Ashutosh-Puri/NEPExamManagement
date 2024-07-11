@extends('layouts.student')
@section('student')
    
<section  x-data="{ loading: false }" x-init="window.addEventListener('beforeunload', () => loading = true)">
  <div x-show="loading" class="fixed top-0 left-0 w-full h-full flex justify-center items-center">
    <x-full-spinner/>
    <div class=" mt-32 text-white text-2xl">
      Proccessing...
    </div>
  </div>
  <section x-show="!loading" class="w-full flex justify-center items-center h-screen">
    <div class="w-full max-w-md mx-auto bg-white  dark:bg-darker border border-primary rounded-lg shadow-lg p-8">
      <h5 class="mb-4 text-2xl xs:text-xl text-center font-bold text-primary">Confirm Payment</h5>
      <hr class="mb-5 h-1 border-0 bg-primary">
      <form  id="payment-success-form" name="payment-success-form" action="{{ route('student.student_verify_ordinace_163_form_payment') }}" method="post">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
      </form>
      <form id="payment-fail-form" name="payment-fail-form" action="{{ route('student.student_failed_ordinace_163_form_payment') }}" method="post">
        @csrf
        <input type="hidden" name="error_razorpay_payment_id" id="error_razorpay_payment_id">
        <input type="hidden" name="error_razorpay_order_id" id="error_razorpay_order_id">
      </form>
      <div class="grid grid-cols-10 md:grid-cols-10 gap-y-4 mb-4">
        <div class="col-span-3">
          <h5 class="mb-1 text-xl xs:text-md font-medium text-gray-900 dark:text-white">Order ID : </h5>
        </div>
        <div class="col-span-7">
          <h5 class="mb-1 text-xl xs:text-md font-medium text-gray-900  dark:text-white">{{ $order->id }} </h5>
        </div>
        <div class="col-span-3">
          <h5 class="mb-1 text-xl xs:text-md font-medium text-gray-900  dark:text-white">Amount : </h5>
        </div>
        <div class="col-span-7">
          <h5 class="mb-1 text-xl xs:text-md font-medium text-gray-900  dark:text-white">{{ number_format($order->amount / 100, 2) }} Rs.</h5>
        </div>
      </div>
      <hr class="mb-4 h-1 border-0 bg-primary">
      <div class="flex justify-between mt-8">
        <div>
          <a wire:navigate href="{{ route('student.payments') }}">
            <button type="button" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Cancel</button>
          </a>
        </div>
        <div>
          <button type="button" id="rzp-button1" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Pay With Razorpay</button>
        </div>
        @section('scripts')
            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                var options = @json($json_order_data);

                options.handler = function(response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('payment-success-form').submit();
                };
                var rzp1 = new Razorpay(options);

                document.getElementById('rzp-button1').addEventListener('click', function(e) {
                    rzp1.open();
                    e.innerHTML = 'Processing...';
                    e.disabled = true;
                    e.preventDefault();
                });

                rzp1.on('payment.failed', function(response) {
                    document.getElementById('error_razorpay_payment_id').value = response.error.metadata.payment_id;
                    document.getElementById('error_razorpay_order_id').value = response.error.metadata.order_id;
                    document.getElementById('payment-fail-form').submit();
                });
            </script>
        @endsection
    </div>
  </div>
</section>
</section>
@endsection
