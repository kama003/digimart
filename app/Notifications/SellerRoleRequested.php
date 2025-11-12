<?php

namespace App\Notifications;

use App\Models\SellerRoleRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerRoleRequested extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public SellerRoleRequest $sellerRoleRequest,
        public User $requestingUser
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Seller Role Request')
            ->greeting('Hello Admin,')
            ->line('A new seller role request has been submitted.')
            ->line('User: ' . $this->requestingUser->name . ' (' . $this->requestingUser->email . ')')
            ->line('Please review and approve or reject this request.')
            ->action('Review Request', route('admin.seller-requests'))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'seller_role_request_id' => $this->sellerRoleRequest->id,
            'user_id' => $this->requestingUser->id,
            'user_name' => $this->requestingUser->name,
            'user_email' => $this->requestingUser->email,
            'message' => $this->requestingUser->name . ' has requested to become a seller.',
        ];
    }
}
