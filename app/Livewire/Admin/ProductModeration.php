<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Notifications\ProductApproved;
use App\Notifications\ProductRejected;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ProductModeration extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status_filter = '';
    
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $selectedProductId = null;
    public $selectedProduct = null;
    public $rejectionReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openApproveModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->selectedProduct = Product::with('user')->findOrFail($productId);
        $this->showApproveModal = true;
    }

    public function openRejectModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->selectedProduct = Product::with('user')->findOrFail($productId);
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeModals()
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->selectedProductId = null;
        $this->selectedProduct = null;
        $this->rejectionReason = '';
    }

    public function approve()
    {
        $product = Product::with('user')->findOrFail($this->selectedProductId);

        // Authorize the action
        Gate::authorize('approve', $product);

        // Update product status
        $product->update([
            'is_approved' => true,
            'rejection_reason' => null,
        ]);

        // Send notification to seller
        $product->user->notify(new ProductApproved($product));

        session()->flash('success', 'Product has been approved successfully.');
        
        $this->closeModals();
    }

    public function reject()
    {
        // Validate rejection reason
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
            'rejectionReason.min' => 'The rejection reason must be at least 10 characters.',
            'rejectionReason.max' => 'The rejection reason must not exceed 500 characters.',
        ]);

        $product = Product::with('user')->findOrFail($this->selectedProductId);

        // Authorize the action
        Gate::authorize('reject', $product);

        // Update product status
        $product->update([
            'is_approved' => false,
            'rejection_reason' => $this->rejectionReason,
        ]);

        // Send notification to seller
        $product->user->notify(new ProductRejected($product, $this->rejectionReason));

        session()->flash('success', 'Product has been rejected.');
        
        $this->closeModals();
    }

    public function render()
    {
        // Authorize admin access
        Gate::authorize('viewAny', Product::class);

        $query = Product::with(['user', 'category'])
            ->withTrashed();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Apply status filter
        if ($this->status_filter) {
            switch ($this->status_filter) {
                case 'pending':
                    $query->where('is_approved', false)
                        ->where('is_active', true)
                        ->whereNull('deleted_at');
                    break;
                case 'approved':
                    $query->where('is_approved', true)
                        ->whereNull('deleted_at');
                    break;
                case 'rejected':
                    $query->where('is_approved', false)
                        ->whereNotNull('rejection_reason')
                        ->whereNull('deleted_at');
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.product-moderation', [
            'products' => $products,
        ]);
    }
}
