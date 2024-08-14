<?php

namespace Database\Factories;

use App\Models\MasterRecord;
use App\Models\OtherEngagement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtherEngagement>
 */
class OtherEngagementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OtherEngagement::class;
    public function definition(): array
    {
        return [
            'master_record_id' => null,
            'society_engaged_or_to_engage' => $this->faker->company,
            'engagement_type' => $this->faker->randomElement(['Talks', 'Site Visit', 'Design Challenge']),
            'engagement_happened' => $this->faker->randomElement(['Sponsored', 'No', 'Internship']),
            'notes' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
