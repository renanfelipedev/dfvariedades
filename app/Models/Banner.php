<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'tipo_midia',
        'url_midia',
        'link_tipo',
        'link_id',
        'link_url',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ordem' => 'integer',
    ];

    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }
}
