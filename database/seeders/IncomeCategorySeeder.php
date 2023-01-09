<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IncomeCategory::truncate();
        IncomeCategory::insert([
            ['name' => 'SPP', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sumbangan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Uang Gedung', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lain - lain', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
