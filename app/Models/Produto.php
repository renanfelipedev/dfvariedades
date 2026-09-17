<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'colecao_id',
        'categoria_id',
        'nome',
        'slug',
        'descricao',
        'detalhes',
        'preco',
        'preco_promocional',
        'estoque',
        'estoque_minimo',
        'sku',
        'imagens',
        'ativo',
        'destaque',
        'escolhido',
        'presente',
        'cabelo',
        'flash_deal',
        'flash_deal_fim',
        'ordem',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'preco_promocional' => 'decimal:2',
        'estoque' => 'integer',
        'estoque_minimo' => 'integer',
        'imagens' => 'array',
        'ativo' => 'boolean',
        'destaque' => 'boolean',
        'escolhido' => 'boolean',
        'presente' => 'boolean',
        'cabelo' => 'boolean',
        'flash_deal' => 'boolean',
        'flash_deal_fim' => 'datetime',
        'ordem' => 'integer',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function colecao(): BelongsTo
    {
        return $this->belongsTo(Colecao::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function pedidoItens(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }

    public function scopeDestaque(Builder $query): Builder
    {
        return $query->where('destaque', true)->where('ativo', true)->orderBy('ordem');
    }

    public function scopeEscolhidos(Builder $query): Builder
    {
        return $query->where('escolhido', true)->where('ativo', true)->orderBy('ordem');
    }

    public function scopePresentear(Builder $query): Builder
    {
        return $query->where('presente', true)->where('ativo', true)->orderBy('ordem');
    }

    public function scopeCabelo(Builder $query): Builder
    {
        return $query->where('cabelo', true)->where('ativo', true)->orderBy('ordem');
    }

    public function scopeFlashDeal(Builder $query): Builder
    {
        return $query->where('flash_deal', true)->where('ativo', true)->orderBy('ordem');
    }

    public function scopePromocoes(Builder $query): Builder
    {
        return $query->whereNotNull('preco_promocional')
            ->whereColumn('preco_promocional', '<', 'preco')
            ->where('ativo', true)
            ->orderBy('ordem');
    }

    public function scopeEstoqueBaixo(Builder $query, ?int $limite = null): Builder
    {
        if ($limite !== null) {
            return $query->where('ativo', true)->where('estoque', '<=', $limite)->orderBy('estoque');
        }

        return $query->where('ativo', true)
            ->where(function (Builder $q) {
                $q->whereColumn('estoque', '<=', 'estoque_minimo')
                    ->orWhere('estoque', '<=', 5);
            })
            ->orderBy('estoque');
    }

    /**
     * Obter a primeira imagem do produto
     */
    protected function primeiraImagem(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (is_array($this->imagens) && count($this->imagens) > 0) {
                    return $this->imagens[0];
                }

                return 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=600&auto=format&fit=crop';
            }
        );
    }

    /**
     * Obter preço final com desconto se aplicável
     */
    protected function precoFinal(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->preco_promocional && $this->preco_promocional < $this->preco)
                ? (float) $this->preco_promocional
                : (float) $this->preco
        );
    }

    /**
     * Verifica se está em promoção
     */
    protected function temDesconto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preco_promocional && $this->preco_promocional < $this->preco
        );
    }

    /**
     * Calcula o percentual de desconto
     */
    protected function percentualDesconto(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->tem_desconto && $this->preco > 0) {
                    return (int) round((($this->preco - $this->preco_promocional) / $this->preco) * 100);
                }

                return 0;
            }
        );
    }

    /**
     * Informações de parcelamento (até 6x)
     */
    protected function parcelamento(): Attribute
    {
        return Attribute::make(
            get: function () {
                $valor = $this->preco_final;
                $parcelas = ($valor >= 120) ? 6 : (($valor >= 60) ? 3 : 1);
                $valorParcela = $valor / $parcelas;

                return [
                    'parcelas' => $parcelas,
                    'valor_parcela' => $valorParcela,
                    'texto' => $parcelas > 1 ? "ou {$parcelas}x de R$ ".number_format($valorParcela, 2, ',', '.') : 'à vista',
                ];
            }
        );
    }
}
