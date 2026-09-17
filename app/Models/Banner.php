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

    public function getTargetUrlAttribute(): ?string
    {
        if (! empty($this->link_url)) {
            return $this->link_url;
        }

        if (empty($this->link_tipo) || $this->link_tipo === 'sem_link') {
            return null;
        }

        try {
            if ($this->link_tipo === 'colecao' && $this->link_id) {
                $item = is_numeric($this->link_id) ? Colecao::find($this->link_id) : Colecao::where('slug', $this->link_id)->first();

                return $item ? route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => $item->slug]) : route('catalogo');
            }

            if ($this->link_tipo === 'marca' && $this->link_id) {
                $item = is_numeric($this->link_id) ? Marca::find($this->link_id) : Marca::where('slug', $this->link_id)->first();

                return $item ? route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $item->slug]) : route('catalogo');
            }

            if ($this->link_tipo === 'categoria' && $this->link_id) {
                $item = is_numeric($this->link_id) ? Categoria::find($this->link_id) : Categoria::where('slug', $this->link_id)->first();

                return $item ? route('catalogo.tipo', ['tipo' => 'categoria', 'slug' => $item->slug]) : route('catalogo');
            }

            if ($this->link_tipo === 'produto' && $this->link_id) {
                $item = is_numeric($this->link_id) ? Produto::find($this->link_id) : Produto::where('slug', $this->link_id)->first();

                return $item ? route('produto.show', ['slug' => $item->slug]) : route('catalogo');
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
