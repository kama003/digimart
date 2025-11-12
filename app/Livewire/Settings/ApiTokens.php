<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Validate;
use Livewire\Component;

class ApiTokens extends Component
{
    #[Validate('required|string|max:255')]
    public string $tokenName = '';
    
    public ?string $plainTextToken = null;
    
    public function createToken(): void
    {
        $this->validate();
        
        $token = auth()->user()->createToken($this->tokenName);
        
        $this->plainTextToken = $token->plainTextToken;
        $this->tokenName = '';
        
        $this->dispatch('token-created');
    }
    
    public function revokeToken(int $tokenId): void
    {
        auth()->user()->tokens()->where('id', $tokenId)->delete();
        
        $this->dispatch('token-revoked');
    }
    
    public function closeTokenModal(): void
    {
        $this->plainTextToken = null;
    }
    
    public function render()
    {
        return view('livewire.settings.api-tokens', [
            'tokens' => auth()->user()->tokens()->latest()->get(),
        ]);
    }
}
