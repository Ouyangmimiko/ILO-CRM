<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'id' => Customer::generateCustomerId(),  //自定义id写法
            'organisation' => $this->faker->company,
            'organisation_sector' => $this->faker->word,
            'first_name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'job_title' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'location' => $this->faker->address,
            'uob_alumni' => $this->faker->randomElement(['yes', 'no']),
            'programme_of_study_engaged' => $this->faker->words(3, true),
            'mentoring_2021_22' => $this->faker->randomElement(['yes', 'no']),
            'mentoring_2022_23' => $this->faker->randomElement(['yes', 'no']),
            'mentoring_2023_24' => $this->faker->randomElement(['yes', 'no']),
            'yii_2021_22' => $this->faker->randomElement(['yes', 'no']),
            'yii_2022_23' => $this->faker->randomElement(['yes', 'no']),
            'yii_2023_24' => $this->faker->randomElement(['yes', 'no']),
            'projects_2021_22' => $this->faker->randomElement(['yes', 'no']),
            'projects_2022_23' => $this->faker->randomElement(['yes', 'no']),
            'projects_2023_24' => $this->faker->randomElement(['yes', 'no']),
            'info_related_to_contacting_the_partner' => $this->faker->paragraph
        ];
    }
}
