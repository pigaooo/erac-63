<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrocinador extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'patrocinadores';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'telefone',
        'endereco',
        'tipo_patrocinio',
        'loja_id',
        'user_id',
    ];

    protected $casts = [
        'id' => 'string',
        'loja_id' => 'string',
        'user_id' => 'string',
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
