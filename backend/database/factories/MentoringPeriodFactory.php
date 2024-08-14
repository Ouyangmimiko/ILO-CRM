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
            'master_record_id' => MasterRecord::factory()->create()->id,
            'academic_year' => $this->faker->word,
            'mentoring_status' => $this->faker->randomElement(['yes', 'no']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (MentoringPeriod $mentoringPeriod) {
            $years = ['23/24', '24/25', '25/26', '22/23', '21/22'];
            $usedYears = MentoringPeriod::where('master_record_id', $mentoringPeriod->master_record_id)
                ->pluck('academic_year')
                ->toArray();
            $availableYears = array_diff($years, $usedYears);
            $mentoringPeriod->update([
                'academic_year' => $this->faker->randomElement($availableYears),
            ]);
        });
    }
}
