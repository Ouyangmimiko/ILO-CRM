<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class MasterRecord extends Model
{
    use HasFactory;

    protected $table = 'master_records';

    // Using UUID as primary key
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'organisation',
        'organisation_sector',
        'first_name',
        'surname',
        'job_title',
        'email',
        'location',
        'uob_alumni',
        'programme_of_study_engaged',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function mentoringPeriods(): HasMany
    {
        return $this->hasMany(MentoringPeriod::class);
    }

    public function industryYears(): HasMany
    {
        return $this->hasMany(IndustryYear::class);
    }

    public function projectYears(): HasMany
    {
        return $this->hasMany(ProjectYear::class);
    }

    public function otherEngagement(): HasOne
    {
        return $this->hasOne(OtherEngagement::class);
    }

    public function contactInfos () : HasOne
    {
        return $this->hasOne(ContactInfo::class);
    }

    public function getUobAlumniAttribute($value)
    {
        return $value;
    }

    public function setUobAlumniAttribute($value)
    {
        $this->attributes['uob_alumni'] = $value === null ? null : (strtolower($value) === 'yes' ? 'yes' : 'no');
    }
}
