<?php

namespace App\Livewire\Admin;

use App\Models\Withdrawal;
use App\Notifications\WithdrawalApproved;
use App\Notifications\WithdrawalRejected;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class WithdrawalManagement extends Component
{
    use WithPagination;

    public string $status_filter = '';
    public ?int $rejectingWithdrawalId = null;
    public string $rejectionReason = '';

    protected $queryString = [
        'status_filter' => ['except' => ''],
    ];

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approve($withdrawalId)
    {
        $withdrawal = Withdrawal::findOrFail($withdrawalId);

        // Check if user has sufficient balance
        if ($withdrawal->user->balance < $withdrawal->amount) {
            session()->flash('error', 'User has insufficient balance for this withdrawal.');
            return;
        }

        DB::transaction(function () use ($withdrawal) {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'processed_at' => now(),
            ]);

            // Deduct amount from user balance
            $withdrawal->user->decrement('balance', $withdrawal->amount);

            // Send notification to seller
            $withdrawal->user->notify(new WithdrawalApproved($withdrawal));
        });

        session()->flash('success', 'Withdrawal approved successfully. Funds deducted from seller balance.');
    }

    public function showRejectModal($withdrawalId)
    {
        $this->rejectingWithdrawalId = $withdrawalId;
        $this->rejectionReason = '';
    }

    public function cancelReject()
    {
        $this->rejectingWithdrawalId = null;
        $this->rejectionReason = '';
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
            'rejectionReason.min' => 'The reason must be at least 10 characters.',
            'rejectionReason.max' => 'The reason must not exceed 500 characters.',
        ]);

        $withdrawal = Withdrawal::findOrFail($this->rejectingWithdrawalId);

        // Update withdrawal status
        $withdrawal->update([
            'status' => 'rejected',
            'admin_notes' => $this->rejectionReason,
            'processed_at' => now(),
        ]);

        // Send notification to seller
        $withdrawal->user->notify(new WithdrawalRejected($withdrawal, $this->rejectionReason));

        session()->flash('success', 'Withdrawal rejected successfully.');

        $this->rejectingWithdrawalId = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        $query = Withdrawal::query()->with('user');

        // Filter by status
        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        $withdrawals = $query->latest()->paginate(20);

        return view('livewire.admin.withdrawal-management', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
