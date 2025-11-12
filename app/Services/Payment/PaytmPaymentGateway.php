<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Helpers\PaytmChecksum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaytmPaymentGateway implements PaymentGatewayInterface
{
    protected string $merchantId;
    protected string $merchantKey;
    protected string $website;
    protected string $environment;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('payment.paytm.merchant_id');
        $this->merchantKey = config('payment.paytm.merchant_key');
        $this->website = config('payment.paytm.website');
        $this->environment = config('payment.paytm.environment', 'production');
        
        $this->baseUrl = $this->environment === 'production'
            ? 'https://securegw.paytm.in'
            : 'https://securegw-stage.paytm.in';
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
            $orderId = 'ORDER_' . uniqid();
            
            $paytmParams = [
                'body' => [
                    'requestType' => 'Payment',
                    'mid' => $this->merchantId,
                    'websiteName' => $this->website,
                    'orderId' => $orderId,
                    'txnAmount' => [
                        'value' => number_format($amount, 2, '.', ''),
                        'currency' => config('payment.currency', 'INR'),
                    ],
                    'userInfo' => [
                        'custId' => $metadata['user_id'] ?? 'GUEST',
                    ],
                    'callbackUrl' => route('paytm.callback'),
                ],
            ];

            // Generate checksum
            $checksum = PaytmChecksum::generateSignature(
                json_encode($paytmParams['body'], JSON_UNESCAPED_SLASHES),
                $this->merchantKey
            );

            $paytmParams['head'] = [
                'signature' => $checksum,
            ];

            // Initiate transaction
            $response = Http::post(
                $this->baseUrl . '/theia/api/v1/initiateTransaction?mid=' . $this->merchantId . '&orderId=' . $orderId,
                $paytmParams
            );

            $responseData = $response->json();

            if (isset($responseData['body']['resultInfo']['resultStatus']) && 
                $responseData['body']['resultInfo']['resultStatus'] === 'S') {
                
                return [
                    'payment_id' => $orderId,
                    'client_secret' => $responseData['body']['txnToken'],
                    'metadata' => $metadata,
                ];
            }

            throw new \Exception('Paytm transaction initiation failed: ' . ($responseData['body']['resultInfo']['resultMsg'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::channel('payment')->error('Paytm payment intent creation failed', [
                'error' => $e->getMessage(),
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
            $paytmParams = [
                'body' => [
                    'mid' => $this->merchantId,
                    'orderId' => $paymentId,
                ],
            ];

            // Generate checksum
            $checksum = PaytmChecksum::generateSignature(
                json_encode($paytmParams['body'], JSON_UNESCAPED_SLASHES),
                $this->merchantKey
            );

            $paytmParams['head'] = [
                'signature' => $checksum,
            ];

            // Check transaction status
            $response = Http::post(
                $this->baseUrl . '/v3/order/status',
                $paytmParams
            );

            $responseData = $response->json();

            if (isset($responseData['body']['resultInfo']['resultStatus'])) {
                return $responseData['body']['resultInfo']['resultStatus'] === 'TXN_SUCCESS';
            }

            return false;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Paytm payment confirmation failed', [
                'error' => $e->getMessage(),
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
            $refundId = 'REFUND_' . uniqid();
            
            $paytmParams = [
                'body' => [
                    'mid' => $this->merchantId,
                    'orderId' => $paymentId,
                    'refId' => $refundId,
                    'txnType' => 'REFUND',
                    'refundAmount' => number_format($amount, 2, '.', ''),
                ],
            ];

            // Generate checksum
            $checksum = PaytmChecksum::generateSignature(
                json_encode($paytmParams['body'], JSON_UNESCAPED_SLASHES),
                $this->merchantKey
            );

            $paytmParams['head'] = [
                'signature' => $checksum,
            ];

            // Initiate refund
            $response = Http::post(
                $this->baseUrl . '/refund/apply',
                $paytmParams
            );

            $responseData = $response->json();

            if (isset($responseData['body']['resultInfo']['resultStatus'])) {
                return in_array($responseData['body']['resultInfo']['resultStatus'], ['PENDING', 'SUCCESS']);
            }

            return false;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Paytm refund failed', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
                'amount' => $amount,
                'timestamp' => now()->toIso8601String(),
            ]);

            return false;
        }
    }
}
