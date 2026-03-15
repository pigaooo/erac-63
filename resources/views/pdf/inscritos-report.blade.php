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
            margin: 32px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            color: #92400e;
        }

        .subtitle {
            margin-top: 6px;
            margin-bottom: 20px;
            color: #6b7280;
        }

        .summary {
            display: block;
            margin-bottom: 20px;
            padding: 12px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
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
            letter-spacing: 0.04em;
            padding: 10px 8px;
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
        }

        tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
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
    </style>
</head>
<body>
    <h1>{{ $reportTitle ?? 'Relatorio de inscritos' }}</h1>
    <div class="subtitle">61o Encontro Regional de Aprendizes e Companheiros</div>

    <div class="summary">
        <strong>Total de inscritos:</strong> {{ $inscritos->count() }}<br>
        <strong>Gerado em:</strong> {{ $generatedAt->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Pago</th>
                <th>Nome</th>
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
                    <td>{{ $inscrito->grau }}</td>
                    <td>{{ $inscrito->email }}</td>
                    <td>{{ $inscrito->telefone ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum inscrito encontrado para os filtros atuais.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
