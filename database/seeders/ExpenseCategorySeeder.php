<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseCategory::truncate();
        ExpenseCategory::insert([
            ['name' => 'SPP', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sumbangan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Uang Gedung', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lain - lain', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
