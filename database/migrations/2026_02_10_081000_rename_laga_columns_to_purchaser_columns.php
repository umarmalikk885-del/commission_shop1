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
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                if (Schema::hasColumn('purchases', 'laga_code')) {
                    $table->renameColumn('laga_code', 'purchaser_code');
                }
                if (Schema::hasColumn('purchases', 'laga_id')) {
                    $table->renameColumn('laga_id', 'purchaser_id');
                }
            });
        }

        if (Schema::hasTable('bakri_book_transactions')) {
            Schema::table('bakri_book_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('bakri_book_transactions', 'laga_rate')) {
                    $table->renameColumn('laga_rate', 'purchaser_rate');
                }
            });
        }

        if (Schema::hasTable('purchaser_payments')) {
             Schema::table('purchaser_payments', function (Blueprint $table) {
                if (Schema::hasColumn('purchaser_payments', 'laga_id')) {
                    $table->renameColumn('laga_id', 'purchaser_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                if (Schema::hasColumn('purchases', 'purchaser_code')) {
                    $table->renameColumn('purchaser_code', 'laga_code');
                }
                if (Schema::hasColumn('purchases', 'purchaser_id')) {
                    $table->renameColumn('purchaser_id', 'laga_id');
                }
            });
        }

        if (Schema::hasTable('bakri_book_transactions')) {
            Schema::table('bakri_book_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('bakri_book_transactions', 'purchaser_rate')) {
                    $table->renameColumn('purchaser_rate', 'laga_rate');
                }
            });
        }
        
        if (Schema::hasTable('purchaser_payments')) {
             Schema::table('purchaser_payments', function (Blueprint $table) {
                if (Schema::hasColumn('purchaser_payments', 'purchaser_id')) {
                    $table->renameColumn('purchaser_id', 'laga_id');
                }
            });
        }
    }
};
