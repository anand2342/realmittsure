<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearch extends Component
{
    public $role; // Role slug passed from the parent view
    public $search = ''; // Holds the search input

    public function render()
    {
        // Fetch users based on role and search input
        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('user_roles')
                ->where('role_slug', $this->role);
        })
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->get();

        return view('livewire.user-search', ['userNames' => $users]);
    }
}
