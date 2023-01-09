<?php

namespace Database\Seeders;

use App\Models\IncomeMethod;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IncomeMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        IncomeMethod::truncate();
        Schema::enableForeignKeyConstraints();
        IncomeMethod::insert([
            ['name' => 'Tunai', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Cicilan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gratis', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Transfer', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lain - lain', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
