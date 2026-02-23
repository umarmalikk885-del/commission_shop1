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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->date('invoice_date');
            $table->string('customer');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index('invoice_date');
            $table->index('customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
