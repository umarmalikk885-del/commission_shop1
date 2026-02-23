<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_items')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('stock_items');
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('stock_items')) {
            Schema::create('stock_items', function (Blueprint $table) {
                $table->id();
                $table->string('item_name')->nullable();
                $table->decimal('quantity', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }
};

