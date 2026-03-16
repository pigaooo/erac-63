<?php

use App\Http\Controllers\ExportsPdf\InscritoPdfExportController;
use App\Models\Patrocinador;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.home', compact('patrocinadores'));
})->name('home');

Route::get('/localizacao', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.localizacao', compact('patrocinadores'));
})->name('localizacao');

Route::get('/programacao', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.programacao', compact('patrocinadores'));
})->name('programacao');

Route::get('/inscricao', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.inscricao', compact('patrocinadores'));
})->name('inscricao');

Route::get('/sobre', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.sobre', compact('patrocinadores'));
})->name('sobre');

Route::middleware('auth')
    ->get('/admin/inscritos/export-pdf', InscritoPdfExportController::class)
    ->name('admin.inscritos.export-pdf');
