<?php

namespace Database\Seeders;

use App\Models\InternalUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class InternalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        InternalUser::truncate();
        Schema::enableForeignKeyConstraints();

        InternalUser::factory(35)->create();
    }
}
