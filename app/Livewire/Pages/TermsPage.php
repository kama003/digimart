<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Terms of Service')]
class TermsPage extends Component
{
    public function render()
    {
        return view('livewire.pages.terms-page');
    }
}
