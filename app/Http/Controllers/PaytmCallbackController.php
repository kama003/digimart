<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PaytmChecksum;

class PaytmCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $paytmParams = $request->all();
            
            // Verify checksum
            $isValidChecksum = PaytmChecksum::verifySignature(
                $paytmParams,
                config('payment.paytm.merchant_key'),
                $paytmParams['CHECKSUMHASH'] ?? ''
            );

            if (!$isValidChecksum) {
                Log::channel('payment')->error('Paytm callback checksum verification failed', [
                    'params' => $paytmParams,
                    'timestamp' => now()->toIso8601String(),
                ]);
                
                return redirect()->route('checkout')
                    ->with('error', 'Payment verification failed. Please try again.');
            }

            $orderId = $paytmParams['ORDERID'] ?? null;
            $status = $paytmParams['STATUS'] ?? null;

            if (!$orderId) {
                return redirect()->route('checkout')
                    ->with('error', 'Invalid payment response.');
            }

            // Find transaction
            $transaction = Transaction::where('payment_id', $orderId)->first();

            if (!$transaction) {
                Log::channel('payment')->error('Transaction not found for Paytm callback', [
                    'order_id' => $orderId,
                    'timestamp' => now()->toIso8601String(),
                ]);
                
                return redirect()->route('checkout')
                    ->with('error', 'Transaction not found.');
            }

            if ($status === 'TXN_SUCCESS') {
                // Payment successful - webhook will handle the rest
                return redirect()->route('checkout.success', ['transaction' => $transaction->id])
                    ->with('success', 'Payment successful!');
            } else {
                // Payment failed
                Log::channel('payment')->warning('Paytm payment failed', [
                    'order_id' => $orderId,
                    'status' => $status,
                    'response' => $paytmParams,
                    'timestamp' => now()->toIso8601String(),
                ]);
                
                return redirect()->route('checkout')
                    ->with('error', 'Payment failed: ' . ($paytmParams['RESPMSG'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            Log::channel('payment')->error('Paytm callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'timestamp' => now()->toIso8601String(),
            ]);

            return redirect()->route('checkout')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }
}
