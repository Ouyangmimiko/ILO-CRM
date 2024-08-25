<?php

namespace App\Imports;

use App\Models\MasterRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

class RecordsImport implements ToModel, WithHeadingRow
{
    /**
     * @throws ValidationException
     */
    public function model(array $row): void
    {
        $validator = Validator::make($row, [
            'email_address' => 'required|email',
            'organisation' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $masterRecord = MasterRecord::updateOrCreate(
            ['email' => $row['email_address']],
            [
                'organisation' => $row['organisation'],
                'organisation_sector' => $row['organisation_sector'],
                'first_name' => $row['first_name'],
                'surname' => $row['surname'],
                'job_title' => $row['job_title'],
                'location' => $row['location'],
                'uob_alumni' => $row['uob_alumni'],
                'programme_of_study_engaged' => $row['programme_of_study_engaged'],
            ]
        );

        foreach ($row as $column => $value) {
            Log::info('Processing Column:', ['column' => $column, 'value' => $value]);
            if (str_contains($column, 'mentoring') && !empty($value)) {
                $this->handleMentoringPeriod($masterRecord, $column, $value);
            } elseif (str_contains($column, 'industry') && !empty($value)) {
                $this->handleIndustryYear($masterRecord, $column, $value);
            } elseif (str_contains($column, 'project') && !empty($value)) {
                $this->handleProjectYear($masterRecord, $column, $value);
            }
        }

        $this->handleOtherEngagement($masterRecord, $row);
        $this->handleContactInfo($masterRecord, $row);
    }

    protected function handleMentoringPeriod($masterRecord, $column, $value): void
    {
        $academicYear = $this->extractAcademicYear($column);
        $masterRecord->mentoringPeriods()->updateOrCreate(
            ['academic_year' => $academicYear],
            ['mentoring_status' => $value]
        );
    }

    protected function handleIndustryYear($masterRecord, $column, $value): void
    {
        $academicYear = $this->extractAcademicYear($column);
        $masterRecord->industryYears()->updateOrCreate(
            ['academic_year' => $academicYear],
            ['had_placement_status' => $value]
        );
    }

    protected function handleProjectYear($masterRecord, $column, $value): void
    {
        $academicYear = $this->extractAcademicYear($column);
        $masterRecord->projectYears()->updateOrCreate(
            ['academic_year' => $academicYear],
            ['project_client' => $value]
        );
    }

    protected function handleOtherEngagement($masterRecord, $row): void
    {
        $otherEngagementData = [
            'society_engaged_or_to_engage' => $row['engaged_in_society'] ?? null,
            'engagement_type' => $row['engagement_type'] ?? null,
            'engagement_happened' => $row['engagement_happened'] ?? null,
            'notes' => $row['notes'] ?? null,
        ];

        if (!empty(array_filter($otherEngagementData))) {
            $masterRecord->otherEngagement()->updateOrCreate([], $otherEngagementData);
        }
    }

    protected function handleContactInfo($masterRecord, $row): void
    {
        $contactInfo = [
            'contacting_info' => $row['info_related_to_contacting_the_partner'] ?? null,
        ];

        if (!empty(array_filter($contactInfo))) {
            $masterRecord->contactInfos()->updateOrCreate([], $contactInfo);
        }
    }

    protected function extractAcademicYear($column): ?string
    {
        $column = str_replace('_', '-', $column);
        preg_match('/\d{4}-\d{4}/', $column, $matches);
        return $matches[0] ?? null;
    }
}
