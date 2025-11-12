<?php

namespace App\Livewire\Seller;

use App\Models\Withdrawal;
use Livewire\Component;

class WithdrawalRequest extends Component
{
    public $amount = '';
    public $method = '';
    public $account_details = '';

    protected function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . auth()->user()->balance,
            ],
            'method' => 'required|in:bank_transfer,paypal,other',
            'account_details' => 'required|string|min:10',
        ];
    }

    protected $messages = [
        'amount.required' => 'Please enter an amount to withdraw.',
        'amount.numeric' => 'Amount must be a valid number.',
        'amount.min' => 'Minimum withdrawal amount is $0.01.',
        'amount.max' => 'Amount cannot exceed your available balance.',
        'method.required' => 'Please select a withdrawal method.',
        'method.in' => 'Invalid withdrawal method selected.',
        'account_details.required' => 'Please provide your account details.',
        'account_details.min' => 'Account details must be at least 10 characters.',
    ];

    public function submit()
    {
        $this->validate();

        // Create withdrawal request
        Withdrawal::create([
            'user_id' => auth()->id(),
            'amount' => $this->amount,
            'method' => $this->method,
            'account_details' => $this->account_details,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Withdrawal request submitted successfully. We will review it shortly.');

        return redirect()->route('seller.withdrawals');
    }

    public function render()
    {
        $availableBalance = auth()->user()->balance;

        return view('livewire.seller.withdrawal-request', [
            'availableBalance' => $availableBalance,
        ]);
    }
}
