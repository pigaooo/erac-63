<?php

namespace App\Console\Commands;

use App\Mail\IndividualRegistrationConfirmationMail;
use App\Models\Grau;
use App\Models\Inscrito;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {to}';

    protected $description = 'Send a test registration confirmation email to the given address';

    public function handle()
    {
        $to = $this->argument('to');

        $ins = new Inscrito([
            'name' => 'Teste Envio',
            'email' => $to,
            'telefone' => '(11) 99999-9999',
            'cpf' => '000.000.000-00',
            'cim' => '123',
        ]);

        $grau = Grau::query()->where('codigo', 'AM')->first() ?? new Grau([
            'codigo' => 'AM',
            'nome' => 'A∴M∴',
        ]);

        $ins->grau_id = $grau->id;
        $ins->setRelation('grau', $grau);

        $ins->loja = (object)['name' => 'Loja Teste'];

        Mail::to($to)->send(new IndividualRegistrationConfirmationMail($ins));

        $this->info('Test mail sent to ' . $to);

        return 0;
    }
}
