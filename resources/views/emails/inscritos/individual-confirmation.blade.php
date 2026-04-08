@component('emails.layout', [
    'title' => 'Confirmação da inscrição',
    'heroTitle' => 'Sua inscrição foi recebida',
    'heroText' => 'O cadastro individual do participante foi registrado com sucesso e já entrou na fila de validação da organização.',
    'inscrito' => $inscrito,
])
    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Olá, <strong>{{ $inscrito->name }}</strong>.
    </p>

    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Confirmamos o recebimento da sua inscrição individual no ERAC. Agora falta apenas a confirmação do pagamento para liberar seu credenciamento.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:#2f1c10; border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#d7b98d; margin-bottom:8px;">Próximo passo</div>
                <div style="font-size:16px; line-height:1.75; color:#fff5e2;">
                    Realize o pagamento via PIX e envie o comprovante para <strong>comprovantes@erac61.com.br</strong>.
                </div>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Assim que o pagamento for validado, você receberá um novo e-mail com a confirmação final.
    </p>
@endcomponent
