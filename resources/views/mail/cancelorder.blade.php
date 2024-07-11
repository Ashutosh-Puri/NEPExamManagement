@component('mail::message')
# Your Appoinment for Cancelation For Examination Work (Mail Notification)

Respected Sir/Madam,<br>
{{$examorder->exampanel->faculty->faculty_name}}

Your Appointment for {{ $examorder->exampanel->subject->subject_name }} for post {{ $examorder->exampanel->examorderpost->post_name }} has been cancelled<br>
Thanking You,<br>
Deputy register<br>
Exam Coordination
@endcomponent
