@component('mail::message')
#Acknowledgment regarding manuscript submission (Sangamner College Mail Notification)
 
Respected Sir/Madam,<br>
{{$papersubmission->faculty->faculty_name}}
@component('mail::table')
    | CLASS                 |{{$papersubmission->subject->patternclass->courseclass->course->course_name}}                             |
    | --------------------- |:---------------------------------------------------------------------------------------------:|         
    | Course Title           | {{$papersubmission->subject->subject_code}}   {{$papersubmission->subject->subject_name}}   |
    | No of Set Submitted   |  {{$papersubmission->noofsets}}                                                               | 
    | Date of submission    | {{$papersubmission->created_at}}                                                              |     
    @endcomponent
    
    Thank you for manuscript submission.
 

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent0