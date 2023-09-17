<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'section_name' => 'البنك الاهلي',
            'description' => null,
            'created_by' => 'yousef nasser',
        ];

        DB::table('sections')->insert($data);

    }
}
