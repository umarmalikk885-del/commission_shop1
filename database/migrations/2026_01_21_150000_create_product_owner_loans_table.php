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
        Schema::create('product_owner_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_owner_id')->nullable();
            $table->string('owner_name')->nullable();
            $table->foreignId('vendor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('loan_date');
            $table->decimal('advance_amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('loan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_owner_loans');
    }
};

