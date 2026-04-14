<?php

namespace App\Http\Controllers;

use App\Models\Inscrito;
use App\Models\Loja;
use App\Models\Patrocinador;
use App\Support\InscricaoCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelPdf\Facades\Pdf;

class SiteController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'patrocinadores' => $this->patrocinadores(),
        ]);
    }

    public function localizacao()
    {
        return view('pages.localizacao', [
            'patrocinadores' => $this->patrocinadores(),
        ]);
    }

    public function programacao()
    {
        return view('pages.programacao', [
            'patrocinadores' => $this->patrocinadores(),
        ]);
    }

    public function inscricao(InscricaoCalendar $inscricaoCalendar)
    {
        return view('pages.inscricao', [
            'patrocinadores' => $this->patrocinadores(),
            'inscricaoResumo' => $inscricaoCalendar->resumo(),
        ]);
    }

    public function sobre()
    {
        return view('pages.sobre', [
            'patrocinadores' => $this->patrocinadores(),
        ]);
    }

    public function patrocinadoresPage()
    {
        return view('pages.patrocinadores', [
            'patrocinadoresPorTipo' => $this->patrocinadoresAgrupados(),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Inscrito::query()->with(['loja', 'grau']);
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

        $grauId = $selectedIds->isEmpty()
            ? data_get($filters, 'grau_id.value', data_get($filters, 'grau_id'))
            : null;

        if ($selectedIds->isEmpty() && filled($grauId)) {
            $query->where('grau_id', $grauId);
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

    protected function patrocinadores()
    {
        return Cache::remember('site.patrocinadores', now()->addMinutes(10), function () {
            return Patrocinador::query()
                ->orderBy('name')
                ->get();
        });
    }

    protected function patrocinadoresAgrupados()
    {
        $ordem = ['Diamante', 'Ouro', 'Prata', 'Bronze', 'Apoio'];
        $patrocinadores = $this->patrocinadores()->groupBy('tipo_patrocinio');

        return collect($ordem)->mapWithKeys(fn (string $tipo) => [
            $tipo => $patrocinadores->get($tipo, collect())->values(),
        ]);
    }
}
