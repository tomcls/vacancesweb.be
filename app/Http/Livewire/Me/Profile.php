<?php

namespace App\Http\Livewire\Me;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public User $user;
    public $upload;


    protected $rules = [
        'user.firstname' => 'max:140',
        'user.lastname' => 'max:140',
        'user.email' => 'email',
        'user.phone' => 'nullable',
        'user.code' => 'nullable',
        'user.company_name' => 'nullable',
        'user.company_vat' => 'nullable',
        'user.lang' => 'nullable',
        'user.avatar' => 'nullable',
        'upload' => 'nullable|image|max:300',
    ];

    public function mount() { $this->user = auth()->user(); }

    public function save()
    {
        $this->validate();

        $this->user->save();

        $this->upload && $this->user->update([
            'avatar' => $this->upload->store('/', 'avatars'),
        ]);

        $this->notify(['message'=>'User well saved','type'=>'success']);
    }
    public function render()
    {
        return view('livewire.me.profile')->layout('layouts.me');
    }
}
