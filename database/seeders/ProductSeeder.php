<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'product_name' => 'الفواتير المستعصية',
            'description' => null,
            'section_id' => 1,
        ];

        DB::table('products')->insert($data);

    }
}
