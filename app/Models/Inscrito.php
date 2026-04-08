<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    use HasFactory, HasUlids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'telefone',
        'cpf',
        'cim',
        'grau',
        'loja_id',
        'is_paied',
        'registration_confirmation_sent_at',
        'payment_confirmation_sent_at',
    ];

    protected $casts = [
        'id' => 'string',
        'loja_id' => 'string',
        'is_paied' => 'boolean',
        'registration_confirmation_sent_at' => 'datetime',
        'payment_confirmation_sent_at' => 'datetime',
    ];

    public function getGrauDescricaoAttribute()
    {
        $map = [
            'AM' => 'A∴M∴',
            'CM' => 'C∴M∴',
            'MM' => 'M∴M∴',
            'MI' => 'M∴I∴',
            'OT' => 'Outros',
            'VI' => 'Visitante',
            'CU' => 'Cunhada',
            'SO' => 'Sobrinho',
        ];

        return $map[$this->grau] ?? $this->grau;
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
