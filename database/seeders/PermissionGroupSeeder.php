<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionGroup::truncate();

        $names = [
            'master',
            'transactions',
        ];

        foreach ($names as $n) {
            PermissionGroup::insert(['name' => $n, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }
    }
}
