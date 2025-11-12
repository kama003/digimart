<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Notifications')]
class NotificationCenter extends Component
{
    use WithPagination;

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
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('notificationReceived');
    }

    public function render()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(15);

        return view('livewire.notifications.notification-center', [
            'notifications' => $notifications,
        ]);
    }
}
