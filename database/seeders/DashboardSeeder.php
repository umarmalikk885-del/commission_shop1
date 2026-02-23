<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Company Settings (only if doesn't exist)
        CompanySetting::firstOrCreate(
            ['company_name' => 'Commission Shop'],
            [
                'address' => 'Shop Address',
                'phone' => '0300-0000000'
            ]
        );
    }
}
