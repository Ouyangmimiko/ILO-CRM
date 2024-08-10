<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';

    protected $fillable = [
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
        'info_related_to_contacting_the_partner'
    ];

    // 使用自定义主键
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';


    // 监听模型的创建事件，生成自定义ID
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = self::generateCustomerId();
            }
        });
    }

    //生成唯一id
    public static function generateCustomerId(): string
    {
        $date = date('Ymd');
        $lastCustomer = DB::table('customer')
            ->where('id', 'like', $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCustomer) {
            $lastId = (int)substr($lastCustomer->id, 8);
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        return $date . str_pad($newId, 4, '0', STR_PAD_LEFT);
    }
}
