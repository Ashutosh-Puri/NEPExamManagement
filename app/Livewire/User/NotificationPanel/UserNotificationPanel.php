<?php

namespace App\Livewire\User\NotificationPanel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationPanel extends Component
{   
    # By Ashutosh
    public $notifications;

    public function mount()
    {
        $this->notifications = $this->getNotifications();
    }

    protected function getNotifications()
    {

        $user = Auth::guard('user')->user();
        if ($user) {
            $notifications = DatabaseNotification::select('id','data')->where('notifiable_id', $user->id)->where('notifiable_type', get_class($user))->whereNull('read_at')->get();
            return $notifications;
        }
        return [];
    }


    public function mark_as_read_user_notification($notificationId)
    {   
        
        DB::beginTransaction();

        try 
        {   
            $notification = DatabaseNotification::find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->notifications = $this->getNotifications(); 
            }
            
            DB::commit();


            $this->dispatch('alert',type:'success',message:'Saved Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Save !!');
        }
       
    }
    
    public function render()
    {
        return view('livewire.user.notification-panel.user-notification-panel');
    }
}
