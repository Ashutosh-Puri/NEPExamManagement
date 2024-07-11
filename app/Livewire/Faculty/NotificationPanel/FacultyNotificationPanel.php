<?php

namespace App\Livewire\Faculty\NotificationPanel;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class FacultyNotificationPanel extends Component
{   

    public $notifications;

    public function mount()
    {
        $this->notifications = $this->getNotifications();
    }

    protected function getNotifications()
    {

        $user = Auth::guard('faculty')->user();
        if ($user) {
            $notifications = DatabaseNotification::select('id','data')->where('notifiable_id', $user->id)->where('notifiable_type', get_class($user))->whereNull('read_at')->get();
            return $notifications;
        }
        return [];
    }


    public function mark_as_read_faculty_notification($notificationId)
    {   
        $notification = DatabaseNotification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->notifications = $this->getNotifications(); 
        }
    }

    public function render()
    {
        return view('livewire.faculty.notification-panel.faculty-notification-panel');
    }
}
