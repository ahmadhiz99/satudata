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
use App\Livewire\Management\Datasets\Index as ManagementDatasetsIndex;
use App\Livewire\Management\Datasets\Create as ManagementDatasetsCreate;
use App\Livewire\Management\Datasets\Edit as ManagementDatasetsEdit;
use App\Livewire\Management\Datasets\Show as ManagementDatasetsShow;
use App\Livewire\Opd\Index as OpdDatasetsIndex;
use App\Livewire\Opd\Show as OpdDatasetsShow;
use App\Livewire\Sector\Index as SectorIndex;
use App\Livewire\Datasektoral\Index as DatasektoralIndex;
use App\Livewire\Datasektoral\Show as DatasektoralShow;
use App\Livewire\Home;

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');
Route::get('/', Home::class)->name('home');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// User Management Routes
Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
Route::get('/management/datasets', ManagementDatasetsIndex::class)->name('management.datasets.index');
Route::get('/opd/datasets', OpdDatasetsIndex::class)->name('opd.datasets.index');
Route::get('/opd/datasets/{dataset:slug}', OpdDatasetsShow::class)->name('opd.datasets.show');
Route::get('/sectors', SectorIndex::class)->name('sectors.index');
Route::get('/datasektoral', DatasektoralIndex::class)->name('datasektoral.index');
Route::get('/datasektoral/{dataset}', DatasektoralShow::class)->name('datasektoral.show');

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

    Route::get('/management/datasets/create', ManagementDatasetsCreate::class)->name('management.datasets.create');
    Route::get('/management/datasets', ManagementDatasetsIndex::class)->name('management.datasets.index');
    Route::get('/management/datasets/{upload}', ManagementDatasetsShow::class)->name('management.datasets.show');
    Route::get('/management/datasets/{dataset}/edit', ManagementDatasetsEdit::class)->name('management.datasets.edit');
});


require __DIR__.'/auth.php';
