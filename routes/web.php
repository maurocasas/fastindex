<?php

use App\Livewire\Auth\Login;
use App\Livewire\Pages;
use App\Livewire\ServiceAccounts;
use App\Livewire\Settings;
use App\Livewire\Team;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::middleware(RedirectIfAuthenticated::class)
    ->group(function () {
        Route::get('/login', Login::class)
            ->name('login');
    });

Route::middleware(Authenticate::class)
    ->group(function () {
        Route::get('sites/{site}/{tab?}', App\Livewire\Sites\Show::class)
            ->name('sites.show');

        Route::get('/', App\Livewire\Sites\Index::class)
            ->name('dashboard');

        Route::get('pages/{site?}', Pages::class)
            ->name('pages');

        Route::get('account', App\Livewire\Account::class)
            ->name('account');

        Route::get('service-accounts', ServiceAccounts::class)
            ->can('admin')
            ->name('service-accounts');

        Route::get('settings', Settings::class)
            ->can('admin')
            ->name('settings');

        Route::get('team', Team::class)
            ->can('admin')
            ->name('team');
    });
