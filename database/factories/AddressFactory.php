<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maggomann\LaravelAddressable\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maggomann\LaravelAddressable\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Maggomann\LaravelAddressable\Models\Address>
     */
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'gender_id' => $this->faker->randomElement([1, 2]),
            'category_id' => 1,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'name' => $this->faker->name,
            'street_address' => $this->faker->streetAddress,
            'street_addition' => $this->faker->streetSuffix,
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country_code' => $this->faker->countryCode,
            'state' => null,
            'company' => $this->faker->company,
            'job_title' => $this->faker->jobTitle,
            'is_preferred' => true,
            'is_main' => true,
        ];
    }
}
