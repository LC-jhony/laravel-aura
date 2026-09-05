<?php

use App\Livewire\Profile\DeleteUserForm;
use App\Livewire\Profile\UpdatePasswordForm;
use App\Livewire\Profile\UpdateProfileInformationForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs(User::factory()->create())->get('/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(UpdateProfileInformationForm::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $user->refresh();
    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

test('password can be updated', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(UpdatePasswordForm::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword');

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(DeleteUserForm::class)
        ->set('password', 'password')
        ->call('deleteUser');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});
