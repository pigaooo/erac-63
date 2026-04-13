<?php

namespace App\Filament\Resources\Inscritos\Tables;

use App\Models\Inscrito;
use App\Support\InscritoEmailDispatcher;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InscritosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('is_paied')
                    ->label('Pago')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Inscrito')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('loja.name')
                    ->label('Loja')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grau')
                    ->label('Grau')
                    ->badge()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('telefone')
                    ->label('Telefone')
                    ->toggleable(),
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cim')
                    ->label('CIM')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Nome do inscrito')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Digite o nome do inscrito'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['name'] ?? null),
                            fn (Builder $query): Builder => $query->where('name', 'like', '%' . trim($data['name']) . '%'),
                        );
                    }),
                TernaryFilter::make('is_paied')
                    ->label('Pagos'),
                SelectFilter::make('grau')
                    ->label('Grau')
                    ->options([
                        'AM' => 'A.M.',
                        'CM' => 'C.M.',
                        'MM' => 'M.M.',
                        'MI' => 'M.I.',
                        'OT' => 'Outros',
                        'VI' => 'Visitante',
                        'CU' => 'Cunhada',
                        'SO' => 'Sobrinho',
                    ]),
                SelectFilter::make('loja_id')
                    ->label('Loja')
                    ->relationship('loja', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkAction::make('confirmPayments')
                    ->label('Confirmar pagamento')
                    ->icon('heroicon-o-banknotes')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records): void {
                        $ids = $records
                            ->filter(fn (Inscrito $inscrito) => $inscrito->payment_confirmation_sent_at === null)
                            ->pluck('id');

                        if ($ids->isEmpty()) {
                            Notification::make()
                                ->title('Nenhum inscrito elegivel para confirmacao de pagamento.')
                                ->warning()
                                ->send();

                            return;
                        }

                        Inscrito::withoutEvents(function () use ($ids): void {
                            Inscrito::query()
                                ->whereKey($ids)
                                ->update([
                                    'is_paied' => true,
                                    'updated_at' => now(),
                                ]);
                        });

                        $inscritos = Inscrito::query()
                            ->with('loja')
                            ->whereKey($ids)
                            ->get();

                        app(InscritoEmailDispatcher::class)->dispatchPaymentBatch($inscritos);

                        Notification::make()
                            ->title('Pagamentos confirmados e e-mails enviados para a fila.')
                            ->success()
                            ->send();
                    }),
                BulkAction::make('exportPdf')
                    ->label('Exportar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        return redirect()->to(route('admin.inscritos.export-pdf', [
                            'ids' => $records->pluck('id')->all(),
                        ]));
                    }),
                DeleteBulkAction::make(),
            ])
            ->stackedOnMobile();
    }
}
