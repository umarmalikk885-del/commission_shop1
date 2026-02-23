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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('qty', 12, 2)->nullable()->after('item_name'); // Numeric quantity
            $table->string('unit')->nullable()->after('qty'); // Unit: kilo, crate, bag, dozen, piece, etc.
            $table->decimal('rate', 12, 2)->nullable()->after('unit'); // Rate per unit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['qty', 'unit', 'rate']);
        });
    }
};
