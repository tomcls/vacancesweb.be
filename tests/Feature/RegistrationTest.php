<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
  
     /** @test */
    function can_register()
    {

        Livewire::test('auth.register')
            ->set('email', 'tom3@itcl.io')
            ->set('name', 'Thomas')
            ->set('password', '111111')
            ->set('passwordConfirmation', '111111')
            ->call('register')
            ->assertRedirect('/index');
        $this->assertTrue(User::whereEmail('tom3@itcl.io')->exists());
       // $this->assertEquals('tom3@itcl.io', auth()->user()->email);
    }
    /** @test */
    function email_is_required()
    {

        Livewire::test('auth.register')
            ->set('email', '')
            ->set('name', 'Thomas')
            ->set('password', '123456')
            ->set('passwordConfirmation', '123456')
            ->call('register')
            ->assertHasErrors(['email' => 'required']);
    }
    /** @test */
    function email_is_valid_email()
    {
        $user= User::create([
            'name' => 'thomas',
            'email' => 'thomas.claessensimmovlan.be',
            'password' => Hash::make('password'),
        ]);

        Livewire::test('auth.register')
            ->set('email', 'thomas.claessensimmovlan.be')
            ->set('name', 'Thomas')
            ->set('password', '123456')
            ->set('passwordConfirmation', '123456')
            ->call('register')
            ->assertHasErrors(['email' => 'email']);
    }
     /** @test */
    function password_is_required()
    {
        Livewire::test('auth.register')
            ->set('email', 'calebporzio@gmail.com')
            ->set('password', '')
            ->set('passwordConfirmation', 'password')
            ->call('register')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    function password_is_minimum_of_six_characters()
    {
        Livewire::test('auth.register')
            ->set('email', 'calebporzio@gmail.com')
            ->set('password', 'secre')
            ->set('passwordConfirmation', 'password')
            ->call('register')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    function password_matches_password_confirmation()
    {
        Livewire::test('auth.register')
            ->set('email', 'calebporzio@gmail.com')
            ->set('password', 'password')
            ->set('passwordConfirmation', 'not-secret')
            ->call('register')
            ->assertHasErrors(['password' => 'same']);
    }
    /** @test */
    function see_email_hasnt_already_been_taken_validation_message_as_user_types()
    {
        User::create([
            'name' => 'calebporzio',
            'email' => 'calebporzio@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Livewire::test('auth.register')
            ->set('email', 'calebporzi@gmail.com')
            ->assertHasNoErrors()
            ->set('email', 'calebporzio@gmail.com')
            ->assertHasErrors(['email' => 'unique']);
    }

}
