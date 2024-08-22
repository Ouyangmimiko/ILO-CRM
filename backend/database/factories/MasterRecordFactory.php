<?php

namespace Database\Factories;

use App\Models\MasterRecord;
use App\Models\MentoringPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function Webmozart\Assert\Tests\StaticAnalysis\uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterRecord>
 */
class MasterRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MasterRecord::class;
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'organisation' => $this->faker->company,
            'first_name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'job_title' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'location' => $this->faker->city,
            'uob_alumni' => $this->faker->randomElement(['yes', 'no']),
            'programme_of_study_engaged' => $this->faker->randomElement(['MECH', 'Aerospace Engineering', 'CS']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
