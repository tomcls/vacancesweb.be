<?php

namespace Tests\Feature;

use App\Models\User ;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_see_livewire_profile_component_on_profile_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get('/profile')
            ->assertStatus(200);
            // ->assertSuccessful()
            // ->assertSeeLivewire(Profile::class)
    }

    /** @test */
    function can_update_profile()
    {
        $user =  User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile')
            ->set('firstname', 'foo')
            ->set('lastname', 'bar')
            ->call('save');

        $user->refresh();

        $this->assertEquals('foo', $user->firstname);
        $this->assertEquals('bar', $user->lastname);
    }

    /** @test */
    function username_must_less_than_24_characters()
    {
        $user =  User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile')
            ->set('firstname', str_repeat('a', 25))
            ->set('lastname', 'bar')
            ->call('save')
            ->assertHasErrors(['firstname' => 'max']);
    }

    /** @test */
    function about_must_less_than_140_characters()
    {
        $user =  User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile')
            ->set('firstname', 'foo')
            ->set('lastname', str_repeat('a', 141))
            ->call('save')
            ->assertHasErrors(['lastname' => 'max']);
    }

    /** @test */
    function can_upload_avatar()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.png');

        Storage::fake('avatars');

        Livewire::actingAs($user)
            ->test('profile')
            ->set('upload', $file)
            ->call('save');

        $user->refresh();

        $this->assertNotNull($user->avatar);
        Storage::disk('avatars')->assertExists($user->avatar);
    }

    /** @test */
    function profile_info_is_pre_populated()
    {
        $user = User::factory()->create([
            'username' => 'foo',
            'about' => 'bar',
        ]);

        Livewire::actingAs($user)
            ->test('profile')
            ->assertSet('user.username', 'foo')
            ->assertSet('user.about', 'bar');
    }

    /** @test */
    function message_is_shown_on_save()
    {
        $user = User::factory()->create([
            'username' => 'foo',
            'about' => 'bar',
        ]);

        Livewire::actingAs($user)
            ->test('profile')
            ->call('save')
            ->assertEmitted('notify-saved');
    }

    /** @test */
    function username_must_be_less_than_24_characters()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile')
            ->set('user.username', str_repeat('a', 25))
            ->set('user.about', 'bar')
            ->call('save')
            ->assertHasErrors(['user.username' => 'max']);
    }

    /** @test */
    function about_must_be_less_than_140_characters()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile')
            ->set('user.username', 'foo')
            ->set('user.about', str_repeat('a', 141))
            ->call('save')
            ->assertHasErrors(['user.about' => 'max']);
    }
}