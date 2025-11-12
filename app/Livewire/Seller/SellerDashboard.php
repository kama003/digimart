<?php

namespace App\Livewire\Seller;

use App\Models\Download;
use App\Models\TransactionItem;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SellerDashboard extends Component
{
    public function render()
    {
        $sellerId = auth()->id();

        // Calculate total revenue from TransactionItem where seller_id = auth()->id()
        $totalRevenue = TransactionItem::where('seller_id', $sellerId)
            ->sum('seller_amount');

        // Calculate total downloads from Download where product.user_id = auth()->id()
        $totalDownloads = Download::whereHas('product', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })->count();

        // Query top-performing products by revenue
        $topProductsByRevenue = TransactionItem::where('seller_id', $sellerId)
            ->select('product_id', DB::raw('SUM(seller_amount) as total_revenue'), DB::raw('COUNT(*) as sales_count'))
            ->with('product:id,title,slug,thumbnail_path,price')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Query top-performing products by downloads
        $topProductsByDownloads = Download::whereHas('product', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })
            ->select('product_id', DB::raw('COUNT(*) as download_count'))
            ->with('product:id,title,slug,thumbnail_path,price')
            ->groupBy('product_id')
            ->orderByDesc('download_count')
            ->limit(5)
            ->get();

        // Display pending balance (auth()->user()->balance)
        $pendingBalance = auth()->user()->balance;

        // Show recent completed withdrawals
        $recentWithdrawals = Withdrawal::where('user_id', $sellerId)
            ->whereIn('status', ['approved', 'completed'])
            ->orderByDesc('processed_at')
            ->limit(5)
            ->get();

        return view('livewire.seller.seller-dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalDownloads' => $totalDownloads,
            'topProductsByRevenue' => $topProductsByRevenue,
            'topProductsByDownloads' => $topProductsByDownloads,
            'pendingBalance' => $pendingBalance,
            'recentWithdrawals' => $recentWithdrawals,
        ]);
    }
}
