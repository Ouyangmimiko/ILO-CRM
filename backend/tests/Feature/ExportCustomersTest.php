<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use App\Imports\CustomersExport;

class ExportCustomersTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_export_customers_to_excel()
    {
        // 模拟前端发送的数据
        $contacts = [
            [
                'organisation' => 'Tech Corp',
                'organisation_sector' => 'IT',
                'first_name' => 'John',
                'surname' => 'Doe',
                'job_title' => 'Developer',
                'email' => 'john.doe@example.com',
                'location' => 'San Francisco',
                'uob_alumni' => 'yes',
                'programme_of_study_engaged' => 'Computer Science',
                'mentoring_2021_22' => 'yes',
                'mentoring_2022_23' => 'no',
                'mentoring_2023_24' => 'yes',
                'yii_2021_22' => 'no',
                'yii_2023_24' => 'no',
                'projects_2021_22' => 'yes',
                'projects_2022_23' => 'no',
                'projects_2023_24' => 'yes',
                'info_related_to_contacting_the_partner' => 'Available for new projects'
            ],
            [
                'organisation' => 'Tech Corp',
                'organisation_sector' => 'IT',
                'first_name' => 'Jane',
                'surname' => 'Smith',
                'job_title' => 'Developer',
                'email' => 'jane.smith@example.com',
                'location' => 'San Francisco',
                'uob_alumni' => 'yes',
                'programme_of_study_engaged' => 'Computer Science',
                'mentoring_2021_22' => 'yes',
                'mentoring_2022_23' => 'no',
                'mentoring_2023_24' => 'yes',
                'yii_2021_22' => 'no',
                'yii_2023_24' => 'no',
                'projects_2021_22' => 'yes',
                'projects_2022_23' => 'no',
                'projects_2023_24' => 'yes',
                'info_related_to_contacting_the_partner' => 'Available for new projects'
            ]
        ];

        // 开始假设 Excel 操作
        Excel::fake();

        // 模拟一个 POST 请求到导出路由
        $response = $this->postJson('/export', ['contacts' => $contacts]);

        // 断言请求成功
        $response->assertStatus(200);

        // 断言文件被导出
        Excel::assertDownloaded('customers.xlsx', function (CustomersExport $export) use ($contacts) {
            // 验证导出的内容是否匹配
            // 转换导出的集合为数组
            $exportedData = $export->collection()->toArray();

            // 需要确保导出的数据和模拟数据的格式和内容一致
            return $exportedData === $contacts;
        });
    }
}
