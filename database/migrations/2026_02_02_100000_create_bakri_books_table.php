<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Main table for ادائیگی (Payment) - بکری بُک records
     */
    public function up(): void
    {
        Schema::create('bakri_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Header fields
            $table->date('record_date'); // تاریخ
            $table->string('trader')->nullable(); // بیوپاری
            $table->string('goat_number')->nullable(); // بکری نمبر
            $table->string('truck_number')->nullable(); // ٹرک نمبر
            
            // Summary Panel fields (Left side)
            $table->decimal('raw_goat', 12, 2)->default(0); // خام بکری
            $table->decimal('fare', 12, 2)->default(0); // کرایہ
            $table->decimal('commission', 12, 2)->default(0); // کمیشن
            $table->decimal('labor', 12, 2)->default(0); // مزدوری
            $table->decimal('mashiana', 12, 2)->default(0); // مشیانہ
            $table->decimal('stamp', 12, 2)->default(0); // سٹائپ
            $table->decimal('other_expenses', 12, 2)->default(0); // دیق خرچہ
            $table->decimal('total_expenses', 12, 2)->default(0); // کل اخراجات
            $table->decimal('net_goat', 12, 2)->default(0); // صافی بکری
            
            // Balance fields (میزان)
            $table->decimal('balance1', 12, 2)->default(0); // میزان 1 (after first table)
            $table->decimal('balance2', 12, 2)->default(0); // میزان 2 (after third table)
            
            // Footer
            $table->text('additional_details')->nullable(); // اضافی تفصیل
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bakri_books');
    }
};
