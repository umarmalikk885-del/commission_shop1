<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vendor_dues');
    }

    public function down(): void
    {
        Schema::create('vendor_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('payable_amount', 14, 2);
            $table->decimal('commission_amount', 14, 2)->default(0);
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index('vendor_id');
            $table->index('order_id');
            $table->index('payment_status');
        });
    }
};
