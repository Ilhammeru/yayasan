<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\InstitutionIncomeCategory;
use App\Models\Intitution;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Intitution::truncate();
        InstitutionClass::truncate();
        InstitutionClassLevel::truncate();
        Schema::enableForeignKeyConstraints();

        Intitution::insert([
            [
                'name' => 'SD',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'SMP',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'SMA',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        
        $incomeCategories = IncomeCategory::all();

        for ($a = 1; $a < 4; $a++) {
            InstitutionClass::insert([
                'intitution_id' => 1,
                'name' => $a,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $levels = ['a', 'b', 'c'];
        for ($b = 0; $b < count($levels); $b++) {
            InstitutionClassLevel::insert([
                'institution_class_id' => 1,
                'name' => $levels[$b],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            InstitutionClassLevel::insert([
                'institution_class_id' => 2,
                'name' => $levels[$b],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        foreach ($incomeCategories as $ic) {
            InstitutionIncomeCategory::insert([
                'institution_id' => 1,
                'income_category_id' => $ic->id,
                'status' => 1,
            ]);
        }
    }
}
