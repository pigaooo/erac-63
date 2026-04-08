@component('emails.layout', [
    'title' => 'Inscrição realizada pela loja',
    'heroTitle' => 'Sua loja concluiu sua inscrição',
    'heroText' => 'Recebemos sua inscrição como parte de um lote enviado pela loja responsável.',
    'inscrito' => $inscrito,
])
    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Olá, <strong>{{ $inscrito->name }}</strong>.
    </p>

    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Sua loja <strong>{{ $inscrito->loja->name ?? 'responsável pelo lote' }}</strong> já realizou sua inscrição no ERAC. Seu cadastro foi registrado no sistema com sucesso.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:#efe0c4; border:1px solid #cfb07e; border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#8c6239; margin-bottom:8px;">Pagamento do lote</div>
                <div style="font-size:16px; line-height:1.75; color:#382214;">
                    A confirmação final será enviada após a validação do pagamento referente ao lote da sua loja.
                </div>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Guarde esta mensagem. Ela comprova que sua inscrição já foi registrada pela loja.
    </p>
@endcomponent
