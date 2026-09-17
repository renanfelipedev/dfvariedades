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
        Schema::create('lista_esperas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('whatsapp');
            $table->string('email')->nullable();
            $table->string('cep');
            $table->string('cidade');
            $table->string('estado', 2)->default('BA');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_esperas');
    }
};
