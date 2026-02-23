<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Third table - Date-wise transactions for بکری بُک
     */
    public function up(): void
    {
        Schema::create('bakri_book_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bakri_book_id')->constrained()->onDelete('cascade');
            
            // Third table columns
            $table->date('transaction_date')->nullable(); // تاریخ
            $table->string('book_code')->nullable(); // کوڈ کا بک
            $table->string('book')->nullable(); // کا بک
            $table->decimal('trader_quantity', 12, 2)->default(0); // تعداد بیوپاری
            $table->decimal('trader_rate', 12, 2)->default(0); // ریٹ بیوپاری
            $table->decimal('trader_amount', 12, 2)->default(0); // رقم بیوپاری
            $table->decimal('book_quantity', 12, 2)->default(0); // تعداد کا بک
            $table->decimal('book_rate', 12, 2)->default(0); // ریٹ کا بک
            $table->decimal('payment_rate', 12, 2)->default(0); // ادا گر ریٹ
            $table->decimal('book_amount', 12, 2)->default(0); // رقم کا بک
            
            $table->integer('row_order')->default(0); // To maintain row order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bakri_book_transactions');
    }
};
