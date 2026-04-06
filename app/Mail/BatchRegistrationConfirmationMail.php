<?php

namespace App\Mail;

use App\Models\Inscrito;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BatchRegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscrito $inscrito)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sua loja concluiu sua inscrição no ERAC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscritos.batch-confirmation',
        );
    }
}
