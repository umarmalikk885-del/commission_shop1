<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        Schema::table('purchasers', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('name')->after('code');
            $table->string('mobile')->nullable()->after('name');
            $table->string('address')->nullable()->after('mobile');
            $table->enum('status', ['active', 'blocked'])->default('active')->after('address');
        });

        // Generate codes for existing vendors
        $vendors = DB::table('vendors')->whereNull('code')->get();
        foreach ($vendors as $vendor) {
            $code = $this->generateUniqueCode('vendors', 5);
            DB::table('vendors')->where('id', $vendor->id)->update(['code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('purchasers', function (Blueprint $table) {
            $table->dropColumn(['code', 'name', 'mobile', 'address', 'status']);
        });
    }

    private function generateUniqueCode($table, $length)
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;

        do {
            $code = mt_rand($min, $max);
        } while (DB::table($table)->where('code', $code)->exists());

        return (string) $code;
    }
};
