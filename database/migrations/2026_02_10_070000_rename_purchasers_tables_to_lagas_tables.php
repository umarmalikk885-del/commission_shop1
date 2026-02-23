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
        if (Schema::hasTable('purchasers') && !Schema::hasTable('lagas')) {
            Schema::rename('purchasers', 'lagas');
        }

        if (Schema::hasTable('purchaser_payments') && !Schema::hasTable('laga_payments')) {
            Schema::rename('purchaser_payments', 'laga_payments');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lagas') && !Schema::hasTable('purchasers')) {
            Schema::rename('lagas', 'purchasers');
        }

        if (Schema::hasTable('laga_payments') && !Schema::hasTable('purchaser_payments')) {
            Schema::rename('laga_payments', 'purchaser_payments');
        }
    }
};
