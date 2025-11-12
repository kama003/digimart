<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\SellerRoleRequest;
use App\Notifications\ProductApproved;
use App\Notifications\ProductRejected;
use App\Notifications\PurchaseConfirmation;
use App\Notifications\NewSale;
use App\Notifications\WithdrawalApproved;
use App\Notifications\WithdrawalRejected;
use App\Notifications\SellerRoleRequested;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send purchase confirmation notification to customer
     */
    public function sendPurchaseConfirmation(User $customer, Transaction $transaction, array $downloadLinks): void
    {
        $customer->notify(new PurchaseConfirmation($transaction, $downloadLinks));
    }

    /**
     * Send new sale notification to seller
     */
    public function sendNewSaleNotification(User $seller, TransactionItem $transactionItem): void
    {
        $seller->notify(new NewSale($transactionItem));
    }

    /**
     * Send product approved notification to seller
     */
    public function sendProductApprovedNotification(User $seller, Product $product): void
    {
        $seller->notify(new ProductApproved($product));
    }

    /**
     * Send product rejected notification to seller
     */
    public function sendProductRejectedNotification(User $seller, Product $product, string $reason): void
    {
        $seller->notify(new ProductRejected($product, $reason));
    }

    /**
     * Send withdrawal approved notification to seller
     */
    public function sendWithdrawalApprovedNotification(User $seller, Withdrawal $withdrawal): void
    {
        $seller->notify(new WithdrawalApproved($withdrawal));
    }

    /**
     * Send withdrawal rejected notification to seller
     */
    public function sendWithdrawalRejectedNotification(User $seller, Withdrawal $withdrawal, string $reason): void
    {
        $seller->notify(new WithdrawalRejected($withdrawal, $reason));
    }

    /**
     * Send seller role requested notification to all admins
     */
    public function sendSellerRoleRequestedNotification(SellerRoleRequest $sellerRoleRequest, User $requestingUser): void
    {
        $admins = User::where('role', 'admin')->get();
        
        Notification::send($admins, new SellerRoleRequested($sellerRoleRequest, $requestingUser));
    }
}
