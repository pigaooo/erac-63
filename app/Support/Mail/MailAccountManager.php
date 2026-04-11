<?php

namespace App\Support\Mail;

use App\Models\MailAccount;
use RuntimeException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailAccountManager
{
    /**
     * @param  array<int, array{address:string,name:?string}>  $to
     * @param  array<int, array{address:string,name:?string}>  $cc
     * @param  array<int, array{address:string,name:?string}>  $bcc
     * @param  array<int, array{address:string,name:?string}>  $replyTo
     * @param  array<int, array{path:string,name:?string}>  $attachments
     */
    public function send(
        MailAccount $account,
        array $to,
        string $subject,
        ?string $htmlBody = null,
        ?string $textBody = null,
        array $cc = [],
        array $bcc = [],
        array $replyTo = [],
        array $attachments = [],
        ?string $inReplyTo = null,
        array $references = [],
    ): void {
        $email = (new Email())
            ->from(new Address($account->email_address, $account->from_name ?? $account->name))
            ->subject($subject);

        foreach ($this->mapAddresses($to) as $address) {
            $email->addTo($address);
        }

        foreach ($this->mapAddresses($cc) as $address) {
            $email->addCc($address);
        }

        foreach ($this->mapAddresses($bcc) as $address) {
            $email->addBcc($address);
        }

        foreach ($this->mapAddresses($replyTo) as $address) {
            $email->addReplyTo($address);
        }

        if ($htmlBody !== null && $htmlBody !== '') {
            $email->html($htmlBody);
        }

        if ($textBody !== null && $textBody !== '') {
            $email->text($textBody);
        }

        if ($inReplyTo !== null && $inReplyTo !== '') {
            $email->getHeaders()->addIdHeader('In-Reply-To', $inReplyTo);
        }

        if ($references !== []) {
            $email->getHeaders()->addTextHeader('References', implode(' ', $references));
        }

        foreach ($attachments as $attachment) {
            if (! is_file($attachment['path'])) {
                continue;
            }

            $email->attachFromPath($attachment['path'], $attachment['name'] ?? null);
        }

        $this->createMailer($account)->send($email);
    }

    public function createMailer(MailAccount $account): Mailer
    {
        return new Mailer($this->buildTransport($account));
    }

    public function buildTransport(MailAccount $account): EsmtpTransport
    {
        $tls = match ($account->smtp_encryption) {
            'ssl' => true,
            'tls' => false,
            default => false,
        };

        $transport = new EsmtpTransport($account->smtp_host, $account->smtp_port, $tls);

        if (filled($account->smtp_username)) {
            $transport->setUsername($account->smtp_username);
        }

        if (filled($account->smtp_password)) {
            $transport->setPassword($account->smtp_password);
        }

        if ($account->smtp_encryption === 'tls') {
            $transport->setAutoTls(true);
            $transport->setRequireTls(true);
        } else {
            $transport->setAutoTls(false);
        }

        $localDomain = $account->smtp_ehlo_domain;

        if (blank($localDomain)) {
            throw new RuntimeException('A conta de email precisa de um dominio EHLO/HELO configurado para enviar mensagens.');
        }

        if (method_exists($transport, 'setLocalDomain')) {
            $transport->setLocalDomain($localDomain);
        }

        return $transport;
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $addresses
     * @return array<int, Address>
     */
    private function mapAddresses(array $addresses): array
    {
        return array_map(
            fn (array $address): Address => new Address($address['address'], $address['name'] ?? ''),
            array_values(array_filter(
                $addresses,
                fn (array $address): bool => filled($address['address'] ?? null),
            )),
        );
    }
}
