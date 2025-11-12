<?php

namespace App\Listeners;

use App\Events\PurchaseCompleted;
use App\Models\Download;
use App\Models\TransactionItem;
use App\Models\User;
use App\Services\CartService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPurchase implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct(
        private CartService $cartService,
        private NotificationService $notificationService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseCompleted $event): void
    {
        $transaction = $event->transaction;

        try {
            DB::transaction(function () use ($transaction) {
                // Get cart items from transaction metadata
                $cartItems = $transaction->metadata['cart_items'] ?? [];

                if (empty($cartItems)) {
                    Log::warning('No cart items found in transaction metadata', [
                        'transaction_id' => $transaction->id,
                    ]);
                    return;
                }

                $commissionPercent = config('payment.commission_percent', 10);

                // Create transaction items and update seller balances
                foreach ($cartItems as $item) {
                    $product = $item['product'];
                    $quantity = $item['quantity'];
                    $price = $product['price'];
                    $subtotal = $price * $quantity;

                    // Calculate commission and seller amount
                    $commission = ($subtotal * $commissionPercent) / 100;
                    $sellerAmount = $subtotal - $commission;

                    // Create transaction item
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product['id'],
                        'seller_id' => $product['user_id'],
                        'price' => $price,
                        'commission' => $commission,
                        'seller_amount' => $sellerAmount,
                    ]);

                    // Update seller balance
                    DB::table('users')
                        ->where('id', $product['user_id'])
                        ->increment('balance', $sellerAmount);

                    // Generate download link
                    $expiryHours = config('payment.download_link_expiry_hours', 24);
                    
                    Download::create([
                        'user_id' => $transaction->user_id,
                        'product_id' => $product['id'],
                        'transaction_id' => $transaction->id,
                        'download_url' => '', // Will be generated on-demand
                        'expires_at' => now()->addHours($expiryHours),
                    ]);

                    // Update product downloads count
                    DB::table('products')
                        ->where('id', $product['id'])
                        ->increment('downloads_count');
                }

                // Clear user's cart
                $this->cartService->clear();

                Log::info('Purchase processed successfully', [
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'items_count' => count($cartItems),
                    'timestamp' => now()->toIso8601String(),
                ]);
            });

            // Prepare download links for notification
            $downloadLinks = [];
            $downloads = Download::where('transaction_id', $transaction->id)
                ->with('product')
                ->get();

            foreach ($downloads as $download) {
                $downloadLinks[] = [
                    'product_title' => $download->product->title,
                    'url' => route('download', $download->id),
                ];
            }

            // Send purchase confirmation notification to customer
            $this->notificationService->sendPurchaseConfirmation(
                $transaction->user,
                $transaction,
                $downloadLinks
            );

            // Send new sale notifications to sellers
            $transactionItems = TransactionItem::where('transaction_id', $transaction->id)
                ->with('seller')
                ->get();

            foreach ($transactionItems as $transactionItem) {
                $this->notificationService->sendNewSaleNotification(
                    $transactionItem->seller,
                    $transactionItem
                );
            }

        } catch (\Exception $e) {
            Log::error('Purchase processing failed', [
                'transaction_id' => $transaction->id,
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
    public function failed(PurchaseCompleted $event, \Throwable $exception): void
    {
        Log::error('Purchase processing listener failed', [
            'transaction_id' => $event->transaction->id,
            'error' => $exception->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

