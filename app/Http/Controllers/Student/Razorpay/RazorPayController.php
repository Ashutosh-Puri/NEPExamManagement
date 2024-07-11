<?php

namespace App\Http\Controllers\Student\Razorpay;

use Razorpay\Api\Api;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Examformmaster;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Student\StudentPaymentNotificationJob;


class RazorPayController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function student_pay_exam_form_fee(Request $request)
    {   
        $examformmaster = Examformmaster::find($request->exam_form_master_id);
        if($examformmaster)
        {
            DB::beginTransaction();
            try
            {
                $orderdata = [
                    'amount' => ( $examformmaster->totalfee * 100 ),
                    'currency' => env('RAZORPAY_CURRENCY'),
                    'receipt' => 'Exam_Form_Receipt_'.$examformmaster->id.'_'.$examformmaster->student_id,
                    'payment_capture'=>1,
                    'notes'=>[
                        'student_id'=>$examformmaster->student_id,
                        'exam_form_master_id'=>$examformmaster->id,
                        'member_id'=> $examformmaster->student->memid,
                        'date'=>date('d-m-Y h:i:s A')
                    ]
                ];

                $order = $this->api->order->create($orderdata);
                if($order)
                {
                    $transaction= new Transaction;
                    if( $transaction)
                    {   
                        $transaction->transaction_name="Exam Form";
                        $transaction->razorpay_order_id=$order->id;
                        $transaction->amount= $examformmaster->totalfee;
                        $transaction->status='created';
                        $transaction->save();
                        if($transaction)
                        {
                            $examformmaster->transaction_id = $transaction->id;
                            $examformmaster->update();
                        }
                    }
                    $logo="data:image/x-icon;base64,".base64_encode(file_get_contents(public_path('favicon.ico')));
                    $json_order_data = [
                        "key" =>config('services.razorpay.key'), 
                        "amount"=> $examformmaster->totalfee * 100, 
                        "currency"=> env('RAZORPAY_CURRENCY'),
                        "name"=>preg_replace('/(?<!\ )[A-Z]/', ' $0', config('app.name')), 
                        "description"=> "Student_Exam_Form_Payment",
                        "order_id"=> $order->id,
                        "image"=>$logo,
                        "prefill"=> [
                            "name"=> $examformmaster->student->student_name,
                            "email"=>  $examformmaster->student->email,
                            "contact"=>$examformmaster->student->mobile_no, 
                        ],
                        "notes"=> [
                            "exam_form_master_id"=> $examformmaster->id,
                            "student_id"=> $examformmaster->student_id,
                            "member_id"=> $examformmaster->student->memid,
                            "address"=> isset($examformmaster->student->getpermanentaddress()->address)? $examformmaster->student->getpermanentaddress()->address:'',
                        ],
                        "theme"=> [
                            "color"=> "#32CD32" //lime
                        ]
                    ];
                    
                    DB::commit();
                    return view('razorpay.confirm_exam_form_payment',compact('order','json_order_data'));
                }

            } catch (\Razorpay\Api\Errors\Error $e) {
                DB::rollback();
                return redirect()->route('student.paymnets')->with('alert', ['type' => 'error', 'message' => 'Order Not Created.']);
            }
        }

    }

    public function student_verify_exam_form_payment(Request $request)
    {   
        try {

            DB::beginTransaction();

            $transaction = Transaction::where('razorpay_order_id', $request->razorpay_order_id)->first();
            if($transaction)
            {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ];
        
                $this->api->utility->verifyPaymentSignature($attributes);
                
                $payment = $this->api->payment->fetch($request->razorpay_payment_id);
                if($payment)
                {
                    $transaction->razorpay_payment_id = $request->razorpay_payment_id;
                    $transaction->razorpay_signature = $request->razorpay_signature;
                    $transaction->payment_date =  isset($payment['created_at']) ? date_create_from_format('U', $payment['created_at']) : null;
                    $transaction->status='captured'; // Capured
                    $transaction->save();
            
                    $exam_form_master =  $transaction->examformmaster()->first();
                    if( $exam_form_master)
                    {
                        $exam_form_master->feepaidstatus = 1;
                        $exam_form_master->payment_date = now();
                        $exam_form_master->save();
                    }
                }

            }

            $data = ['student_id' =>$exam_form_master->student_id, 'payment_response' => $payment];
            StudentPaymentNotificationJob::dispatch($data);
           
            DB::commit();

            return redirect()->route('student.payments')->with('alert', ['type' => 'success', 'message' => 'Payment Success & Verification Success.']);
        } 
        catch (SignatureVerificationError $e) 
        {
            DB::rollBack();

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Verification Failed.']);

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'An error occurred during payment verification.']);
        }
    }
    
    public function student_failed_exam_form_payment(Request $request)
    {   

        DB::beginTransaction();
        try 
        { 
            $payment = $this->api->payment->fetch($request->error_razorpay_payment_id);
            if($payment)
            {
                $transaction=Transaction::where('razorpay_order_id',$payment->order_id)->first();
                if($transaction)
                {
                    $transaction->razorpay_payment_id=$payment->id;
                    $transaction->payment_date = now();
                    $transaction->status='failed'; // fail
                    $transaction->update();
    
                    $examformmaster=$transaction->examformmaster;
                    if($examformmaster)
                    {   
                        $data = ['student_id' =>$examformmaster->student_id, 'payment_response' => $payment];
                        StudentPaymentNotificationJob::dispatch($data);
                    }
    
                    DB::commit();
                    return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Payment Failed.']);
                } 
            } 
            else 
            {
                return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Transaction not found.']);
            }
        } 
        catch (\Exception $e) 
        {
            DB::rollback(); 

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'An error occurred while processing the payment.']);
        }
    }

    public function student_refund_exam_form_fee(Request $request)
    {   
        $examformmaster = Examformmaster::find($request->exam_form_master_id);

        if(isset($examformmaster->transaction->id))
        {
            $transaction=Transaction::find($examformmaster->transaction->id);
            if($transaction)
            {   
                DB::beginTransaction();
                try 
                { 
                    $refund = $this->api->payment->fetch($transaction->razorpay_payment_id)->refund([
                        'amount' => $transaction->amount*100,
                        'speed' => 'optimum',
                        "receipt"=>"Student_Exam_Form_Fee_Refund_".$examformmaster->id
                    ]);
                    $transaction->razorpay_refund_id= $refund->id;
                    $transaction->status= 'refunded'; // Refund
                    $transaction->update();
                    
                    $data = ['student_id' =>$examformmaster->student_id, 'payment_response' => $this->api->payment->fetch($transaction->razorpay_payment_id)->fetchRefund($refund->id)];
                    StudentPaymentNotificationJob::dispatch($data);
                    
                    DB::commit();
                    
                    return redirect()->route('student.payments')->with('alert', ['type' => 'success', 'message' => 'Payment Refund Was Successful. Refund ID: '.$refund->id]);
                } 
                catch (\Razorpay\Api\Error\BadRequest $e) 
                {
                    DB::rollback();
                    return redirect()->route('student.payments')->with('alert', ['type' => 'info','message' => 'This Payment Has Already Been Fully Refunded.',]);       
                } 
                catch (Exception $e) 
                {         
                    DB::rollback();
                    return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'An error occurred while processing the refund. Please try again later.']);
                }
            }
        }
    }

    public function student_pay_ordinace_163_form_fee(Request $request)
    {   
        $studentordinace163 = Studentordinace163::find($request->student_ordinace_163_id);
        if($studentordinace163)
        {
            DB::beginTransaction();
            try
            {
                $orderdata = [
                    'amount' => ( $studentordinace163->fee * 100 ),
                    'currency' => env('RAZORPAY_CURRENCY'),
                    'receipt' => 'Ordinace_163_Receipt_'.$studentordinace163->id.'_'.$studentordinace163->student_id,
                    'payment_capture'=>1,
                    'notes'=>[
                        'student_id'=>$studentordinace163->student_id,
                        'student_ordinace_163_id'=>$studentordinace163->id,
                        'member_id'=> $studentordinace163->student->memid,
                        'date'=>date('d-m-Y h:i:s A')
                    ]
                ];

                $order = $this->api->order->create($orderdata);
                if($order)
                {
                    $transaction= new Transaction;
                    if( $transaction)
                    {
                        $transaction->transaction_name="Ordinace 163 Form";
                        $transaction->razorpay_order_id=$order->id;
                        $transaction->amount= $studentordinace163->fee;
                        $transaction->status='created';
                        $transaction->save();
                        if($transaction)
                        {
                            $studentordinace163->transaction_id = $transaction->id;
                            $studentordinace163->update();
                        }
                    }
                    $logo="data:image/x-icon;base64,".base64_encode(file_get_contents(public_path('favicon.ico')));
                    $json_order_data = [
                        "key" =>config('services.razorpay.key'), 
                        "amount"=> $studentordinace163->fee * 100, 
                        "currency"=> env('RAZORPAY_CURRENCY'),
                        "name"=>preg_replace('/(?<!\ )[A-Z]/', ' $0', config('app.name')), 
                        "description"=> "Ordinace_163_Form_Payment",
                        "order_id"=> $order->id,
                        "image"=>$logo,
                        "prefill"=> [
                            "name"=> $studentordinace163->student->student_name,
                            "email"=>  $studentordinace163->student->email,
                            "contact"=>$studentordinace163->student->mobile_no, 
                        ],
                        "notes"=> [
                            "student_ordinace_163_id"=> $studentordinace163->id,
                            "student_id"=> $studentordinace163->student_id,
                            "member_id"=> $studentordinace163->student->memid,
                            "address"=> isset($studentordinace163->student->getpermanentaddress()->address)? $studentordinace163->student->getpermanentaddress()->address:'',
                        ],
                        "theme"=> [
                            "color"=> "#32CD32" //lime
                        ]
                    ];
                    
                    DB::commit();
                    return view('razorpay.confirm_ordinace_163_form_payment',compact('order','json_order_data'));
                }

            } catch (\Razorpay\Api\Errors\Error $e) {
                DB::rollback();
                return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Order Not Created.']);
            }
        }

    }

    public function student_verify_ordinace_163_form_payment(Request $request)
    {   
        try {

            DB::beginTransaction();

            $transaction = Transaction::where('razorpay_order_id', $request->razorpay_order_id)->first();
            if($transaction)
            {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ];
        
                $this->api->utility->verifyPaymentSignature($attributes);
                
                $payment = $this->api->payment->fetch($request->razorpay_payment_id);
                if($payment)
                {
                    $transaction->razorpay_payment_id = $request->razorpay_payment_id;
                    $transaction->razorpay_signature = $request->razorpay_signature;
                    $transaction->payment_date =  isset($payment['created_at']) ? date_create_from_format('U', $payment['created_at']) : null;
                    $transaction->status='captured'; // Capured
                    $transaction->save();
            
                    $studentordinace163 =  $transaction->studentordinace163()->first();
                    if( $studentordinace163)
                    {
                        $studentordinace163->is_fee_paid = 1;
                        $studentordinace163->payment_date = now();
                        $studentordinace163->save();
                    }
                }

            }

            $data = ['student_id' =>$studentordinace163->student_id, 'payment_response' => $payment];
            StudentPaymentNotificationJob::dispatch($data);
           
            DB::commit();

            return redirect()->route('student.payments')->with('alert', ['type' => 'success', 'message' => 'Payment Success & Verification Success.']);
        } 
        catch (SignatureVerificationError $e) 
        {
            DB::rollBack();

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Verification Failed.']);

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'An error occurred during payment verification.']);
        }
    }

    public function student_failed_ordinace_163_form_payment(Request $request)
    {   

        DB::beginTransaction();
        try 
        { 
            $payment = $this->api->payment->fetch($request->error_razorpay_payment_id);
            if($payment)
            {
                $transaction=Transaction::where('razorpay_order_id',$payment->order_id)->first();
                if($transaction)
                {
                    $transaction->razorpay_payment_id=$payment->id;
                    $transaction->payment_date = now();
                    $transaction->status='failed'; // fail
                    $transaction->update();
    
                    $studentordinace163=$transaction->studentordinace163;
                    if($studentordinace163)
                    {   
                        $studentordinace163->payment_date = now();
                        $studentordinace163->update();

                        $data = ['student_id' =>$studentordinace163->student_id, 'payment_response' => $payment];
                        StudentPaymentNotificationJob::dispatch($data);
                    }
    
                    DB::commit();
                    return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Payment Failed.']);
                } 
            } 
            else 
            {
                return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'Transaction not found.']);
            }
        } 
        catch (\Exception $e) 
        {
            DB::rollback(); 

            return redirect()->route('student.payments')->with('alert', ['type' => 'error', 'message' => 'An error occurred while processing the payment.']);
        }
    }
}
