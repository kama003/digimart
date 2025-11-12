<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\Computed;

class NotificationBell extends Component
{
    /**
     * Get the unread notification count
     */
    #[Computed]
    public function unreadCount()
    {
        return auth()->user()->unreadNotifications()->count();
    }

    /**
     * Listen for notification events to refresh the count
     */
    protected $listeners = ['notificationReceived' => '$refresh'];

    public function render()
    {
        return view('livewire.notifications.notification-bell');
    }
}
