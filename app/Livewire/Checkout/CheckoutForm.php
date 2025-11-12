<?php

namespace App\Livewire\Checkout;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Transaction;
use App\Services\CartService;
use App\Services\Payment\PaytmPaymentGateway;
use App\Services\Payment\StripePaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CheckoutForm extends Component
{
    public string $payment_gateway = 'stripe';
    public ?string $clientSecret = null;
    public ?string $paymentId = null;
    public ?string $errorMessage = null;
    public array $cartItems = [];
    public float $total = 0;
    public float $commission = 0;
    public float $sellerAmount = 0;

    protected $rules = [
        'payment_gateway' => 'required|in:stripe,paytm',
    ];

    public function mount(CartService $cartService)
    {
        // Check if cart is empty
        $items = $cartService->getItems();
        
        if ($items->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('cart.index');
        }

        $this->cartItems = $items->toArray();
        $this->total = $cartService->getTotal();
        
        // Calculate commission and seller amount
        $commissionPercent = config('payment.commission_percent', 10);
        $this->commission = ($this->total * $commissionPercent) / 100;
        $this->sellerAmount = $this->total - $this->commission;
    }

    public function processCheckout()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create pending transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'payment_gateway' => $this->payment_gateway,
                'payment_id' => '', // Will be updated after payment intent creation
                'amount' => $this->total,
                'commission' => $this->commission,
                'seller_amount' => $this->sellerAmount,
                'status' => 'pending',
                'metadata' => [
                    'cart_items' => $this->cartItems,
                    'user_email' => Auth::user()->email,
                    'user_name' => Auth::user()->name,
                ],
            ]);

            // Get payment gateway instance
            $gateway = $this->getPaymentGateway();

            // Create payment intent
            $paymentData = $gateway->createPaymentIntent($this->total, [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
            ]);

            // Update transaction with payment ID
            $transaction->update([
                'payment_id' => $paymentData['payment_id'],
            ]);

            $this->paymentId = $paymentData['payment_id'];
            $this->clientSecret = $paymentData['client_secret'];

            DB::commit();

            // Dispatch browser event to initialize payment form
            $this->dispatch('payment-intent-created', [
                'clientSecret' => $this->clientSecret,
                'gateway' => $this->payment_gateway,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::channel('payment')->error('Checkout process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'payment_gateway' => $this->payment_gateway,
                'amount' => $this->total,
                'timestamp' => now()->toIso8601String(),
            ]);

            $this->errorMessage = 'Payment processing failed. Please try again.';
        }
    }

    public function handlePaymentSuccess()
    {
        if (!$this->paymentId) {
            $this->errorMessage = 'Invalid payment session.';
            return;
        }

        try {
            $transaction = Transaction::where('payment_id', $this->paymentId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$transaction) {
                $this->errorMessage = 'Transaction not found.';
                return;
            }

            // Redirect to success page
            session()->flash('success', 'Payment successful! Your purchase is being processed.');
            return redirect()->route('checkout.success', ['transaction' => $transaction->id]);

        } catch (\Exception $e) {
            Log::channel('payment')->error('Payment success handling failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payment_id' => $this->paymentId,
                'user_id' => Auth::id(),
                'timestamp' => now()->toIso8601String(),
            ]);

            $this->errorMessage = 'An error occurred while processing your payment. Please contact support.';
        }
    }

    public function handlePaymentFailure(string $error)
    {
        Log::channel('payment')->warning('Payment failed', [
            'error' => $error,
            'payment_id' => $this->paymentId,
            'user_id' => Auth::id(),
            'payment_gateway' => $this->payment_gateway,
            'amount' => $this->total,
            'timestamp' => now()->toIso8601String(),
        ]);

        $this->errorMessage = 'Payment failed: ' . $error;
        
        // Reset payment state
        $this->clientSecret = null;
        $this->paymentId = null;
    }

    private function getPaymentGateway(): PaymentGatewayInterface
    {
        return match ($this->payment_gateway) {
            'stripe' => app(StripePaymentGateway::class),
            'paytm' => app(PaytmPaymentGateway::class),
            default => throw new \Exception('Invalid payment gateway'),
        };
    }

    public function render()
    {
        return view('livewire.checkout.checkout-form');
    }
}
