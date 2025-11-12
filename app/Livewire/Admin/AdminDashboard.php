<?php

namespace App\Livewire\Admin;

use App\Models\Download;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public string $period = 'monthly';

    public function setPeriod(string $period)
    {
        $this->period = $period;
    }

    public function render()
    {
        // Calculate date range based on period
        $dateFrom = match ($this->period) {
            'daily' => now()->subDays(30),
            'weekly' => now()->subWeeks(12),
            'monthly' => now()->subMonths(12),
            default => null, // 'all' - no date filter
        };

        // Calculate total platform revenue (sum of commission)
        $totalPlatformRevenue = Transaction::query()
            ->where('status', 'completed')
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom))
            ->sum('commission');

        // Calculate total transactions count
        $totalTransactions = Transaction::query()
            ->where('status', 'completed')
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom))
            ->count();

        // Query top-selling products
        $topSellingProducts = TransactionItem::query()
            ->select('product_id', DB::raw('SUM(price) as total_sales'), DB::raw('COUNT(*) as sales_count'))
            ->with('product:id,title,slug,thumbnail_path,price')
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->whereHas('transaction', fn($query) => $query->where('created_at', '>=', $dateFrom));
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        // Query top-performing sellers
        $topPerformingSellers = TransactionItem::query()
            ->select('seller_id', DB::raw('SUM(seller_amount) as total_earnings'), DB::raw('COUNT(*) as sales_count'))
            ->with('seller:id,name,email')
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->whereHas('transaction', fn($query) => $query->where('created_at', '>=', $dateFrom));
            })
            ->groupBy('seller_id')
            ->orderByDesc('total_earnings')
            ->limit(10)
            ->get();

        // Calculate total downloads count
        $totalDownloads = Download::query()
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom))
            ->count();

        // Count active users by role
        $usersByRole = User::query()
            ->select('role', DB::raw('COUNT(*) as count'))
            ->where('is_banned', false)
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Prepare revenue trend data grouped by date for charts
        $revenueTrendData = $this->getRevenueTrendData($dateFrom);

        return view('livewire.admin.admin-dashboard', [
            'totalPlatformRevenue' => $totalPlatformRevenue,
            'totalTransactions' => $totalTransactions,
            'topSellingProducts' => $topSellingProducts,
            'topPerformingSellers' => $topPerformingSellers,
            'totalDownloads' => $totalDownloads,
            'usersByRole' => $usersByRole,
            'chartData' => $revenueTrendData,
        ]);
    }

    private function getRevenueTrendData($dateFrom)
    {
        $groupBy = match ($this->period) {
            'daily' => DB::raw('DATE(created_at)'),
            'weekly' => DB::raw('YEARWEEK(created_at, 1)'),
            'monthly' => DB::raw('DATE_FORMAT(created_at, "%Y-%m")'),
            default => DB::raw('DATE_FORMAT(created_at, "%Y-%m")'),
        };

        $dateFormat = match ($this->period) {
            'daily' => 'Y-m-d',
            'weekly' => 'Y-W',
            'monthly' => 'Y-m',
            default => 'Y-m',
        };

        $transactions = Transaction::query()
            ->select(
                DB::raw('DATE_FORMAT(created_at, "' . $dateFormat . '") as period'),
                DB::raw('SUM(commission) as revenue'),
                DB::raw('SUM(amount) as total_sales'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('status', 'completed')
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $labels = [];
        $revenue = [];
        $totalSales = [];
        $transactionCounts = [];

        foreach ($transactions as $transaction) {
            $labels[] = $this->formatPeriodLabel($transaction->period);
            $revenue[] = (float) $transaction->revenue;
            $totalSales[] = (float) $transaction->total_sales;
            $transactionCounts[] = $transaction->transaction_count;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'totalSales' => $totalSales,
            'transactionCounts' => $transactionCounts,
        ];
    }

    private function formatPeriodLabel(string $period): string
    {
        return match ($this->period) {
            'daily' => date('M d', strtotime($period)),
            'weekly' => 'Week ' . substr($period, -2),
            'monthly' => date('M Y', strtotime($period . '-01')),
            default => $period,
        };
    }
}
