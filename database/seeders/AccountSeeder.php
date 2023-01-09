<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::truncate();
        Account::insert([
            ['code' => '10001', 'name' => 'Kas', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['code' => '10002', 'name' => 'Rekening Bank', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
