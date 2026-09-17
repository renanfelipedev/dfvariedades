<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'user_id',
        'nome_cliente',
        'whatsapp_cliente',
        'cpf_cliente',
        'email_cliente',
        'tipo_entrega',
        'loja_retirada',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'opcao_frete',
        'valor_frete',
        'subtotal',
        'desconto',
        'taxa_pagamento',
        'total',
        'forma_pagamento',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'valor_frete' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'desconto' => 'decimal:2',
        'taxa_pagamento' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pedido $pedido) {
            if (empty($pedido->codigo)) {
                $pedido->codigo = 'DFV-'.strtoupper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }
}
