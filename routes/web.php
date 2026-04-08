<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\ExportsPdf\InscritoPdfExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/localizacao', [SiteController::class, 'localizacao'])->name('localizacao');

Route::get('/programacao', [SiteController::class, 'programacao'])->name('programacao');

Route::get('/inscricao', [SiteController::class, 'inscricao'])->name('inscricao');

Route::get('/patrocinadores', [SiteController::class, 'patrocinadoresPage'])->name('patrocinadores');

Route::get('/sobre', [SiteController::class, 'sobre'])->name('sobre');

Route::middleware('auth')
    ->get('/admin/inscritos/export-pdf', InscritoPdfExportController::class)
    ->name('admin.inscritos.export-pdf');
