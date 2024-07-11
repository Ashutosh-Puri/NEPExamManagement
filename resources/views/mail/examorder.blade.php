@component('mail::message')
# Your Appoinment for Examination Work (Mail Notification)

Respected Sir/Madam,<br>
{{$examorder->exampanel->faculty->faculty_name." have been appointed for Examination work."}}
@component('mail::table')
    | Time of Meeting | Place of Meeting           | Date              |
    |:---------------:|:---------------------------|:------------------|  
    |10.30  AM        | Examination Section,       | Please See        | 
    |                 | First Floor,               | the attached file | 
    |                 |Sangamner College,Sangamner |                   |
    @endcomponent
    
@component('mail::button', ['url' => $url])
Download Order
@endcomponent
    @component('mail::panel')
     Chairman has to attend meeting before 45 mins of schedule.
@endcomponent


@component('mail::panel')
     Manuscripts of the question papers will be accepted between 10.00 A.M. - 1.00 P.M. and between 2.15 P.M. - 5.30 P.M. only.
@endcomponent

 
No seal from the College will be provided.<br>
At the time of submission of sealed envelopes of manuscripts / solutions / scheme of marking, the Chairman should produce his / her appointment letter and the Identity Card in the Strong Room.



We have taken utmost care while sending this appointment, in case you have received a wrong Email/Appointment ,<br> please intimate us at following email_id exam.unit@sangamnercollege.edu.in
 
Thanks,<br>
CEO,<br>
{{ config('app.name') }}<br>
Call Us On
(02425) 223181
(02425) 222869
@endcomponent
