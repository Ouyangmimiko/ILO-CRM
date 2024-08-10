<?php

namespace App\Imports;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class CustomersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::all([
            'organisation',
            'organisation_sector',
            'first_name',
            'surname',
            'job_title',
            'email',
            'location',
            'uob_alumni',
            'programme_of_study_engaged',
            'mentoring_2021_22',
            'mentoring_2022_23',
            'mentoring_2023_24',
            'yii_2021_22',
            'yii_2022_23',
            'yii_2023_24',
            'projects_2021_22',
            'projects_2022_23',
            'projects_2023_24',
            'info_related_to_contacting_the_partner',
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Organisation',
            'Organisation Sector',
            'First Name',
            'Surname',
            'Job Title',
            'Email',
            'Location',
            'UOB Alumni',
            'Programme of Study Engaged',
            'Mentoring 2021/22',
            'Mentoring 2022/23',
            'Mentoring 2023/24',
            'YII 2021/22',
            'YII 2022/23',
            'YII 2023/24',
            'Projects 2021/22',
            'Projects 2022/23',
            'Projects 2023/24',
            'Info Related to Contacting the Partner',
            'Created At',
            'Updated At'
        ];
    }
}
