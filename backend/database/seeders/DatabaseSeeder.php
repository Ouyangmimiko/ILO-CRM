<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MasterRecord;
use App\Models\MentoringPeriod;
use App\Models\OtherEngagement;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\IndustryYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        Customer::factory()->count(1)->create();
//        DB::transaction(function () {
//            for ($i = 0; $i < 20; $i++) {
//                Customer::factory()->create();
//            }
//        });
        MasterRecord::factory()
            ->count(4)
            ->has(MentoringPeriod::factory()->count(4))
            ->has(IndustryYear::factory()->count(4))
            ->hasOtherEngagement()
            ->create();
//        $masterRecords = MasterRecord::factory()
//            ->count(5)
//            ->create();
//        foreach ($masterRecords as $masterRecord) {
//            for ($i = 0; $i < 4; $i++) {
////                MentoringPeriod::factory()->create();
//                IndustryYear::factory()->create(['master_record_id' => $masterRecord->id]);
//            }
//        }

    }
}
