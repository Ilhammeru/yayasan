<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseType::truncate();
        ExpenseType::insert([
            ['name' => 'Sekali Bayar', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Harian', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Mingguan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bulanan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tahunan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
