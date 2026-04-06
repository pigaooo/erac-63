@component('emails.layout', [
    'title' => 'Inscricao realizada pela loja',
    'heroTitle' => 'Sua loja concluiu sua inscricao',
    'heroText' => 'Recebemos sua inscricao como parte de um lote enviado pela loja responsavel.',
    'inscrito' => $inscrito,
])
    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Ola, <strong>{{ $inscrito->name }}</strong>.
    </p>

    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Sua loja <strong>{{ $inscrito->loja->name ?? 'responsavel pelo lote' }}</strong> ja realizou sua inscricao no ERAC. Seu cadastro foi registrado no sistema com sucesso.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:#efe0c4; border:1px solid #cfb07e; border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#8c6239; margin-bottom:8px;">Pagamento do lote</div>
                <div style="font-size:16px; line-height:1.75; color:#382214;">
                    A confirmacao final sera enviada apos a validacao do pagamento referente ao lote da sua loja.
                </div>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Guarde esta mensagem. Ela comprova que sua inscricao ja foi registrada pela loja.
    </p>
@endcomponent
