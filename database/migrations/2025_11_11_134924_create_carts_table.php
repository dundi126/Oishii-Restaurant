<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->json('customizations')->nullable(); // Stores all customizations as JSON
            $table->decimal('customization_price', 8, 2)->default(0); // Total extra from customizations
            $table->timestamps();

            // Ensure unique menu items per user
            $table->unique(['user_id', 'menu_item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
