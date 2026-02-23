<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bakri_books', function (Blueprint $table) {
            $table->index('trader', 'bakri_books_trader_idx');
            $table->index('goat_number', 'bakri_books_goat_idx');
            $table->index('truck_number', 'bakri_books_truck_idx');
            $table->index('record_date', 'bakri_books_record_date_idx');
        });

        Schema::table('bakri_book_transactions', function (Blueprint $table) {
            $table->index('book', 'bakri_transactions_book_idx');
            $table->index('book_code', 'bakri_transactions_book_code_idx');
            $table->index('transaction_date', 'bakri_transactions_date_idx');
            $table->index('bakri_book_id', 'bakri_transactions_book_fk_idx');
        });

        Schema::table('bakri_book_items', function (Blueprint $table) {
            $table->index('item_type', 'bakri_items_item_type_idx');
            $table->index('code', 'bakri_items_code_idx');
            $table->index('bakri_book_id', 'bakri_items_book_fk_idx');
        });
    }

    public function down(): void
    {
        Schema::table('bakri_books', function (Blueprint $table) {
            $table->dropIndex('bakri_books_trader_idx');
            $table->dropIndex('bakri_books_goat_idx');
            $table->dropIndex('bakri_books_truck_idx');
            $table->dropIndex('bakri_books_record_date_idx');
        });

        Schema::table('bakri_book_transactions', function (Blueprint $table) {
            $table->dropIndex('bakri_transactions_book_idx');
            $table->dropIndex('bakri_transactions_book_code_idx');
            $table->dropIndex('bakri_transactions_date_idx');
            $table->dropIndex('bakri_transactions_book_fk_idx');
        });

        Schema::table('bakri_book_items', function (Blueprint $table) {
            $table->dropIndex('bakri_items_item_type_idx');
            $table->dropIndex('bakri_items_code_idx');
            $table->dropIndex('bakri_items_book_fk_idx');
        });
    }
};
