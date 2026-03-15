<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentInscritosTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Inscrito::query()->with('loja')->latest())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->label('Inscrito')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('loja.name')
                    ->label('Loja')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('grau')
                    ->label('Grau')
                    ->badge(),
                IconColumn::make('is_paied')
                    ->label('Pago')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}
