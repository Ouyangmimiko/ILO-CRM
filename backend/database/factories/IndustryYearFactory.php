<?php

namespace Database\Factories;

use App\Models\MasterRecord;
use App\Models\IndustryYear;
use App\Models\MentoringPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IndustryYear>
 */
class IndustryYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = IndustryYear::class;

    public function definition(): array
    {
        return [
            'master_record_id' => null, // Placeholder
            'academic_year' => $this->faker->unique()->word, // Placeholder
            'had_placement_status' => $this->faker->randomElement(['yes', 'no']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (IndustryYear $industryYear) {
            $years = ['2023-2024', '2024-2025', '2025-2026', '2022-2023', '2021-2022'];
            $usedYears = IndustryYear::where('master_record_id', $industryYear->master_record_id)
                ->pluck('academic_year')
                ->toArray();
            $availableYears = array_diff($years, $usedYears);

            $industryYear->update([
                'academic_year' => $this->faker->randomElement($availableYears),
            ]);
        });
    }
}
