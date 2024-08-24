<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\RecordsImport;
use App\Models\MasterRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class MasterRecordsController extends Controller
{
    public function import(Request $request)
    {
        try {
            Excel::import(new RecordsImport, $request->file('file'));

            return response()->json(['message' => 'Records imported successfully.']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid data in file',
                'errors' => $e->errors(), // 返回验证错误信息
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function index()
    {
        // Default index with current academic year
        $currentAcademicYear = $this->getCurrentAcademicYearRange();
//        $masterRecords = $this->getMasterRecordsByYearRange($currentAcademicYear);
        $masterRecords = $this->getRecordsByYearRange(new MasterRecord, $currentAcademicYear);
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
//        $masterRecords = $this->getMasterRecordsByYearRange($yearRange);
        $masterRecords = $this->getRecordsByYearRange(new MasterRecord, $yearRange);
        return response()->json(['data' => $masterRecords, 'year_range' => $yearRange]);
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
            'uob_alumni' => ['nullable', 'string', 'regex:/^(yes|no)$/'],
            'programme_of_study_engaged' => 'nullable|string|max:255',

            // mentoring periods
            'mentoring_periods' => 'nullable|array',
            'mentoring_periods.*.academic_year' =>  ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'mentoring_periods.*.mentoring_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // industry years
            'industry_years' => 'nullable|array',
            'industry_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'industry_years.*.had_placement_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // project years
            'project_years' => 'array',
            'project_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'project_years.*.project_client' => 'required|string|max:255',

            // other engagement
            'other_engagement' => 'nullable|array',
            'other_engagement.society_engaged_or_to_engage' => 'nullable|string|max:255',
            'other_engagement.engagement_type' => 'nullable|string|max:255',
            'other_engagement.engagement_happened' => 'nullable|string|max:255',
            'other_engagement.notes' => 'nullable|string',

            // contacting info
            'contact_infos' => 'nullable|array',
            'contact_infos.contacting_info' => 'nullable|string|max:255',
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
                        'mentoring_status' => $mentoringPeriod['mentoring_status'],
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

            if (!empty($validatedData['project_years'])) {
                foreach ($validatedData['project_years'] as $projectYear) {
                    $masterRecord->projectYears()->create([
                        'academic_year' => $projectYear['academic_year'],
                        'project_client' => $projectYear['project_client'],
                    ]);
                }
            }

            if (!empty($validatedData['other_engagement'])) {
                $masterRecord->otherEngagement()->create($validatedData['other_engagement']);
            }

            if (!empty($validatedData['contact_infos'])) {
                $masterRecord->contactInfos()->create($validatedData['contact_infos']);
            }

            DB::commit();

            return response()->json(['message' => 'Record created successfully.','data' => $masterRecord], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to create record.'.$e], 500);
        }
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
            'uob_alumni' => ['nullable', 'string', 'regex:/^(yes|no)$/'],
            'programme_of_study_engaged' => 'nullable|string|max:255',

            // mentoring periods
            'mentoring_periods' => 'nullable|array',
            'mentoring_periods.*.academic_year' =>  ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'mentoring_periods.*.mentoring_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // industry years
            'industry_years' => 'nullable|array',
            'industry_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'industry_years.*.had_placement_status' => ['required', 'string', 'max:255','regex:/^(yes|no)$/'],

            // project years
            'project_years' => 'array',
            'project_years.*.academic_year' => ['required', 'string', 'max:255','regex:/^\d{4}-\d{4}$/'],
            'project_years.*.project_client' => 'required|string|max:255',

            // other engagement
            'other_engagement' => 'nullable|array',
            'other_engagement.society_engaged_or_to_engage' => 'nullable|string|max:255',
            'other_engagement.engagement_type' => 'nullable|string|max:255',
            'other_engagement.engagement_happened' => 'nullable|string|max:255',
            'other_engagement.notes' => 'nullable|string',

            // contacting info
            'contact_infos' => 'nullable|array',
            'contact_infos.contacting_info' => 'nullable|string|max:255',
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
                            'mentoring_status' => $mentoringPeriod['mentoring_status'],
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
            if (!empty($validatedData['project_years'])) {
                foreach ($validatedData['project_years'] as $projectYear) {
                    $existingRecord = $masterRecord->projectYears()
                        ->where('academic_year', $projectYear['academic_year'])
                        ->first();
                    if ($existingRecord) {
                        $existingRecord->update([
                            'project_client' => $projectYear['project_client'],
                        ]);
                    } else {
                        $masterRecord->projectYears()->create([
                            'academic_year' => $projectYear['academic_year'],
                            'project_client' => $projectYear['project_client'],
                        ]);
                    }
                }
            }
            if (!empty($validatedData['other_engagement'])) {
                $masterRecord->otherEngagement()->delete(); // delete old record
                $masterRecord->otherEngagement()->create($validatedData['other_engagement']);
            }

            if (!empty($validatedData['contact_infos'])) {
                $masterRecord->contactInfos()->delete();
                $masterRecord->contactInfos()->create($validatedData['contact_infos']);
            }

            DB::commit();

            $updatedRecord = MasterRecord::findOrFail($id)->load(['mentoringPeriods', 'industryYears', 'projectYears', 'otherEngagement']);

            return response()->json(['message' => 'Update success', 'data' => $updatedRecord]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request, $searchType)
    {
        $yearRange = [];
        if ($request->filled('year_range')) {
            $yearRange = $request->input('year_range');
        } else {
            return response()->json(['error' => 'No valid year range provided.'], 400);
        }
        $query = MasterRecord::query();
        if ($searchType == 'all') {
            if ($request->filled('search_term')) {
                $searchTerm = $request->input('search_term');

                $excludeColumns = ['id','master_record_id', 'created_at', 'updated_at'];

                // search master_records
                $recordColumns = Schema::getColumnListing((new MasterRecord)->getTable());
                $query->where(function ($q) use ($recordColumns, $searchTerm, $excludeColumns) {
                    foreach ($recordColumns as $column) {
                        if (!in_array($column, $excludeColumns)) {
                            $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                        }
                    }
                });

                $relationships = ['mentoringPeriods', 'industryYears', 'projectYears', 'otherEngagement', 'contactInfos'];

                foreach ($relationships as $relationship) {
                    $query->orWhereHas($relationship, function ($q) use ($searchTerm, $excludeColumns) {
                        $relatedModel = $q->getModel();
                        $relatedColumns = Schema::getColumnListing($relatedModel->getTable());

                        $q->where(function ($q) use ($relatedColumns, $searchTerm, $excludeColumns) {
                            foreach ($relatedColumns as $column) {
                                if (!in_array($column, $excludeColumns)) {
                                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                                }
                            }
                        });
                    });
                }
            } else {
                return response()->json(['message' => 'No search term entered'], 400);
            }
        } else if ($searchType == 'specific') {
            // Get the request data
            $requestData = $request->all();

            // Handle master_records fields
            $excludeColumns = ['id', 'master_record_id', 'created_at', 'updated_at'];
            foreach ($requestData as $key => $value) {
                if (in_array($key, $excludeColumns)) {
                    continue;
                }

                if ($key !== 'mentoring_periods' && $key !== 'industry_years' && $key !== 'year_range') {
                    $query->where($key, $value);
                }
            }

            // Handle mentoring_periods relationship
            if (isset($requestData['mentoring_periods'])) {
                $query->whereHas('mentoringPeriods', function ($q) use ($requestData) {
                    foreach ($requestData['mentoring_periods'] as $filter) {
                        foreach ($filter as $column => $value) {
                            $q->where($column, 'LIKE', "%{$value}%");
                        }
                    }
                });
            }

            // Handle industry_years relationship
            if (isset($requestData['industry_years'])) {
                $query->whereHas('industryYears', function ($q) use ($requestData) {
                    foreach ($requestData['industry_years'] as $filter) {
                        foreach ($filter as $column => $value) {
                            $q->where($column, $value);
                        }
                    }
                });
            }

            // Handle project_years relationship
            if (isset($requestData['project_years'])) {
                $query->whereHas('projectYears', function ($q) use ($requestData) {
                    foreach ($requestData['project_years'] as $filter) {
                        foreach ($filter as $column => $value) {
                            $q->where($column, $value);
                        }
                    }
                });
            }

            // Handle other_engagement relationship
            if (isset($requestData['other_engagement'])) {
                $query->whereHas('otherEngagement', function ($q) use ($requestData) {
                    foreach ($requestData['other_engagement'] as $column => $value) {
                        $q->where($column, $value);
                    }
                });
            }

            // Handle contact_infos relationship
            if (isset($requestData['contact_infos'])) {
                $query->whereHas('contactInfos', function ($q) use ($requestData) {
                    foreach ($requestData['contact_infos'] as $column => $value) {
                        $q->where($column, $value);
                    }
                });
            }
        } else {
            return response()->json(['error' => 'Invalid search type provided.'], 400);
        }
        $records = $this->getRecordsByYearRange($query, $yearRange);

        return response()->json(['data' => $records]);
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
            'projectYears' => function ($query) use ($yearRange) {
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
                        'mentoring_status' => $mentoringPeriod->mentoring_status,
                    ];
                }) : [],
                'industry_years' => $record->industryYears ? $record->industryYears->map(function ($industryYear) {
                    return [
                        'academic_year' => $industryYear->academic_year,
                        'had_placement_status' => $industryYear->had_placement_status,
                    ];
                }) : [],
                'project_years' => $record->projectYears ? $record->projectYears->map(function ($projectYear) {
                    return [
                        'academic_year' => $projectYear->academic_year,
                        'project_client' => $projectYear->project_client,
                    ];
                }) : [],
                'other_engagement' => $record->otherEngagement ? [
                    'society_engaged_or_to_engage' => $record->otherEngagement->society_engaged_or_to_engage ?? null,
                    'engagement_type' => $record->otherEngagement->engagement_type ?? null,
                    'engagement_happened' => $record->otherEngagement->engagement_happened ?? null,
                    'notes' => $record->otherEngagement->notes ?? null,
                ] : [],
            ];
        });
        return $formattedMasterRecords;
    }

    public function getRecordsByYearRange($queryOrModel, array $yearRange)
    {
        if (empty($yearRange)) {
            return ['error' => 'No valid years provided.'];
        }
        if ($queryOrModel instanceof MasterRecord) {
            $query = $queryOrModel::query();
        } elseif ($queryOrModel instanceof Builder) {
            $query = $queryOrModel;
        } else {
            return ['error' => 'Invalid parameter type.'];
        }

        $masterRecords = $query->with([
            'mentoringPeriods' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'industryYears' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'projectYears' => function ($query) use ($yearRange) {
                $query->whereIn('academic_year', $yearRange);
            },
            'otherEngagement',
            'contactInfos'
        ])->get();

        return $this->formatMasterRecords($masterRecords);
    }

    private function formatMasterRecords($masterRecords)
    {
        return $masterRecords->map(function ($record) {
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
                        'mentoring_status' => $mentoringPeriod->mentoring_status,
                    ];
                }) : [],
                'industry_years' => $record->industryYears ? $record->industryYears->map(function ($industryYear) {
                    return [
                        'academic_year' => $industryYear->academic_year,
                        'had_placement_status' => $industryYear->had_placement_status,
                    ];
                }) : [],
                'project_years' => $record->projectYears ? $record->projectYears->map(function ($projectYear) {
                    return [
                        'academic_year' => $projectYear->academic_year,
                        'project_client' => $projectYear->project_client,
                    ];
                }) : [],
                'other_engagement' => $record->otherEngagement ? [
                    'society_engaged_or_to_engage' => $record->otherEngagement->society_engaged_or_to_engage ?? null,
                    'engagement_type' => $record->otherEngagement->engagement_type ?? null,
                    'engagement_happened' => $record->otherEngagement->engagement_happened ?? null,
                    'notes' => $record->otherEngagement->notes ?? null,
                ] : [],
                'contact_infos' => $record->contactInfos ? [
                    'contacting_info' => $record->contactInfos->contacting_info ?? null,
                ] : [],
            ];
        });
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


        for ($year = $startYear; $year < $endYear; $year++) {
            $academicYearStart = Carbon::create($year, $startMonth, $startDay);
            $academicYearEnd = Carbon::create($year + 1, $endMonth, $endDay);
            $years[] = $academicYearStart->format('Y') . '-' . $academicYearEnd->format('Y');
        }

        return $years;
    }
}
