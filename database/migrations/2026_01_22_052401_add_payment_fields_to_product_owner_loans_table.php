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
        Schema::table('product_owner_loans', function (Blueprint $table) {
            $table->decimal('paid_amount', 14, 2)->default(0)->after('advance_amount');
            $table->date('paid_date')->nullable()->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_owner_loans', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'paid_date']);
        });
    }
};
