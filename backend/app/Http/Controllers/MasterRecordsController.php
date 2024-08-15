<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterRecord;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        if ($startYear >= $endYear) {
            return response()->json(['error' => 'Start year must be less than end year'],400);
        }
        $yearRange = $this->getAcademicYearRange($startYear, $endYear);
        $masterRecords = $this->getMasterRecordsByYearRange($yearRange);
        return response()->json([$masterRecords]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'organisation' => ['required', 'string', 'max:255'],
            'organisation_sector' =>  ['nullable', 'string', 'max:255'],
            'first_name' =>  ['required', 'string', 'max:255','regex:/^[\pL\s\-]+$/u'],
            'surname' =>  ['required', 'string', 'max:255','regex:/^[\pL\s\-]+$/u'],
            'job_title' => 'nullable|string|max:255',
            'email' => ['required','email', 'unique:master_records,email'],
            'location' => 'nullable|string|max:255',
            'uob_alumni' => 'nullable|string|regex:/^(yes|no)$/',
            'programme_of_study_engaged' => 'string|max:255',

            // mentoring periods
            'mentoring_periods' => 'nullable|array',
            'mentoring_periods.*.academic_year' =>  ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'mentoring_periods.*.status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // industry years
            'industry_years' => 'nullable|array',
            'industry_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'industry_years.*.had_placement_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // projects
            'projects' => 'array',
            'projects.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'projects.*.project_client' => 'required|string|max:255',

            // other engagement
            'other_engagement' => 'nullable|array',
            'other_engagement.society_engaged_or_to_engage' => 'nullable|string|max:255',
            'other_engagement.engagement_type' => 'nullable|string|max:255',
            'other_engagement.engagement_happened' => 'nullable|string|max:255',
            'other_engagement.notes' => 'nullable|text',
        ]);

        DB::beginTransaction();

        try {
            $masterRecord = MasterRecord::create([
                'id' => (string) Str::uuid(),
                'organisation' => $validatedData['organisation'],
                'organisation_sector' => $validatedData['organisation_sector'] ?? null,
                'first_name' => $validatedData['first_name'],
                'surname' => $validatedData['surname'],
                'job_title' => $validatedData['job_title'] ?? null,
                'email' => $validatedData['email'],
                'location' => $validatedData['location'] ?? null,
                'uob_alumni' => $validatedData['uob_alumni'] ?? null,
                'programme_of_study_engaged' => $validatedData['programme_of_study_engaged'] ?? null,
            ]);

            if (!empty($validatedData['mentoring_periods'])) {
                foreach ($validatedData['mentoring_periods'] as $mentoringPeriod) {
                    $masterRecord->mentoringPeriods()->create([
                        'academic_year' => $mentoringPeriod['academic_year'],
                        'mentoring_status' => $mentoringPeriod['status'],
                    ]);
                }
            }

            if (!empty($validatedData['industry_years'])) {
                foreach ($validatedData['industry_years'] as $industryYear) {
                    $masterRecord->industryYears()->create([
                        'academic_year' => $industryYear['academic_year'],
                        'had_placement_status' => $industryYear['had_placement_status'],
                    ]);
                }
            }

            if (!empty($validatedData['projects'])) {
                foreach ($validatedData['projects'] as $project) {
                    $masterRecord->projects()->create([
                        'academic_year' => $project['academic_year'],
                        'project_client' => $project['project_client'],
                    ]);
                }
            }

            if (!empty($validatedData['other_engagement'])) {
                $masterRecord->otherEngagement()->create($validatedData['other_engagement']);
            }

            DB::commit();

            return response()->json(['message' => 'Record created successfully.','data' => $masterRecord], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to create record.'], 500);
        }

//        $masterRecord = MasterRecord::create([
//            'id' => (string) Str::uuid(),
//            'organisation' => $validatedData['organisation'],
//            'organisation_sector' => $validatedData['organisation_sector'] ?? null,
//            'first_name' => $validatedData['first_name'],
//            'surname' => $validatedData['surname'],
//            'job_title' => $validatedData['job_title'] ?? null,
//            'email' => $validatedData['email'],
//            'location' => $validatedData['location'] ?? null,
//            'uob_alumni' => $validatedData['uob_alumni'] ?? null,
//            'programme_of_study_engaged' => $validatedData['programme_of_study_engaged'] ?? null,
//        ]);
//
//        if (!empty($validatedData['mentoring_periods'])) {
//            foreach ($validatedData['mentoring_periods'] as $mentoringPeriod) {
//                $masterRecord->mentoringPeriods()->create([
//                    'academic_year' => $mentoringPeriod['academic_year'],
//                    'mentoring_status' => $mentoringPeriod['status'],
//                ]);
//            }
//        }
//
//        if (!empty($validatedData['industry_years'])) {
//            foreach ($validatedData['industry_years'] as $industryYear) {
//                $masterRecord->industryYears()->create([
//                    'academic_year' => $industryYear['academic_year'],
//                    'had_placement_status' => $industryYear['had_placement_status'],
//                ]);
//            }
//        }
//
//        if (!empty($validatedData['projects'])) {
//            foreach ($validatedData['projects'] as $project) {
//                $masterRecord->projects()->create([
//                    'academic_year' => $project['academic_year'],
//                    'project_client' => $project['project_client'],
//                ]);
//            }
//        }
//
//        if (!empty($validatedData['other_engagement'])) {
//            $masterRecord->otherEngagement()->create($validatedData['other_engagement']);
//        }
//
//        return response()->json(['message' => 'Record created successfully', 'data' => $masterRecord], 201);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $masterRecord = MasterRecord::findOrFail($id);

            $masterRecord->delete();

            DB::commit();

            return response()->json(['message' => 'Delete successfully'], );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Delete record failed'], );
        }
    }

    public function update(Request $request, $id)
    {
        $masterRecord = MasterRecord::findOrFail($id);

        $validatedData = $request->validate([
            'organisation' => 'required|string|max:255',
            'organisation_sector' => 'nullable|string|max:255',
            'first_name' =>  ['required', 'string', 'max:255','regex:/^[\pL\s\-]+$/u'],
            'surname' =>  ['required', 'string', 'max:255','regex:/^[\pL\s\-]+$/u'],
            'job_title' => 'nullable|string|max:255',
            'email' => ['required','email', 'unique:master_records,email,' . $id],
            'location' => 'nullable|string|max:255',
            'uob_alumni' => 'nullable|string|regex:/^(yes|no)$/',
            'programme_of_study_engaged' => 'string|max:255',

            // mentoring periods
            'mentoring_periods' => 'nullable|array',
            'mentoring_periods.*.academic_year' =>  ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'mentoring_periods.*.status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // industry years
            'industry_years' => 'nullable|array',
            'industry_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'industry_years.*.had_placement_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // projects
            'projects' => 'array',
            'projects.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'projects.*.project_client' => 'required|string|max:255',

            // other engagement
            'other_engagement' => 'nullable|array',
            'other_engagement.society_engaged_or_to_engage' => 'nullable|string|max:255',
            'other_engagement.engagement_type' => 'nullable|string|max:255',
            'other_engagement.engagement_happened' => 'nullable|string|max:255',
            'other_engagement.notes' => 'nullable|text',
        ]);

        DB::beginTransaction();

        try {
            // update master records
            $masterRecord->update([
                'organisation' => $validatedData['organisation'],
                'organisation_sector' => $validatedData['organisation_sector'] ?? null,
                'first_name' => $validatedData['first_name'],
                'surname' => $validatedData['surname'],
                'job_title' => $validatedData['job_title'] ?? null,
                'email' => $validatedData['email'],
                'location' => $validatedData['location'] ?? null,
                'uob_alumni' => $validatedData['uob_alumni'] ?? null,
                'programme_of_study_engaged' => $validatedData['programme_of_study_engaged'] ?? null,
            ]);

            // update relative table
            if (!empty($validatedData['mentoring_periods'])) {
                foreach ($validatedData['mentoring_periods'] as $mentoringPeriod) {
                    // check if exist
                    $existingRecord = $masterRecord->mentoringPeriods()
                        ->where('academic_year', $mentoringPeriod['academic_year'])
                        ->first();
                    if ($existingRecord) {
                        // if exist
                        $existingRecord->update([
                            'mentoring_status' => $mentoringPeriod['status'],
                        ]);
                    } else {
                        // creat new records
                        $masterRecord->mentoringPeriods()->create([
                            'academic_year' => $mentoringPeriod['academic_year'],
                            'mentoring_status' => $mentoringPeriod['status'],
                        ]);
                    }
                }
            }

            if (!empty($validatedData['industry_years'])) {
                foreach ($validatedData['industry_years'] as $industryYear) {
                    $existingRecord = $masterRecord->industryYears()
                        ->where('academic_year', $industryYear['academic_year'])
                        ->first();
                    if ($existingRecord) {
                        $existingRecord->update([
                            'had_placement_status' => $industryYear['had_placement_status'],
                        ]);
                    } else {
                        $masterRecord->industryYears()->create([
                            'academic_year' => $industryYear['academic_year'],
                            'had_placement_status' => $industryYear['had_placement_status'],
                        ]);
                    }
                }
            }
            if (!empty($validatedData['projects'])) {
                foreach ($validatedData['projects'] as $project) {
                    $existingRecord = $masterRecord->projects()
                        ->where('academic_year', $project['academic_year'])
                        ->first();
                    if ($existingRecord) {
                        $existingRecord->update([
                            'project_client' => $project['project_client'],
                        ]);
                    } else {
                        $masterRecord->projects()->create([
                            'academic_year' => $project['academic_year'],
                            'project_client' => $project['project_client'],
                        ]);
                    }
                }
            }
            if (!empty($validatedData['other_engagement'])) {
                $masterRecord->otherEngagement()->delete(); // delete old record
                $masterRecord->otherEngagement()->create($validatedData['other_engagement']);
            }

            DB::commit();

            $updatedRecord = MasterRecord::findOrFail($id)->load(['mentoringPeriods', 'industryYears', 'projects', 'otherEngagement']);

            return response()->json(['message' => 'Update success', 'data' => $updatedRecord]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
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

        if ($startYear >= $endYear) {
            throw new \Exception("Start year must be less than end year.");
        }

        for ($year = $startYear; $year < $endYear; $year++) {
            $academicYearStart = Carbon::create($year, $startMonth, $startDay);
            $academicYearEnd = Carbon::create($year + 1, $endMonth, $endDay);
            $years[] = $academicYearStart->format('Y') . '-' . $academicYearEnd->format('Y');
        }

        return $years;
    }
}
