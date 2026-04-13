<?php

namespace App\Filament\Resources\MailEvents\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MailEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('occurred_at', 'desc')
            ->columns([
                TextColumn::make('occurred_at')->label('Quando')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('account.name')->label('Conta')->searchable(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('summary')->label('Resumo')->wrap(),
                IconColumn::make('is_read')->label('Lido')->boolean(),
            ])
            ->stackedOnMobile();
    }
}
