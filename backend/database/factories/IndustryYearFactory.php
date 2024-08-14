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
            'academic_year' => $this->faker->word, // Placeholder
            'had_placement_status' => $this->faker->randomElement(['yes', 'no']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (IndustryYear $industryYear) {
            $years = ['23/24', '24/25', '25/26', '22/23', '21/22'];
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
