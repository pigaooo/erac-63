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
    ];

    protected $casts = [
        'id' => 'string',
        'loja_id' => 'string',
        'is_paied' => 'boolean',
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
