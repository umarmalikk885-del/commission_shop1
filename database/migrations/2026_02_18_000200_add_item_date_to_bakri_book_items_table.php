<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bakri_book_items')) {
            Schema::table('bakri_book_items', function (Blueprint $table) {
                if (!Schema::hasColumn('bakri_book_items', 'item_date')) {
                    $table->date('item_date')->nullable()->after('code');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bakri_book_items')) {
            Schema::table('bakri_book_items', function (Blueprint $table) {
                if (Schema::hasColumn('bakri_book_items', 'item_date')) {
                    $table->dropColumn('item_date');
                }
            });
        }
    }
};
