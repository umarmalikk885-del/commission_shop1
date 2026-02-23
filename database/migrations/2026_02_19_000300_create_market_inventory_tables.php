<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->foreignId('product_owner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->decimal('price_per_unit', 12, 2)->default(0);
            $table->enum('status', ['available', 'low', 'out'])->default('available');
            $table->timestamp('available_from')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_item_id')->constrained('market_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['create', 'update', 'adjust_stock', 'price_change', 'status_change']);
            $table->decimal('quantity_before', 12, 2)->nullable();
            $table->decimal('quantity_after', 12, 2)->nullable();
            $table->decimal('price_before', 12, 2)->nullable();
            $table->decimal('price_after', 12, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('market_items');
    }
};

