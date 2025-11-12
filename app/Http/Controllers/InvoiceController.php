<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Download invoice for a transaction
     */
    public function download(int $transactionId): Response
    {
        // Verify the user owns this transaction
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with([
                'user',
                'transactionItems.product.category',
                'transactionItems.seller',
            ])
            ->firstOrFail();

        // Generate PDF
        $pdf = Pdf::loadView('invoices.template', [
            'transaction' => $transaction,
        ]);

        // Return PDF download
        return $pdf->download('invoice-' . $transaction->id . '.pdf');
    }
}
