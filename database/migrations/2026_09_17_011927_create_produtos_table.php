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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('colecao_id')->nullable()->constrained('colecoes')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->text('detalhes')->nullable();
            $table->decimal('preco', 10, 2);
            $table->decimal('preco_promocional', 10, 2)->nullable();
            $table->integer('estoque')->default(10);
            $table->string('sku')->nullable();
            $table->json('imagens')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('destaque')->default(false);
            $table->boolean('escolhido')->default(false);
            $table->boolean('presente')->default(false);
            $table->boolean('cabelo')->default(false);
            $table->boolean('flash_deal')->default(false);
            $table->timestamp('flash_deal_fim')->nullable();
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
