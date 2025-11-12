<?php

namespace App\Livewire\Profile;

use App\Models\SellerRoleRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RequestSellerRole extends Component
{
    public $hasPendingRequest = false;
    public $latestRequest = null;

    public function mount()
    {
        $this->checkPendingRequest();
    }

    public function checkPendingRequest()
    {
        $this->latestRequest = Auth::user()->sellerRoleRequests()
            ->latest()
            ->first();

        $this->hasPendingRequest = $this->latestRequest && $this->latestRequest->status === 'pending';
    }

    public function submitRequest()
    {
        // Check if user is already a seller or admin
        if (Auth::user()->isSeller() || Auth::user()->isAdmin()) {
            session()->flash('error', 'You already have seller privileges.');
            return;
        }

        // Check if there's already a pending request
        if ($this->hasPendingRequest) {
            session()->flash('error', 'You already have a pending seller role request.');
            return;
        }

        // Create new seller role request
        $sellerRoleRequest = SellerRoleRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        // Send notification to all admins
        $notificationService = app(NotificationService::class);
        $notificationService->sendSellerRoleRequestedNotification($sellerRoleRequest, Auth::user());

        $this->checkPendingRequest();

        session()->flash('success', 'Your seller role request has been submitted successfully. An admin will review it soon.');
    }

    public function render()
    {
        return view('livewire.profile.request-seller-role');
    }
}
