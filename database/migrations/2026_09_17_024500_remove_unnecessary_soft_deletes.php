<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'produtos',
            'marcas',
            'colecoes',
            'categorias',
            'banners',
            'lista_esperas',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Remove soft-deleted ghost records first so they don't block unique constraints
                if (Schema::hasColumn($table, 'deleted_at')) {
                    DB::table($table)->whereNotNull('deleted_at')->delete();

                    Schema::table($table, function (Blueprint $tableBlueprint) {
                        $tableBlueprint->dropSoftDeletes();
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'produtos',
            'marcas',
            'colecoes',
            'categorias',
            'banners',
            'lista_esperas',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->softDeletes();
                });
            }
        }
    }
};
