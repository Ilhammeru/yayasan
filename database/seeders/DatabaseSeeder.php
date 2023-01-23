<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RolePermissionSeeder::class,
            PermissionGroupSeeder::class,
            ExpenseCategorySeeder::class,
            ExpenseMethodSeeder::class,
            ExpenseTypeSeeder::class,
            ExpenseMainSeeder::class,
            IncomeMethodSeeder::class,
            IncomeTypeSeeder::class,
            IncomeCategorySeeder::class,
            InstitutionSeeder::class,
            InternalUserSeeder::class,
            AccountSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
