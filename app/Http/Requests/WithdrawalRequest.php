<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isSeller() || auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxAmount = auth()->user()->balance ?? 0;

        return [
            'amount' => ['required', 'numeric', 'min:1', "max:{$maxAmount}"],
            'method' => ['required', 'in:bank_transfer,paypal,other'],
            'account_details' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Please enter the withdrawal amount.',
            'amount.min' => 'Minimum withdrawal amount is $1.',
            'amount.max' => 'Withdrawal amount cannot exceed your available balance.',
            'method.required' => 'Please select a withdrawal method.',
            'method.in' => 'Invalid withdrawal method selected.',
            'account_details.required' => 'Please provide your account details.',
            'account_details.min' => 'Account details must be at least 10 characters.',
        ];
    }
}
