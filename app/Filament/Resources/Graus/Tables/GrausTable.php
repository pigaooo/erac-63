<?php

namespace App\Filament\Resources\Graus\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GrausTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable(),
                TextColumn::make('codigo')
                    ->label('Codigo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('tipo_especial')
                    ->label('Especial')
                    ->boolean(),
                IconColumn::make('disponivel_formulario_individual')
                    ->label('Individual')
                    ->boolean(),
                IconColumn::make('disponivel_formulario_multiplos')
                    ->label('Multiplo')
                    ->boolean(),
                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('ativo')
                    ->label('Ativo'),
                TernaryFilter::make('tipo_especial')
                    ->label('Tipo especial'),
            ])
            ->defaultSort('ordem')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->stackedOnMobile();
    }
}