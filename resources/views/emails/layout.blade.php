<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#efe7d7; font-family:Georgia, 'Times New Roman', serif; color:#24170d;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(180deg, #efe7d7 0%, #e3d1b0 100%); margin:0; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px; background-color:#fffaf1; border:1px solid #c9ac73; border-radius:28px; overflow:hidden; box-shadow:0 20px 45px rgba(36, 23, 13, 0.18);">
                    <tr>
                        <td style="padding:0; background:radial-gradient(circle at top left, #f6ead1 0%, #8c6239 55%, #2a1608 100%);">
                            <div style="padding:32px 36px 28px 36px;">
                                <div style="display:inline-block; padding:8px 14px; border:1px solid rgba(255,255,255,0.35); border-radius:999px; color:#fff6e4; font-size:12px; letter-spacing:0.24em; text-transform:uppercase;">
                                    ERAC
                                </div>
                                <h1 style="margin:18px 0 10px 0; font-size:34px; line-height:1.15; color:#fff6e4;">{{ $heroTitle ?? 'Confirmacao enviada' }}</h1>
                                <p style="margin:0; max-width:520px; font-size:16px; line-height:1.7; color:#f3e4cd;">{{ $heroText ?? 'Recebemos sua solicitacao e registramos as informacoes no evento.' }}</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 36px 18px 36px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 36px 36px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5ead7; border:1px solid #dcc29b; border-radius:22px;">
                                <tr>
                                    <td style="padding:22px 24px;">
                                        <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#8c6239; margin-bottom:10px;">Resumo do inscrito</div>
                                        <div style="font-size:15px; line-height:1.9; color:#382214;">
                                            <strong>Nome:</strong> {{ $inscrito->name }}<br>
                                            <strong>E-mail:</strong> {{ $inscrito->email }}<br>
                                            <strong>Loja:</strong> {{ $inscrito->loja->name ?? 'Nao informada' }}<br>
                                            <strong>Grau:</strong> {{ $inscrito->grau }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 36px 36px 36px;">
                            <p style="margin:0 0 14px 0; font-size:15px; line-height:1.75; color:#5a4030;">
                                Se precisar falar com a organizacao, responda este e-mail ou envie o comprovante para <strong>comprovante@fontedevida.com</strong>.
                            </p>
                            <p style="margin:0; font-size:12px; line-height:1.7; color:#8a715f;">
                                {{ config('app.name') }}<br>
                                Mensagem automatica enviada pela fila do sistema.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
