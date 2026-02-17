<?php

use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UsersCreate;
use App\Livewire\Users\Edit as UsersEdit;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Roles\Create as RolesCreate;
use App\Livewire\Roles\Edit as RolesEdit;
use App\Livewire\Datasets\Index as DatasetsIndex;
use App\Livewire\Datasets\Show as DatasetsShow;

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// User Management Routes
Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/create', UsersCreate::class)->name('users.create');
    Route::get('/users/{user}/edit', UsersEdit::class)->name('users.edit');

    Route::get('/roles', RolesIndex::class)->name('roles.index');
    Route::get('/roles/create', RolesCreate::class)->name('roles.create');
    Route::get('/roles/{role}/edit', RolesEdit::class)->name('roles.edit');

    // Dataset Routes
    Route::get('/datasets', DatasetsIndex::class)->name('datasets.index');
    Route::get('/datasets/{dataset:slug}', DatasetsShow::class)->name('datasets.show');
});


require __DIR__.'/auth.php';
