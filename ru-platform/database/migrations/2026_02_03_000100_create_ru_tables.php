<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->timestamps();
        });

        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('dish_category', function (Blueprint $table) {
            $table->foreignId('dish_id')->constrained('dishes')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['dish_id', 'category_id']);
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('unit', 20);
            $table->timestamps();
        });

        Schema::create('dish_ingredient', function (Blueprint $table) {
            $table->foreignId('dish_id')->constrained('dishes')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->decimal('qty', 10, 2);
            $table->primary(['dish_id', 'ingredient_id']);
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->date('menu_date');
            $table->enum('service', ['lunch', 'dinner']);
            $table->timestamps();
            $table->unique(['menu_date', 'service']);
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('dish_id')->constrained('dishes')->cascadeOnDelete();
            $table->integer('quantity_limit')->nullable();
            $table->integer('sold_count')->default(0);
            $table->primary(['menu_id', 'dish_id']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('menu_id')->constrained('menus');
            $table->enum('status', ['draft', 'reserved', 'paid', 'cancelled'])->default('draft');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->date('reserved_for')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('dish_id')->constrained('dishes');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->primary(['order_id', 'dish_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->unique();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('method', 30)->default('simulated');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients');
            $table->decimal('delta_qty', 10, 2);
            $table->string('reason', 120);
            $table->timestamps();
            $table->index('ingredient_id');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('dish_id')->nullable()->constrained('dishes');
            $table->foreignId('menu_id')->nullable()->constrained('menus');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('dish_ingredient');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('dish_category');
        Schema::dropIfExists('dishes');
        Schema::dropIfExists('categories');
    }
};
