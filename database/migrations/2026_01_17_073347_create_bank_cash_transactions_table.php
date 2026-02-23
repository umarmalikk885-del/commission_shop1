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
        Schema::create('bank_cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->index();
            $table->enum('type', ['bank', 'cash'])->default('cash');
            $table->enum('transaction_type', ['deposit', 'withdrawal', 'transfer'])->default('deposit');
            $table->decimal('amount', 14, 2);
            $table->string('description');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('type');
            $table->index('transaction_type');
            $table->index(['transaction_date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_cash_transactions');
    }
};
