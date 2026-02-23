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
        if (Schema::hasTable('purchases') && Schema::hasColumn('purchases', 'laga_code')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->renameColumn('laga_code', 'purchaser_code');
            });
        }

        if (Schema::hasTable('purchaser_payments') && Schema::hasColumn('purchaser_payments', 'laga_id')) {
            Schema::table('purchaser_payments', function (Blueprint $table) {
                $table->renameColumn('laga_id', 'purchaser_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchases') && Schema::hasColumn('purchases', 'purchaser_code')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->renameColumn('purchaser_code', 'laga_code');
            });
        }

        if (Schema::hasTable('purchaser_payments') && Schema::hasColumn('purchaser_payments', 'purchaser_id')) {
            Schema::table('purchaser_payments', function (Blueprint $table) {
                $table->renameColumn('purchaser_id', 'laga_id');
            });
        }
    }
};
