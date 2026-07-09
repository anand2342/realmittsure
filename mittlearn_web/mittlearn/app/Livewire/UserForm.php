<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserForm extends Component
{
    public $selectedRole = null;  // This will hold the selected role
    public $roles = [
        'admin' => 'Admin',
        'parent' => 'Parent',
        'school_admin' => 'School Admin',
        'student' => 'Student',
        'super_admin' => 'Super Admin',
        'teacher' => 'Teacher',
        'user' => 'User',
    ];

    // Optionally, pass the role to edit
    public $userRole;

    // You can add lifecycle hooks to initialize the selected role for editing purposes
    public function mount($userRole = null)
    {
        // If editing a user, set the selectedRole to the user's current role
        if ($userRole) {
            $this->selectedRole = $userRole;
        }
    }

    // You can add custom actions for role changes if needed
    public function roleChanged()
    {
        // You can handle any custom logic when the role changes
        // For now, it's not needed unless you want to perform actions on role change.
    }

    public function render()
    {
        return view('livewire.role-form');
    }
}
