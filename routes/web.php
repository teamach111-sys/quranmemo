<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('etudiants', '⚡etudiants')->name('etudiants');
    Route::livewire('createetudiant', '⚡createetudiant')->name('createetudiant');
    Route::livewire('configuration', '⚡para_etab')->name('parametres-etablissement');
});

require __DIR__.'/settings.php';
