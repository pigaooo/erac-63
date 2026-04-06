<?php

namespace App\Mail;

use App\Models\Inscrito;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscrito $inscrito)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pagamento confirmado no ERAC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscritos.payment-confirmation',
        );
    }
}
