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
        Schema::table('bank_cash_transactions', function (Blueprint $table) {
            $table->foreignId('purchase_id')->nullable()->after('notes')->constrained()->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->after('purchase_id')->constrained()->onDelete('set null');
            
            $table->index('purchase_id');
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_cash_transactions', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['purchase_id', 'invoice_id']);
        });
    }
};
