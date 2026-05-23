<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\PublicStorageController;
use App\Http\Controllers\ExportsPdf\InscritoPdfExportController;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/localizacao', [SiteController::class, 'localizacao'])->name('localizacao');

Route::get('/programacao', [SiteController::class, 'programacao'])->name('programacao');

Route::get('/inscricao', [SiteController::class, 'inscricao'])->name('inscricao');

Route::get('/patrocinadores', [SiteController::class, 'patrocinadoresPage'])->name('patrocinadores');

Route::get('/sobre', [SiteController::class, 'sobre'])->name('sobre');

Route::get('/media/{path}', PublicStorageController::class)
    ->where('path', '.*')
    ->name('media.public');

Route::middleware('auth')
    ->get('/admin/inscritos/export-pdf', InscritoPdfExportController::class)
    ->name('admin.inscritos.export-pdf');
