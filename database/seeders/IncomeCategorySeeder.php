<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use App\Models\IncomeMethod;
use App\Models\IncomeType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        IncomeType::truncate();
        IncomeCategory::truncate();
        Schema::enableForeignKeyConstraints();

        IncomeType::insert([
            ['name' => 'Sekali Bayar', 'period' => '1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Harian', 'period' => '30', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Mingguan', 'period' => '5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bulanan', 'period' => '12', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tahunan', 'period' => '1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        
        IncomeCategory::insert([
            ['name' => 'SPP', 'income_type_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sumbangan', 'income_type_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Uang Gedung', 'income_type_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lain - lain', 'income_type_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tabungan', 'income_type_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
