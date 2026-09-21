<?php

declare(strict_types=1);

use App\Http\Controllers\EtudiantExportController;
use App\Http\Controllers\EtudiantPdfController;
use App\Http\Controllers\EtudiantTemplateController;
use App\Http\Controllers\NotePdfController;
use App\Http\Controllers\SuiviExportController;
use App\Http\Controllers\SuiviPdfController;
use App\Http\Controllers\SuiviTemplateController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'can:view-notes'])->group(function() {
    Route::livewire('notes', '⚡note')->name('notes');
    Route::get('notes/pdf', NotePdfController::class)->name('notes.pdf');
    Route::livewire('absence', '⚡absence')->name('absence');
    Route::livewire('suivi', '⚡suivi')->name('suivi');
    Route::get('suivi/export', SuiviExportController::class)->name('suivi.export');
    Route::get('suivi/pdf', SuiviPdfController::class)->name('suivi.pdf');
    Route::get('suivi/template', SuiviTemplateController::class)->name('suivi.template');
});

Route::middleware(['auth', 'verified', 'can:admin'])->group(function() {
    Route::livewire('configuration', '⚡para_etab')->name('parametres-etablissement');
    Route::livewire('roles', '⚡donnerrole')->name('roles');
    Route::livewire('createannee', '⚡createannee')->name('createannee');
});
Route::middleware(['auth', 'verified', 'can:view-etudiants'])->group(function() {
    Route::livewire('createetudiant', '⚡createetudiant')->name('createetudiant');
    Route::livewire('promotions', '⚡promotion')->name('promotions');
    Route::livewire('etudiants', '⚡etudiants')->name('etudiants');
    Route::livewire('/emploit/{promotion}/{groupe}', '⚡emploit')->name('emploit');
    Route::livewire('/filieregerer/{programme}', '⚡filieregerer')->name('filiere');
    Route::livewire('/matieregerer/{niveau}', '⚡matieregerer')->name('matiere');
    Route::livewire('/classe/{promotion}', '⚡tableclasse')->name('classes');
    Route::get('etudiants/export', EtudiantExportController::class)->name('etudiants.export');
    Route::get('etudiants/pdf', EtudiantPdfController::class)->name('etudiants.pdf');
    Route::get('etudiants/template', EtudiantTemplateController::class)->name('etudiants.template');
});
Route::middleware(['auth', 'verified', 'can:view-programme'])->group(function() {
    Route::livewire('filieres', '⚡programme')->name('programme');
});

require __DIR__ . '/settings.php';
