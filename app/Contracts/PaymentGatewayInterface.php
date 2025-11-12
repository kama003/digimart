<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Create a payment intent for the given amount
     *
     * @param float $amount
     * @param array $metadata
     * @return array ['payment_id' => string, 'client_secret' => string]
     */
    public function createPaymentIntent(float $amount, array $metadata): array;

    /**
     * Confirm a payment by payment ID
     *
     * @param string $paymentId
     * @return bool
     */
    public function confirmPayment(string $paymentId): bool;

    /**
     * Refund a payment
     *
     * @param string $paymentId
     * @param float $amount
     * @return bool
     */
    public function refund(string $paymentId, float $amount): bool;
}
