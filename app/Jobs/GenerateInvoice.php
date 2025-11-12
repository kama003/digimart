<?php

namespace App\Jobs;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $transactionId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $transaction = Transaction::with(['user', 'transactionItems.product'])
                ->findOrFail($this->transactionId);

            // Generate PDF
            $pdf = Pdf::loadView('invoices.template', [
                'transaction' => $transaction,
            ]);

            // Store PDF in storage
            $filename = 'invoices/invoice-' . $transaction->id . '.pdf';
            Storage::put($filename, $pdf->output());

            Log::info('Invoice generated successfully', [
                'transaction_id' => $this->transactionId,
                'filename' => $filename,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Invoice generation failed', [
                'transaction_id' => $this->transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toIso8601String(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Invoice generation job failed', [
            'transaction_id' => $this->transactionId,
            'error' => $exception->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

