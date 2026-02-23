<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * مزدوری (Labor Rates) table for Sabzi Mandi items
     */
    public function up(): void
    {
        Schema::create('labor_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Item details
            $table->string('item_code')->nullable(); // کوڈ
            $table->string('item_name'); // آئٹم کا نام (sabzi name)
            $table->string('category')->nullable(); // قسم (category like سبزی, پھل etc)
            
            // Labor rates
            $table->decimal('labor_rate', 10, 2)->default(0); // مزدوری ریٹ
            $table->string('unit')->nullable()->default('kg'); // یونٹ (kg, dozen, piece etc)
            
            // Additional info
            $table->text('notes')->nullable(); // نوٹس
            $table->boolean('is_active')->default(true); // فعال
            
            $table->timestamps();
            
            // Index for faster lookup
            $table->index(['item_code', 'item_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_rates');
    }
};
