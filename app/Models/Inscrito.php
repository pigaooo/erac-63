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
        'grau_id',
        'loja_id',
        'is_paied',
        'registration_confirmation_sent_at',
        'payment_confirmation_sent_at',
    ];

    protected $casts = [
        'id' => 'string',
        'grau_id' => 'string',
        'loja_id' => 'string',
        'is_paied' => 'boolean',
        'registration_confirmation_sent_at' => 'datetime',
        'payment_confirmation_sent_at' => 'datetime',
    ];

    public function getGrauDescricaoAttribute()
    {
        return $this->grau?->nome ?? '-';
    }

    public function grau()
    {
        return $this->belongsTo(Grau::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
