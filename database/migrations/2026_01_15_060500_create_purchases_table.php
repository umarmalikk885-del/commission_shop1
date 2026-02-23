<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date')->index();
            $table->foreignId('vendor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('item_name');
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->nullable(); // e.g. kg, bag, crate
            $table->decimal('rate', 12, 2);
            $table->decimal('total_amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

