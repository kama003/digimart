<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Events\PurchaseCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use PaytmChecksum;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('payment.stripe.webhook_secret');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::channel('payment')->error('Stripe webhook: Invalid payload', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'timestamp' => now()->toIso8601String(),
            ]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::channel('payment')->error('Stripe webhook: Invalid signature', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'timestamp' => now()->toIso8601String(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $paymentId = $paymentIntent->id;

            // Dispatch job to process payment asynchronously
            \App\Jobs\ProcessWebhook::dispatch(
                $paymentId,
                'stripe',
                $paymentIntent->metadata->toArray()
            );

            Log::channel('payment')->info('Stripe webhook received and queued', [
                'payment_id' => $paymentId,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Paytm webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handlePaytmWebhook(Request $request)
    {
        $paytmChecksum = $request->header('X-Paytm-Checksum');
        $payload = $request->all();

        try {
            // Verify webhook signature
            $isValidChecksum = PaytmChecksum::verifySignature(
                json_encode($payload, JSON_UNESCAPED_SLASHES),
                config('payment.paytm.merchant_key'),
                $paytmChecksum
            );

            if (!$isValidChecksum) {
                Log::channel('payment')->error('Paytm webhook: Invalid signature', [
                    'ip' => $request->ip(),
                    'timestamp' => now()->toIso8601String(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } catch (\Exception $e) {
            Log::channel('payment')->error('Paytm webhook: Signature verification failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'timestamp' => now()->toIso8601String(),
            ]);
            return response()->json(['error' => 'Signature verification failed'], 400);
        }

        // Handle the event
        if (isset($payload['STATUS']) && $payload['STATUS'] === 'TXN_SUCCESS') {
            $paymentId = $payload['ORDERID'] ?? null;
            $metadata = [
                'transaction_id' => $payload['TXNID'] ?? null,
                'bank_transaction_id' => $payload['BANKTXNID'] ?? null,
            ];

            if (!$paymentId) {
                Log::channel('payment')->error('Paytm webhook: Missing order ID', [
                    'payload' => $payload,
                    'timestamp' => now()->toIso8601String(),
                ]);
                return response()->json(['error' => 'Missing order ID'], 400);
            }

            // Dispatch job to process payment asynchronously
            \App\Jobs\ProcessWebhook::dispatch(
                $paymentId,
                'paytm',
                $metadata
            );

            Log::channel('payment')->info('Paytm webhook received and queued', [
                'payment_id' => $paymentId,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return response()->json(['status' => 'success']);
    }


}
