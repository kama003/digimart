<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Seller Guide')]
class SellerGuidePage extends Component
{
    public function render()
    {
        return view('livewire.pages.seller-guide-page');
    }
}
