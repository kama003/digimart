<?php

namespace App\Livewire\Customer;

use App\Models\Download;
use App\Models\Transaction;
use App\Services\StorageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseHistory extends Component
{
    use WithPagination;

    protected StorageService $storageService;

    public function boot(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Generate a new download link for a purchased product
     */
    public function generateDownloadLink(int $productId, int $transactionId): void
    {
        try {
            // Verify the user owns this transaction
            $transaction = Transaction::where('id', $transactionId)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->firstOrFail();

            // Verify the product is in this transaction
            $transactionItem = $transaction->transactionItems()
                ->where('product_id', $productId)
                ->with('product')
                ->firstOrFail();

            $product = $transactionItem->product;

            // Generate temporary URL
            $downloadUrl = $this->storageService->generateTemporaryUrl(
                $product->file_path,
                config('filesystems.download_link_expiry_hours', 24)
            );

            // Create new download record
            Download::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'transaction_id' => $transactionId,
                'download_url' => $downloadUrl,
                'expires_at' => now()->addHours(config('filesystems.download_link_expiry_hours', 24)),
            ]);

            session()->flash('success', 'Download link generated successfully! It will expire in ' . config('filesystems.download_link_expiry_hours', 24) . ' hours.');
            
            // Refresh the component to show the new download link
            $this->dispatch('download-link-generated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate download link. Please try again or contact support.');
        }
    }

    public function render()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with([
                'transactionItems.product.category',
                'transactionItems.product.user',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get active download links for the user
        $activeDownloads = Download::where('user_id', Auth::id())
            ->where('expires_at', '>', now())
            ->whereNull('downloaded_at')
            ->get()
            ->keyBy(fn($download) => $download->product_id . '_' . $download->transaction_id);

        return view('livewire.customer.purchase-history', [
            'transactions' => $transactions,
            'activeDownloads' => $activeDownloads,
        ]);
    }
}
