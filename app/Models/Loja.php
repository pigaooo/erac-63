<?php

namespace App\Models;

use App\Models\Inscrito;
use App\Models\Patrocinador;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    use HasFactory, HasUlids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'numero_loja',
        'email',
        'is_ativo',
        'user_id',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'is_ativo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscritos()
    {
        return $this->hasMany(Inscrito::class);
    }

    public function patrocinadores()
    {
        return $this->hasMany(Patrocinador::class);
    }
}
