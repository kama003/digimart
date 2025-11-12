<?php

namespace App\Jobs;

use App\Events\PurchaseCompleted;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $paymentId,
        public string $gateway,
        public array $metadata = []
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            // Idempotency check: Check if payment has already been processed
            $existingTransaction = Transaction::where('payment_id', $this->paymentId)
                ->where('payment_gateway', $this->gateway)
                ->first();

            if (!$existingTransaction) {
                Log::channel('payment')->warning('Transaction not found for payment', [
                    'payment_id' => $this->paymentId,
                    'gateway' => $this->gateway,
                    'timestamp' => now()->toIso8601String(),
                ]);
                return;
            }

            if ($existingTransaction->status === 'completed') {
                Log::channel('payment')->info('Payment already processed (idempotency check)', [
                    'payment_id' => $this->paymentId,
                    'gateway' => $this->gateway,
                    'transaction_id' => $existingTransaction->id,
                    'timestamp' => now()->toIso8601String(),
                ]);
                return;
            }

            // Update transaction status
            $existingTransaction->update([
                'status' => 'completed',
                'metadata' => array_merge($existingTransaction->metadata ?? [], $this->metadata),
            ]);

            // Fire purchase completed event
            event(new PurchaseCompleted($existingTransaction));

            Log::channel('payment')->info('Payment processed successfully', [
                'payment_id' => $this->paymentId,
                'gateway' => $this->gateway,
                'transaction_id' => $existingTransaction->id,
                'amount' => $existingTransaction->amount,
                'timestamp' => now()->toIso8601String(),
            ]);
        });
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::channel('payment')->error('Webhook processing job failed', [
            'payment_id' => $this->paymentId,
            'gateway' => $this->gateway,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

