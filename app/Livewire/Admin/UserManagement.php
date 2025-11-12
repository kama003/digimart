<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $role_filter = '';
    
    public $showBanModal = false;
    public $showChangeRoleModal = false;
    public $selectedUserId = null;
    public $selectedUser = null;
    public $newRole = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'role_filter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openBanModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::findOrFail($userId);
        $this->showBanModal = true;
    }

    public function openChangeRoleModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::findOrFail($userId);
        $this->newRole = $this->selectedUser->role->value;
        $this->showChangeRoleModal = true;
    }

    public function closeModals()
    {
        $this->showBanModal = false;
        $this->showChangeRoleModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->newRole = '';
    }

    public function toggleBan()
    {
        $user = User::findOrFail($this->selectedUserId);

        // Authorize the action
        Gate::authorize('ban', $user);

        $wasBanned = $user->is_banned;
        
        $user->update([
            'is_banned' => !$user->is_banned,
        ]);

        $action = $wasBanned ? 'unbanned' : 'banned';
        session()->flash('success', "User has been {$action} successfully.");
        
        $this->closeModals();
    }

    public function changeRole()
    {
        $user = User::findOrFail($this->selectedUserId);

        // Authorize the action
        Gate::authorize('changeRole', $user);

        // Validate the new role
        if (!in_array($this->newRole, ['customer', 'seller', 'admin'])) {
            session()->flash('error', 'Invalid role selected.');
            return;
        }

        // Don't allow changing if it's the same role
        if ($user->role->value === $this->newRole) {
            session()->flash('error', 'User already has this role.');
            $this->closeModals();
            return;
        }

        $user->update([
            'role' => $this->newRole,
        ]);

        session()->flash('success', "User role has been changed to {$this->newRole} successfully.");
        
        $this->closeModals();
    }

    public function render()
    {
        // Authorize admin access
        Gate::authorize('viewAny', User::class);

        $query = User::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if ($this->role_filter) {
            $query->where('role', $this->role_filter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }
}
