<?php

namespace App\Livewire\Student\NotificationPanel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class StudentNotificationPanel extends Component
{   
    public $notifications=[];

    public function mount()
    {
        $this->notifications = $this->getNotifications();

    }

    protected function getNotifications()
    {   
        $user = Auth::guard('student')->user();
        if($user)
        {
            return  DatabaseNotification::select('id','data')->where('notifiable_id',$user->id)->where('notifiable_type', get_class($user))->whereNull('read_at')->get();
        }else
        {
            return [];
        }
    }


    public function mark_as_read_student_notification($notificationId)
    {   
        $notification = DatabaseNotification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->notifications = $this->getNotifications(); 
        }
    }

    public function render()
    {
        return view('livewire.student.notification-panel.student-notification-panel');
    }
}
