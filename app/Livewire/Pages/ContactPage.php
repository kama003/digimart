<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Notifications\ContactFormSubmitted;
use Illuminate\Support\Facades\Notification;

#[Layout('components.layouts.guest')]
#[Title('Contact Us')]
class ContactPage extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:255')]
    public $subject = '';

    #[Validate('required|string|min:10')]
    public $message = '';

    public function submit()
    {
        $this->validate();

        // Send notification to admin email
        $adminEmail = config('mail.from.address');
        Notification::route('mail', $adminEmail)
            ->notify(new ContactFormSubmitted([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]));

        session()->flash('success', 'Thank you for contacting us! We will get back to you soon.');

        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.pages.contact-page');
    }
}
