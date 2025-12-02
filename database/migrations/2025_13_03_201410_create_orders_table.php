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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('tax_amount', 8, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('total_amount', 8, 2);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_postal_code');
            $table->text('delivery_instructions')->nullable();
            $table->enum('payment_method', ['credit_card', 'cash']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('stripe_payment_intent_id')->nullable(); // removed ->after()
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
