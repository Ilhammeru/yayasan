<?php

namespace Database\Factories;

use App\Models\InstitutionClassLevel;
use App\Models\Intitution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalUser>
 */
class InternalUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $test = InstitutionClassLevel::first();
        $ins = InstitutionClassLevel::with('class.institution')->find($test->id);
        
        return [
            'name' => fake()->name(),
            'institution_id' => $ins->class->institution->id,
            'nis' => fake()->unique()->numberBetween(10000, 20000),
            'parent_data' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'district_id' => 957,
            'city_id' => 78,
            'province_id' => 4,
            'institution_class_id' => $ins->class->id,
            'institution_class_level_id' => $ins->id,
            'status' => true
        ];
    }
}
