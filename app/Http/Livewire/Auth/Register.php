<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $lastname = '';
    public $firstname = '';

    public function register() {

        $data = $this->validate([
            'lastname'=> 'required',
            'firstname'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|same:passwordConfirmation'
        ]);

        $user= User::create([
            'lastname' => $data['lastname'],
            'firstname' => $data['firstname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        //auth()->login($user);
        return redirect('/index');
    }
    public function updatedEmail() {
        //dd($field);
        $this->validate(['email'=>'unique:users']);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}
