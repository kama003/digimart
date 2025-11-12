<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\SellerRoleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class SellerRoleRequests extends Component
{
    use WithPagination;

    public $showApproveModal = false;
    public $showRejectModal = false;
    public $selectedRequestId = null;
    public $rejectionReason = '';

    protected $rules = [
        'rejectionReason' => 'required|string|min:10',
    ];

    public function openApproveModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showApproveModal = true;
    }

    public function openRejectModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeModals()
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->selectedRequestId = null;
        $this->rejectionReason = '';
        $this->resetValidation();
    }

    public function approve()
    {
        // Authorize admin access
        Gate::authorize('viewAny', User::class);

        $request = SellerRoleRequest::with('user')->findOrFail($this->selectedRequestId);

        // Check if request is still pending
        if ($request->status !== 'pending') {
            session()->flash('error', 'This request has already been processed.');
            $this->closeModals();
            return;
        }

        // Update user role to seller
        $request->user->update([
            'role' => UserRole::SELLER,
        ]);

        // Update request status
        $request->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        session()->flash('success', 'Seller role request approved successfully. User has been upgraded to seller.');
        $this->closeModals();
    }

    public function reject()
    {
        // Authorize admin access
        Gate::authorize('viewAny', User::class);

        $this->validate();

        $request = SellerRoleRequest::findOrFail($this->selectedRequestId);

        // Check if request is still pending
        if ($request->status !== 'pending') {
            session()->flash('error', 'This request has already been processed.');
            $this->closeModals();
            return;
        }

        // Update request status
        $request->update([
            'status' => 'rejected',
            'admin_notes' => $this->rejectionReason,
            'processed_at' => now(),
        ]);

        session()->flash('success', 'Seller role request rejected.');
        $this->closeModals();
    }

    public function render()
    {
        // Authorize admin access
        Gate::authorize('viewAny', User::class);

        $requests = SellerRoleRequest::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.seller-role-requests', [
            'requests' => $requests,
        ]);
    }
}
