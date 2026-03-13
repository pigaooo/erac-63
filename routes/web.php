<?php

use App\Models\Patrocinador;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.home', compact('patrocinadores'));
})->name('home');

Route::view('/localizacao', 'pages.localizacao')->name('localizacao');

Route::get('/programacao', function () {
    $patrocinadores = Patrocinador::query()
        ->orderBy('name')
        ->get();

    return view('pages.programacao', compact('patrocinadores'));
})->name('programacao');

Route::view('/inscricao', 'pages.inscricao')->name('inscricao');
Route::view('/sobre', 'pages.sobre')->name('sobre');
