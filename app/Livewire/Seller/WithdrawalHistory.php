<?php

namespace App\Livewire\Seller;

use App\Models\Withdrawal;
use Livewire\Component;
use Livewire\WithPagination;

class WithdrawalHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $withdrawals = Withdrawal::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.seller.withdrawal-history', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
