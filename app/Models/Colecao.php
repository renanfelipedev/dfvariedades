<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colecao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'colecoes';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'imagem_url',
        'banner_url',
        'ordem',
        'ativo',
        'destaque',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'destaque' => 'boolean',
        'ordem' => 'integer',
    ];

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }

    public function scopeDestaque(Builder $query): Builder
    {
        return $query->where('destaque', true)->where('ativo', true)->orderBy('ordem');
    }
}
