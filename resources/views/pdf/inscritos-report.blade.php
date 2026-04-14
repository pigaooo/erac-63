<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de inscritos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 24px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 22px;
            color: #92400e;
        }

        .subtitle {
            margin: 0 0 18px;
            color: #6b7280;
        }

        .summary {
            margin-bottom: 18px;
            padding: 10px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .summary-row {
            margin: 0 0 4px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary strong {
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            padding: 8px 6px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        tbody td {
            padding: 8px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .paid {
            color: #166534;
            font-weight: 700;
        }

        .unpaid {
            color: #b91c1c;
            font-weight: 700;
        }

        .empty {
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <h1>{{ $reportTitle ?? 'Relatorio de inscritos' }}</h1>
    <p class="subtitle">61o Encontro Regional de Aprendizes e Companheiros</p>

    <div class="summary">
        <p class="summary-row">
            <strong>Total de inscritos:</strong> {{ $inscritos->count() }}
        </p>
        <p class="summary-row">
            <strong>Gerado em:</strong> {{ $generatedAt->format('d/m/Y H:i') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pago</th>
                <th>Nome</th>
                <th>Loja</th>
                <th>Grau</th>
                <th>E-mail</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inscritos as $inscrito)
                <tr>
                    <td class="{{ $inscrito->is_paied ? 'paid' : 'unpaid' }}">
                        {{ $inscrito->is_paied ? 'Sim' : 'Nao' }}
                    </td>
                    <td>{{ $inscrito->name }}</td>
                    <td>{{ $inscrito->loja?->name ?? '-' }}</td>
                    <td>{{ $inscrito->grau_descricao }}</td>
                    <td>{{ $inscrito->email }}</td>
                    <td>{{ $inscrito->telefone ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="6">Nenhum inscrito encontrado para os filtros atuais.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
