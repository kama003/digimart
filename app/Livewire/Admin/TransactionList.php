<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status_filter = '';
    public string $date_from = '';
    public string $date_to = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status_filter = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::query()
            ->with(['user', 'transactionItems.product']);

        // Search by customer name or email
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by status
        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        // Filter by date range
        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // Calculate totals
        $totalQuery = Transaction::query()->where('status', 'completed');

        if ($this->search) {
            $totalQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status_filter) {
            $totalQuery->where('status', $this->status_filter);
        }

        if ($this->date_from) {
            $totalQuery->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $totalQuery->whereDate('created_at', '<=', $this->date_to);
        }

        $totalPlatformRevenue = $totalQuery->sum('commission');
        $totalSellerPayouts = $totalQuery->sum('seller_amount');

        return view('livewire.admin.transaction-list', [
            'transactions' => $transactions,
            'totalPlatformRevenue' => $totalPlatformRevenue,
            'totalSellerPayouts' => $totalSellerPayouts,
        ]);
    }
}
