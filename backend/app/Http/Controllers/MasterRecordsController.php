<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterRecord;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterRecordsController extends Controller
{
    public function index()
    {
        // Default index with current academic year
        $currentAcademicYear = $this->getCurrentAcademicYearRange();
        $masterRecords = $this->getMasterRecordsByYearRange($currentAcademicYear);
        return response()->json([$masterRecords]);
    }

    public function indexByYearRange(Request $request)
    {
        $validatedData = $request->validate([
            'academic_year_start' => [
                'required',
                'regex:/^\d{4}$/'
            ],
            'academic_year_end' => [
                'required',
                'regex:/^\d{4}$/'
            ],
        ]);
        $startYear = $validatedData['academic_year_start'];
        $endYear = $validatedData['academic_year_end'];
        $yearRange = $this->getAcademicYearRange($startYear, $endYear);
        $masterRecords = $this->getMasterRecordsByYearRange($yearRange);
        return response()->json([$masterRecords]);
    }

    public function getMasterRecordsByYearRange(array $yearRange)
    {
        if (empty($yearRange)) {
            return response()->json(['error' => 'No valid years provided.'], 400);
        }
        $masterRecords = MasterRecord::with([
            'mentoringPeriods' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'industryYears' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'projects' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'otherEngagement'
        ])->get();
        $formattedMasterRecords = $masterRecords->map(function ($record) {
            return [
                'id' => $record->id,
                'organisation' => $record->organisation,
                'organisation_sector' => $record->organisation_sector,
                'first_name' => $record->first_name,
                'surname' => $record->surname,
                'job_title' => $record->job_title,
                'email' => $record->email,
                'location' => $record->location,
                'uob_alumni' => $record->uob_alumni,
                'programme_of_study_engaged' => $record->programme_of_study_engaged,
                'mentoring_periods' => $record->mentoringPeriods ? $record->mentoringPeriods->map(function ($mentoringPeriod) {
                    return [
                        'academic_year' => $mentoringPeriod->academic_year,
                        'status' => $mentoringPeriod->mentoring_status,
                    ];
                }) : [],
                'industry_years' => $record->industryYears ? $record->industryYears->map(function ($industryYear) {
                    return [
                        'academic_year' => $industryYear->academic_year,
                        'had_placement_status' => $industryYear->had_placement_status,
                    ];
                }) : [],
                'projects' => $record->projects ? $record->projects->map(function ($project) {
                    return [
                        'year' => $project->academic_year,
                        'project_client' => $project->project_client,
                    ];
                }) : [],
                'other_engagement' => [
                    'society_engaged_or_to_engage' => $record->otherEngagement->society_engaged_or_to_engage,
                    'engagement_type' => $record->otherEngagement->engagement_type,
                    'engagement_happened' => $record->otherEngagement->engagement_happened,
                    'notes' => $record->otherEngagement->notes,
                ]
            ];
        });
        return $formattedMasterRecords;
    }

    public function getCurrentAcademicYearRange(): array
    {
        $startMonth = 9;
        $startDay = 1;
        $endMonth = 6;
        $endDay = 30;

        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $startYear = $currentYear;
        $endYear = $currentYear + 1;

        $academicYearStart = Carbon::create($currentYear, $startMonth, $startDay);
        $academicYearEnd = Carbon::create($currentYear + 1, $endMonth, $endDay);

        // calculate current academic year period
        if ($currentDate->isBefore($academicYearStart)) {
            $startYear = $academicYearStart->year - 1;
            $endYear = $academicYearEnd->year - 1;
        }

        return $this->getAcademicYearRange($startYear, $endYear);
    }

    public function getAcademicYearRange($startYear, $endYear): array
    {
        $startMonth = 9;
        $startDay = 1;
        $endMonth = 6;
        $endDay = 30;
        $years = [];

        if ($startYear > $endYear) {
            throw new InvalidArgumentException("Start year must be less than or equal to end year.");
        }
//        else if ($startYear == $endYear-1) {
//            return [$startYear . '-' . $endYear];
//        }

        for ($year = $startYear; $year < $endYear; $year++) {
            $academicYearStart = Carbon::create($year, $startMonth, $startDay);
            $academicYearEnd = Carbon::create($year + 1, $endMonth, $endDay);
            $years[] = $academicYearStart->format('Y') . '-' . $academicYearEnd->format('Y');
        }

        return $years;
    }
}
