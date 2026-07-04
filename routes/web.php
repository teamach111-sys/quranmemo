<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'can:view-notes'])->group(function () {
    Route::livewire('notes', '⚡note')->name('notes');
});

Route::middleware(['auth', 'verified', 'can:admin'])->group(function () {
    Route::livewire('configuration', '⚡para_etab')->name('parametres-etablissement');
    Route::livewire('roles', '⚡donnerrole')->name('roles');
    Route::livewire('createannee', '⚡createannee')->name('createannee');
});
Route::middleware(['auth', 'verified', 'can:view-etudiants'])->group(function () {
    Route::livewire('createetudiant', '⚡createetudiant')->name('createetudiant');
    Route::livewire('etudiants', '⚡etudiants')->name('etudiants');

});
Route::middleware(['auth', 'verified', 'can:view-programme'])->group(function () {
    Route::livewire('programmes', '⚡programme')->name('programme');
});

require __DIR__.'/settings.php';
