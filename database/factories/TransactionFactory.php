<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 10, 500);
        $commission = $amount * 0.10;
        $sellerAmount = $amount - $commission;
        
        return [
            'user_id' => \App\Models\User::factory(),
            'payment_gateway' => fake()->randomElement(['stripe', 'paytm']),
            'payment_id' => 'test_' . fake()->uuid(),
            'amount' => $amount,
            'commission' => $commission,
            'seller_amount' => $sellerAmount,
            'status' => 'pending',
            'metadata' => [],
        ];
    }
}
