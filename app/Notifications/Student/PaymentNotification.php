<?php

namespace App\Notifications\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentNotification extends Notification
{
    use Queueable;

    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function via($notifiable)
    {
        return ['database','mail'];
    }

    public function toMail($notifiable)
    {   
        $status='';
        $payment_data=[];

        if($this->response['status']=='captured')
        {
            $payment_data['payment_id']=$this->response['id'];
            $payment_data['payment_method']=$this->response['method'];
            $payment_data['payment_amount']=$this->response['amount'] / 100;
            $payment_data['payment_status']=$this->response['status'];
            $status="Successful";

        }
        elseif($this->response['status']=='failed')
        {   
            $payment_data['payment_id']=$this->response['id'];
            $payment_data['payment_method']=$this->response['method'];
            $payment_data['payment_amount']=$this->response['amount'] / 100;
            $payment_data['payment_status']=$this->response['status'];
            $payment_data['payment_fail_description']=$this->response['error_description'];
            $payment_data['payment_fail_reason']= $this->response['error_reason']; 

            $status="Failed";
        }
        elseif($this->response['status']=='processed')
        {
            $payment_data['refund_id']=$this->response['id'];
            $payment_data['payment_id']= $this->response['payment_id'];
            $payment_data['payment_amount']=$this->response['amount'] / 100;
            $payment_data['payment_status']=$this->response['status'];
            $status="Refunded";
        }

        $mailMessage = (new MailMessage)
            ->subject('Your Payment '.$status.' Notification')
            ->markdown('mail.payment_notification', ['payment_data' => $payment_data ,'status'=>$status]);

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
