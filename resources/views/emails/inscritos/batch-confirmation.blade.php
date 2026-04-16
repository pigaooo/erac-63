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
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#8c6239; margin-bottom:8px;">Chave PIX</div>
                <div style="font-size:16px; line-height:1.75; color:#382214;">
                    O pagamento pode ser feito via PIX usando a chave:
                    <br>
                    <strong style="font-size:18px;">inscricao@erac61.com.br</strong>
                </div>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:#f5ead7; border:1px solid #dcc29b; border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#8c6239; margin-bottom:12px;">Passo a passo</div>
                <ol style="margin:0; padding-left:20px; font-size:15px; line-height:1.9; color:#382214;">
                    <li>Copie a chave PIX <strong>inscricao@erac61.com.br</strong>.</li>
                    <li>Realize o pagamento referente ao lote da loja.</li>
                    <li>Salve o comprovante do pagamento.</li>
                    <li>Envie o comprovante para <strong>comprovantes@erac61.com.br</strong>, informando o nome da loja e dos inscritos.</li>
                </ol>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Guarde esta mensagem. Ela comprova que sua inscricao ja foi registrada pela loja. A confirmacao final sera enviada apos a validacao do pagamento.
    </p>
@endcomponent
