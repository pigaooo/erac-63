@component('emails.layout', [
    'title' => 'Confirmacao da inscricao',
    'heroTitle' => 'Sua inscricao foi recebida',
    'heroText' => 'O cadastro individual do participante foi registrado com sucesso e ja entrou na fila de validacao da organizacao.',
    'inscrito' => $inscrito,
])
    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Ola, <strong>{{ $inscrito->name }}</strong>.
    </p>

    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Confirmamos o recebimento da sua inscricao individual no ERAC. Agora falta apenas a confirmacao do pagamento para liberar seu credenciamento.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:#2f1c10; border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#d7b98d; margin-bottom:8px;">Chave PIX</div>
                <div style="font-size:16px; line-height:1.75; color:#fff5e2;">
                    Para confirmar sua inscricao, realize o pagamento via PIX usando a chave:
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
                    <li>Abra o app do seu banco e faca o pagamento da inscricao.</li>
                    <li>Salve o comprovante do pagamento.</li>
                    <li>Envie o comprovante para <strong>comprovantes@erac61.com.br</strong>, informando seu nome completo.</li>
                </ol>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Assim que o pagamento for validado, voce recebera um novo e-mail com a confirmacao final.
    </p>
@endcomponent
