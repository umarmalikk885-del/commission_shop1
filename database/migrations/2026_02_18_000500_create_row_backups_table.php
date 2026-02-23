<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('row_backups', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('data');
            $table->string('operation_type', 20)->default('insert');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('inserted_at')->nullable();
            $table->timestamps();
            $table->index(['table_name', 'record_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('row_backups');
    }
};

