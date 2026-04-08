@component('emails.layout', [
    'title' => 'Pagamento confirmado',
    'heroTitle' => 'Pagamento confirmado',
    'heroText' => 'Seu pagamento foi identificado e o credenciamento do participante está confirmado no ERAC.',
    'inscrito' => $inscrito,
])
    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Olá, <strong>{{ $inscrito->name }}</strong>.
    </p>

    <p style="margin:0 0 18px 0; font-size:16px; line-height:1.8; color:#382214;">
        Confirmamos o pagamento da sua inscrição. Seu credenciamento está validado no sistema.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0; background:linear-gradient(135deg, #234b35 0%, #10251a 100%); border-radius:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#bed9c4; margin-bottom:8px;">Status atual</div>
                <div style="font-size:16px; line-height:1.75; color:#f3fff4;">
                    Pagamento aprovado. Leve esta confirmação no celular para agilizar seu check-in no evento.
                </div>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:15px; line-height:1.8; color:#5a4030;">
        Nós vemos no ERAC.
    </p>
@endcomponent
