<?php

namespace Database\Seeders;

use App\Models\ExpenseMain;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseMainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseMain::truncate();
        ExpenseMain::insert([
            ['name' => 'Beban Operasional', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaji Pokok', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaji SD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaji TK', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaji MTS', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaji Yayasan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Internet', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Listrik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
