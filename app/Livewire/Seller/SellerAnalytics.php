<?php

namespace App\Livewire\Seller;

use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SellerAnalytics extends Component
{
    public string $period = 'monthly';

    public function render()
    {
        $sellerId = auth()->id();

        // Query TransactionItem grouped by date for revenue trends
        $revenueTrends = $this->getRevenueTrends($sellerId);

        // Calculate sales metrics
        $totalSales = TransactionItem::where('seller_id', $sellerId)->count();
        
        $averageOrderValue = TransactionItem::where('seller_id', $sellerId)
            ->avg('seller_amount') ?? 0;

        // Calculate conversion rate (products with sales / total products)
        $productsWithSales = TransactionItem::where('seller_id', $sellerId)
            ->distinct('product_id')
            ->count('product_id');
        
        $totalProducts = auth()->user()->products()->count();
        $conversionRate = $totalProducts > 0 ? ($productsWithSales / $totalProducts) * 100 : 0;

        // Prepare data for charts
        $chartData = $this->prepareChartData($revenueTrends);

        return view('livewire.seller.seller-analytics', [
            'totalSales' => $totalSales,
            'averageOrderValue' => $averageOrderValue,
            'conversionRate' => $conversionRate,
            'chartData' => $chartData,
            'revenueTrends' => $revenueTrends,
        ]);
    }

    protected function getRevenueTrends($sellerId)
    {
        $dateFormat = match ($this->period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m',
        };

        $daysBack = match ($this->period) {
            'daily' => 30,
            'weekly' => 84, // 12 weeks
            'monthly' => 365, // 12 months
            default => 365,
        };

        return TransactionItem::where('seller_id', $sellerId)
            ->where('created_at', '>=', now()->subDays($daysBack))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                DB::raw('SUM(seller_amount) as revenue'),
                DB::raw('COUNT(*) as sales_count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    protected function prepareChartData($revenueTrends)
    {
        $labels = [];
        $revenueData = [];
        $salesData = [];

        foreach ($revenueTrends as $trend) {
            $labels[] = $this->formatPeriodLabel($trend->period);
            $revenueData[] = (float) $trend->revenue;
            $salesData[] = $trend->sales_count;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'sales' => $salesData,
        ];
    }

    protected function formatPeriodLabel($period)
    {
        return match ($this->period) {
            'daily' => date('M d', strtotime($period)),
            'weekly' => 'Week ' . substr($period, -2),
            'monthly' => date('M Y', strtotime($period . '-01')),
            default => $period,
        };
    }

    public function setPeriod($period)
    {
        $this->period = $period;
    }
}
