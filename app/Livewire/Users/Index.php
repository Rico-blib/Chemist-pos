<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::all();
    }

    public function updateRole($userId, $role)
    {
        $user = User::findOrFail($userId);
        $user->role = $role;
        $user->save();

        $this->users = User::all();
        session()->flash('success', 'User role updated successfully.');
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
