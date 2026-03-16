<?php

namespace App\Http\Controllers\ExportsPdf;

use App\Http\Controllers\Controller;
use App\Models\Inscrito;
use App\Models\Loja;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class InscritoPdfExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Inscrito::query()->with('loja');
        $downloadName = 'inscritos-erac';
        $reportTitle = 'Relatorio de inscritos';
        $selectedIds = collect($request->input('ids', []))
            ->filter()
            ->values();

        if ($selectedIds->isNotEmpty()) {
            $query->whereIn('id', $selectedIds->all());
        }

        if ($selectedIds->isEmpty() && filled($request->string('search')->toString())) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefone', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('cim', 'like', "%{$search}%")
                    ->orWhereHas('loja', fn ($lojaQuery) => $lojaQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $filters = $selectedIds->isEmpty() ? $request->input('filters', []) : [];

        if ($selectedIds->isEmpty() && filled(data_get($filters, 'name.name'))) {
            $name = trim((string) data_get($filters, 'name.name'));
            $query->where('name', 'like', "%{$name}%");
        }

        $lojaId = $selectedIds->isEmpty()
            ? data_get($filters, 'loja_id.value', data_get($filters, 'loja_id'))
            : null;

        if ($selectedIds->isEmpty() && filled($lojaId)) {
            $query->where('loja_id', $lojaId);

            $loja = Loja::query()->find($lojaId);

            if ($loja) {
                $downloadName = 'inscritos-' . str($loja->name)->slug('-');
                $reportTitle = 'Relatorio de inscritos: ' . $loja->name;
            }
        }

        $paidFilter = $selectedIds->isEmpty()
            ? data_get($filters, 'is_paied.value', data_get($filters, 'is_paied'))
            : null;

        if ($selectedIds->isEmpty() && in_array($paidFilter, ['1', 1, true, 'true'], true)) {
            $query->where('is_paied', true);
        } elseif ($selectedIds->isEmpty() && in_array($paidFilter, ['0', 0, false, 'false'], true)) {
            $query->where('is_paied', false);
        }

        $inscritos = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($selectedIds->isNotEmpty()) {
            $lojas = $inscritos
                ->pluck('loja')
                ->filter()
                ->unique('id')
                ->values();

            if ($lojas->count() === 1) {
                $loja = $lojas->first();
                $downloadName = 'inscritos-' . str($loja->name)->slug('-');
                $reportTitle = 'Relatorio de inscritos: ' . $loja->name;
            }
        }

        return Pdf::view('pdf.inscritos-report', [
            'inscritos' => $inscritos,
            'generatedAt' => now(),
            'reportTitle' => $reportTitle,
        ])
            ->name("{$downloadName}.pdf")
            ->download();
    }
}
