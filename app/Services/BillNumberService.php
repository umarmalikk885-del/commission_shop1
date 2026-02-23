<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Purchase;

class BillNumberService
{
    /**
     * Generate automatic bill number for invoices
     * 
     * @return string
     */
    public function generateInvoiceBillNo(): string
    {
        $latestInvoice = Invoice::latest('id')->first();
        
        if ($latestInvoice) {
            $billNo = $latestInvoice->bill_no;
            if (preg_match('/INV-(\d+)/i', $billNo, $matches)) {
                $nextNumber = (int)$matches[1] + 1;
            } else {
                $nextNumber = Invoice::count() + 1;
            }
        } else {
            $nextNumber = 1;
        }
        
        return 'INV-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate automatic bill number for purchases
     * 
     * @return string
     */
    public function generatePurchaseBillNumber(): string
    {
        $latestPurchase = Purchase::whereNotNull('bill_number')->latest('id')->first();
        
        if ($latestPurchase && $latestPurchase->bill_number) {
            $billNo = $latestPurchase->bill_number;
            if (preg_match('/PUR-(\d+)/i', $billNo, $matches)) {
                $nextNumber = (int)$matches[1] + 1;
            } else {
                $nextNumber = Purchase::whereNotNull('bill_number')->count() + 1;
            }
        } else {
            $nextNumber = 1;
        }
        
        return 'PUR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
