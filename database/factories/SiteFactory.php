<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelSite>
 */
class SiteFactory extends Factory
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
            'name' => $this->faker->company,
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => null,
            'address_city' => $this->faker->city,
            'address_region' => $this->faker->city,
            'address_postal_code' => $this->faker->postcode,
            'address_country_id' => 228,
            'main_contact_name' => $this->faker->name,
            'main_contact_telephone' => $this->faker->phoneNumber,
            'main_contact_email' => $this->faker->email,
            'description' => $this->faker->text,

        ];
    }
}
