<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bakri_books')) {
            Schema::table('bakri_books', function (Blueprint $table) {
                if (!Schema::hasColumn('bakri_books', 'food_rent')) {
                    $table->decimal('food_rent', 12, 2)->default(0)->after('fare');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bakri_books')) {
            Schema::table('bakri_books', function (Blueprint $table) {
                if (Schema::hasColumn('bakri_books', 'food_rent')) {
                    $table->dropColumn('food_rent');
                }
            });
        }
    }
};
