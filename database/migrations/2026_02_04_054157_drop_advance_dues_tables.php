<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('vendor_loans');
        Schema::dropIfExists('product_owner_loans');
    }

    public function down()
    {
        Schema::create('vendor_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->date('loan_date');
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('product_owner_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('owner_name')->nullable();
            $table->date('loan_date');
            $table->integer('advance_amount');
            $table->integer('paid_amount')->default(0);
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
