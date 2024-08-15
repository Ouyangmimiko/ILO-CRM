<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function mentoringPeriods(): HasMany
    {
        return $this->hasMany(MentoringPeriod::class);
    }

    public function industryYears(): HasMany
    {
        return $this->hasMany(IndustryYear::class);
    }

    public function projects(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function otherEngagement(): HasOne
    {
        return $this->hasOne(OtherEngagement::class);
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
