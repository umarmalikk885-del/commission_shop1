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
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'purchaser_code')) {
                $table->renameColumn('purchaser_code', 'laga_code');
            }
            if (Schema::hasColumn('purchases', 'purchaser_id')) {
                $table->renameColumn('purchaser_id', 'laga_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'laga_code')) {
                $table->renameColumn('laga_code', 'purchaser_code');
            }
            if (Schema::hasColumn('purchases', 'laga_id')) {
                $table->renameColumn('laga_id', 'purchaser_id');
            }
        });
    }
};
