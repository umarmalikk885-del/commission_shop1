<?php

namespace App\Console\Commands;

use App\Models\BankCashTransaction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clear {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all dummy/test records from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('⚠️  This will delete ALL data from the database. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting data cleanup...');

        try {
            // Disable foreign key checks temporarily (for SQLite compatibility)
            if (DB::getDriverName() === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }

            // Delete child tables first (respecting foreign key constraints)
            $this->info('Deleting child records...');
            
            InvoiceItem::truncate();
            $this->line('  ✓ Cleared invoice items');
            
            PurchaseItem::truncate();
            $this->line('  ✓ Cleared purchase items');
            
            BankCashTransaction::truncate();
            $this->line('  ✓ Cleared bank/cash transactions');
            
            // Delete parent tables
            $this->info('Deleting parent records...');
            
            Invoice::truncate();
            $this->line('  ✓ Cleared invoices/sales');
            
            Purchase::truncate();
            $this->line('  ✓ Cleared purchases');
            
            Stock::truncate();
            $this->line('  ✓ Cleared stock records');
            
            Vendor::truncate();
            $this->line('  ✓ Cleared vendors');

            // Re-enable foreign key checks
            if (DB::getDriverName() === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $this->newLine();
            $this->info('✅ All dummy/test data has been cleared successfully!');
            $this->info('Note: Users and company settings have been preserved.');

            return 0;
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            if (DB::getDriverName() === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $this->error('❌ Error clearing data: ' . $e->getMessage());
            return 1;
        }
    }
}
