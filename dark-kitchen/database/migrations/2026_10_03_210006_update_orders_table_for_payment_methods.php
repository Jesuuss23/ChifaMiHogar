<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('yape')->after('total'); // 'yape' o 'cash'
            $table->string('cash_amount')->nullable()->after('payment_method'); // con cuánto paga (ej: Paga con S/ 50)
            $table->string('payment_receipt')->nullable()->change(); // Comprobante opcional
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'cash_amount']);
        });
    }
};