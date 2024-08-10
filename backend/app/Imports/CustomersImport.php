<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Customer::updateOrCreate(
                ['email' => $row['email']],
                [
                    'organisation' => $row['organisation'],
                    'organisation_sector' => $row['organisation_sector'],
                    'first_name' => $row['first_name'],
                    'surname' => $row['surname'],
                    'job_title' => $row['job_title'],
                    'email' => $row['email'],
                    'location' => $row['location'],
                    'uob_alumni' => $row['uob_alumni'],
                    'programme_of_study_engaged' => $row['programme_of_study_engaged'],
                    'mentoring_2021_22' => $row['mentoring_2021_22'],
                    'mentoring_2022_23' => $row['mentoring_2022_23'],
                    'mentoring_2023_24' => $row['mentoring_2023_24'],
                    'yii_2021_22' => $row['yii_2021_22'],
                    'yii_2022_23' => $row['yii_2022_23'],
                    'yii_2023_24' => $row['yii_2023_24'],
                    'projects_2021_22' => $row['projects_2021_22'],
                    'projects_2022_23' => $row['projects_2022_23'],
                    'projects_2023_24' => $row['projects_2023_24'],
                    'info_related_to_contacting_the_partner' => $row['info_related_to_contacting_the_partner'],
                ]
            );
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.email' => [
                'required',
                'email',
                Rule::unique('customer', 'email')
            ],
        ];
    }
}
