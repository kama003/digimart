<?php

namespace App\Livewire\Customer;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BillingHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.customer.billing-history', [
            'transactions' => $transactions,
        ]);
    }
}
