<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Asegurar campos en la tabla 'addons' para que sea catálogo global
        if (!Schema::hasTable('addons')) {
            Schema::create('addons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 8, 2)->default(0);
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('addons', function (Blueprint $table) {
                if (!Schema::hasColumn('addons', 'image')) {
                    $table->string('image')->nullable()->after('price');
                }
                if (!Schema::hasColumn('addons', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('image');
                }
                // Si product_id existía y no era nullable, lo volvemos opcional
                if (Schema::hasColumn('addons', 'product_id')) {
                    $table->unsignedBigInteger('product_id')->nullable()->change();
                }
            });
        }

        // 2. Tabla pivote 'addon_product' para vincular qué platos tienen qué extras globales
        if (!Schema::hasTable('addon_product')) {
            Schema::create('addon_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('addon_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_product');
    }
};