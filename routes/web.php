<?php

use App\Livewire\Dashboard\Index as DashboardIndex;

use App\Livewire\Management\Users\Index as UsersIndex;
use App\Livewire\Management\Users\Create as UsersCreate;
use App\Livewire\Management\Users\Edit as UsersEdit;
use App\Livewire\Management\Roles\Index as RolesIndex;
use App\Livewire\Management\Roles\Create as RolesCreate;
use App\Livewire\Management\Roles\Edit as RolesEdit;
use App\Livewire\Management\Organizations\Index as OrganizationsIndex;
use App\Livewire\Management\Organizations\Create as OrganizationsCreate;
use App\Livewire\Management\Organizations\Edit as OrganizationsEdit;
use App\Livewire\Management\Categories\Index as CategoriesIndex;
use App\Livewire\Management\Categories\Create as CategoriesCreate;
use App\Livewire\Management\Categories\Edit as CategoriesEdit;
use App\Livewire\Management\Datasets\Index as ManagementDatasetsIndex;
use App\Livewire\Management\Datasets\Create as ManagementDatasetsCreate;
use App\Livewire\Management\Datasets\Edit as ManagementDatasetsEdit;
use App\Livewire\Management\Datasets\Show as ManagementDatasetsShow;

use App\Livewire\Datasets\Index as DatasetsIndex;
use App\Livewire\Datasets\Show as DatasetsShow;

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

Route::get('/datasets', DatasetsIndex::class)->name('datasets.index');
Route::get('/datasets/{dataset:slug}', DatasetsShow::class)->name('datasets.show');

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
    Route::get('/management/datasets/{id}/edit', ManagementDatasetsEdit::class)->name('management.datasets.edit');

    // Route::get('/management/organizations', OrganizationsIndex::class)->name('management.organizations.index');
    // Route::get('/management/organizations/create', OrganizationsCreate::class)->name('management.organizations.create');
    // Route::get('/management/organizations/{organization}/edit', OrganizationsEdit::class)->name('management.organizations.edit');

    // Route::get('/management/categories', CategoriesIndex::class)->name('management.categories.index');
    // Route::get('/management/categories/create', CategoriesCreate::class)->name('management.categories.create');
    // Route::get('/management/categories/{category}/edit', CategoriesEdit::class)->name('management.categories.edit');

// Categories
    Route::get('management/categories', \App\Livewire\Management\Categories\Index::class)->name('management.categories.index');
    Route::get('management/categories/create', \App\Livewire\Management\Categories\Create::class)->name('management.categories.create');
    Route::get('management/categories/{category}/edit', \App\Livewire\Management\Categories\Edit::class)->name('management.categories.edit');

    // Organizations
    Route::get('management/organizations', \App\Livewire\Management\Organizations\Index::class)->name('management.organizations.index');
    Route::get('management/organizations/create', \App\Livewire\Management\Organizations\Create::class)->name('management.organizations.create');
    Route::get('management/organizations/{organization}/edit', \App\Livewire\Management\Organizations\Edit::class)->name('management.organizations.edit');

});


require __DIR__.'/auth.php';
