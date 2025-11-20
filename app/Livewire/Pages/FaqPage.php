<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Frequently Asked Questions')]
class FaqPage extends Component
{
    public $openSection = null;

    public function toggleSection($section)
    {
        $this->openSection = $this->openSection === $section ? null : $section;
    }

    public function render()
    {
        return view('livewire.pages.faq-page');
    }
}
