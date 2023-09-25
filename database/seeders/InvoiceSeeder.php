<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoice = [
            [
                'invoice_number' => 'INV-001',
                'invoice_date' => '2023-09-01',
                'due_date' => '2023-09-15',
                'product' => 'الفواتير المستعصية',
                'section_id' => 1,
                'amount_collection' => 1000.00,
                'amount_commission' => 50.00,
                'discount' => 0.00,
                'value_vat' => 150.00,
                'rate_vat' => '15%',
                'total' => 1100.00,
                'status' => 'غير مدفوعة',
                'value_status' => 0,
                'note' => 'لم يتم الدفع',
                'payment_date' => '2023-09-05',

            ],
            [
                'invoice_number' => 'INV-002',
                'invoice_date' => '2023-09-01',
                'due_date' => '2023-09-15',
                'product' => 'الفواتير المستعصية',
                'section_id' => 1,
                'amount_collection' => 1000.00,
                'amount_commission' => 50.00,
                'discount' => 0.00,
                'value_vat' => 150.00,
                'rate_vat' => '15%',
                'total' => 1100.00,
                'status' => 'غير مدفوعة',
                'value_status' => 0,
                'note' => 'لم يتم الدفع',
                'payment_date' => '2023-09-05',
            ],
        ];

        DB::table('invoices')->insert($invoice);
    }
}
