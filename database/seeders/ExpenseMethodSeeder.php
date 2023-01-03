<?php

namespace Database\Seeders;

use App\Models\ExpenseMethod;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseMethod::truncate();
        ExpenseMethod::insert([
            ['name' => 'Tunai', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Cicilan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gratis', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Transfer', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lain - lain', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
