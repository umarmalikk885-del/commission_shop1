<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laga_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laga_id')->constrained('lagas')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('advance_date');
            $table->decimal('amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('laga_id');
            $table->index('advance_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laga_advances');
    }
};

