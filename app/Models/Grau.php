<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grau extends Model
{
    use HasFactory, HasUlids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'nome',
        'ordem',
        'ativo',
        'tipo_especial',
        'disponivel_formulario_individual',
        'disponivel_formulario_multiplos',
    ];

    protected $casts = [
        'id' => 'string',
        'ordem' => 'integer',
        'ativo' => 'boolean',
        'tipo_especial' => 'boolean',
        'disponivel_formulario_individual' => 'boolean',
        'disponivel_formulario_multiplos' => 'boolean',
    ];

    public function inscritos()
    {
        return $this->hasMany(Inscrito::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem')->orderBy('nome');
    }

    public function scopeDisponiveisNoFormularioIndividual($query)
    {
        return $query->where('disponivel_formulario_individual', true);
    }

    public function scopeDisponiveisNoFormularioMultiplos($query)
    {
        return $query->where('disponivel_formulario_multiplos', true);
    }
}