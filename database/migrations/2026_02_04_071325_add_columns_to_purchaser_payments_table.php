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
        Schema::table('purchaser_payments', function (Blueprint $table) {
            $table->foreignId('purchaser_id')->constrained()->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 14, 2);
            $table->string('payment_method')->default('cash'); // cash, bank, check
            $table->text('notes')->nullable();
            
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchaser_payments', function (Blueprint $table) {
            $table->dropForeign(['purchaser_id']);
            $table->dropColumn(['purchaser_id', 'payment_date', 'amount', 'payment_method', 'notes']);
        });
    }
};
