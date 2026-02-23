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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->nullable(); // kg, bag, crate, pieces, etc.
            $table->decimal('min_stock_level', 12, 2)->default(0); // Alert when stock goes below this
            $table->decimal('rate', 12, 2)->nullable(); // Current rate/price
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('item_name');
            $table->index('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
