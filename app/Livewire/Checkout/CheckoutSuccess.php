<?php

namespace App\Livewire\Checkout;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CheckoutSuccess extends Component
{
    public Transaction $transaction;

    public function mount($transaction)
    {
        $this->transaction = Transaction::with('transactionItems.product')
            ->where('id', $transaction)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.checkout.checkout-success');
    }
}
