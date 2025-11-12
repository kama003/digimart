<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 5, 100);
        $commission = $price * 0.10;
        $sellerAmount = $price - $commission;
        
        return [
            'transaction_id' => \App\Models\Transaction::factory(),
            'product_id' => \App\Models\Product::factory(),
            'seller_id' => \App\Models\User::factory(),
            'price' => $price,
            'commission' => $commission,
            'seller_amount' => $sellerAmount,
        ];
    }
}
