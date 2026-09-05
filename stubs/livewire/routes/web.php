<?php

use App\Livewire\Dashboard;
use App\Livewire\Profile\Edit as ProfileEdit;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('profile', ProfileEdit::class)
    ->middleware('auth')
    ->name('profile.edit');

require __DIR__.'/auth.php';
