<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public bool $confirmingUserDeletion = false;

    public string $password = '';

    public function confirmUserDeletion(): void
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(): void
    {
        $this->validate(['password' => ['required', 'string', 'current_password']]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.delete-user-form');
    }
}
