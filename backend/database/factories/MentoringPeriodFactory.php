<?php

namespace Database\Factories;

use App\Models\MasterRecord;
use App\Models\MentoringPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MentoringPeriod>
 */
class MentoringPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = MentoringPeriod::class;
    public function definition(): array
    {
        return [
            'master_record_id' => null,
            'academic_year' => $this->faker->unique()->word,
            'mentoring_status' => $this->faker->randomElement(['yes', 'no']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (MentoringPeriod $mentoringPeriod) {
            $years = ['2023-2024', '2024-2025', '2025-2026', '2022-2023', '2021-2022'];
            $usedYears = MentoringPeriod::where('master_record_id', $mentoringPeriod->master_record_id)
                ->pluck('academic_year')
                ->toArray();
            $availableYears = array_diff($years, $usedYears);
//            echo '<pre>';
//            print_r($availableYears);
//            echo '</pre>';
            $mentoringPeriod->update([
                'academic_year' => $this->faker->randomElement($availableYears),
            ]);
        });
    }
}
