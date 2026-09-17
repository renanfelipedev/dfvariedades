<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome_cliente');
            $table->string('whatsapp_cliente');
            $table->string('cpf_cliente');
            $table->string('email_cliente');
            $table->string('tipo_entrega')->default('entrega'); // entrega, retirada
            $table->string('loja_retirada')->nullable();
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('opcao_frete')->nullable();
            $table->decimal('valor_frete', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('taxa_pagamento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('forma_pagamento')->default('credito'); // credito, debito, pix
            $table->string('status')->default('pendente'); // pendente, pago, enviado, entregue, cancelado
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
