<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * First table items for بکری بُک (8 rows data entry)
     */
    public function up(): void
    {
        Schema::create('bakri_book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bakri_book_id')->constrained()->onDelete('cascade');
            
            // First table columns
            $table->string('code')->nullable(); // کوڈ
            $table->string('item_type')->nullable(); // قسمِ مال
            $table->string('packing_code')->nullable(); // کوڈ (پیکنگ)
            $table->string('packing')->nullable(); // پیکنگ
            $table->decimal('quantity', 12, 2)->default(0); // تعداد
            $table->decimal('labor_rate', 12, 2)->default(0); // مزدوری ریٹ
            $table->decimal('labor', 12, 2)->default(0); // مزدوری
            $table->decimal('labor_transport', 12, 2)->default(0); // مزدور چالائی
            $table->decimal('commission_rate', 12, 2)->default(0); // کمیشن ریٹ
            $table->string('marker')->nullable(); // * (asterisk/marker)
            
            $table->integer('row_order')->default(0); // To maintain row order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bakri_book_items');
    }
};
