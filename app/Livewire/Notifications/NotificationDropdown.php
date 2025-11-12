<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\Computed;

class NotificationDropdown extends Component
{
    public $isOpen = false;

    /**
     * Get recent notifications (last 5)
     */
    #[Computed]
    public function recentNotifications()
    {
        return auth()->user()
            ->notifications()
            ->take(5)
            ->get();
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notificationReceived');
        }
    }

    /**
     * Toggle dropdown visibility
     */
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    /**
     * Listen for toggle event
     */
    protected $listeners = ['toggle-notifications' => 'toggle'];

    public function render()
    {
        return view('livewire.notifications.notification-dropdown');
    }
}
