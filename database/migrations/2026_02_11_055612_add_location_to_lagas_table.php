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
        // Use the current table name (purchasers) since lagas was renamed back
        $tableName = Schema::hasTable('purchasers') ? 'purchasers' : 'lagas';
        
        Schema::table($tableName, function (Blueprint $table) {
            $table->string('location')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lagas', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
