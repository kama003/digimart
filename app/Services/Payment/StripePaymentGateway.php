<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('payment.stripe.secret'));
    }

    /**
     * Create a payment intent for the given amount
     *
     * @param float $amount
     * @param array $metadata
     * @return array ['payment_id' => string, 'client_secret' => string]
     */
    public function createPaymentIntent(float $amount, array $metadata): array
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => (int) ($amount * 100), // Convert to cents
                'currency' => config('payment.currency', 'usd'),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'payment_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
            ];
        } catch (ApiErrorException $e) {
            Log::channel('payment')->error('Stripe payment intent creation failed', [
                'error' => $e->getMessage(),
                'error_code' => $e->getError()->code ?? null,
                'amount' => $amount,
                'metadata' => $metadata,
                'timestamp' => now()->toIso8601String(),
            ]);

            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a payment by payment ID
     *
     * @param string $paymentId
     * @return bool
     */
    public function confirmPayment(string $paymentId): bool
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentId);

            return $paymentIntent->status === 'succeeded';
        } catch (ApiErrorException $e) {
            Log::channel('payment')->error('Stripe payment confirmation failed', [
                'error' => $e->getMessage(),
                'error_code' => $e->getError()->code ?? null,
                'payment_id' => $paymentId,
                'timestamp' => now()->toIso8601String(),
            ]);

            return false;
        }
    }

    /**
     * Refund a payment
     *
     * @param string $paymentId
     * @param float $amount
     * @return bool
     */
    public function refund(string $paymentId, float $amount): bool
    {
        try {
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $paymentId,
                'amount' => (int) ($amount * 100), // Convert to cents
            ]);

            return $refund->status === 'succeeded';
        } catch (ApiErrorException $e) {
            Log::channel('payment')->error('Stripe refund failed', [
                'error' => $e->getMessage(),
                'error_code' => $e->getError()->code ?? null,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'timestamp' => now()->toIso8601String(),
            ]);

            return false;
        }
    }
}
