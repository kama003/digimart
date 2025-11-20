<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Privacy Policy')]
class PrivacyPage extends Component
{
    public function render()
    {
        return view('livewire.pages.privacy-page');
    }
}
