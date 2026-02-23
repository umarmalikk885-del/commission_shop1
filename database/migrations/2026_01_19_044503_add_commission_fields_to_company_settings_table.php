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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->string('gst_number')->nullable()->after('email');
            $table->decimal('default_commission_rate', 5, 2)->default(0)->after('gst_number');
            $table->string('currency_symbol', 10)->default('Rs.')->after('default_commission_rate');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('currency_symbol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['email', 'gst_number', 'default_commission_rate', 'currency_symbol', 'tax_rate']);
        });
    }
};
