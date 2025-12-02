<?php
// database/migrations/2024_01_01_000001_create_menu_item_customizations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->string('name'); // 'Broth Type', 'Spice Level', 'Toppings'
            $table->string('type'); // 'select', 'multiple', 'text'
            $table->boolean('required')->default(false);
            $table->json('options')->nullable(); // [{'value': 'Tonkotsu', 'price': 0}, {'value': 'Medium', 'price': 0.50}]
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_customizations');
    }
};
