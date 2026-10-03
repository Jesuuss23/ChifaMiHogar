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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // ej: DK-4912
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->text('delivery_address');
            $table->string('reference')->nullable();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->decimal('total', 8, 2);
            $table->string('payment_status')->default('pending'); // pending, confirmed, rejected
            $table->string('order_status')->default('received');  // received, cooking, on_the_way, delivered, cancelled
            $table->string('payment_receipt')->nullable(); // Ruta de la imagen del voucher
            $table->text('notes')->nullable(); // ej: Bajo en sal, sin cebolla
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
