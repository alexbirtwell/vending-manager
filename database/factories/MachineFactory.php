<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelMachine>
 */
class MachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'machine_number' => $this->faker->unique()->numberBetween(1, 100),
            'site_id' => Site::factory(),
            'machine_type'=> $this->faker->randomElement(['Snack', 'Drinks', 'Snack & Drinks']),
            'payment_mechanic' => $this->faker->randomElement(['Cash', 'Card', 'Cash & Card']),
            'brand' => $this->faker->company,
            'model' => $this->faker->numberBetween(1000,9999)
        ];
    }
}
