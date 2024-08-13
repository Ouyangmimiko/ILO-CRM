<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringPeriod extends Model
{
    use HasFactory;

    protected $table = 'mentoring_periods';

    protected $fillable = [
        'academic_year',
        'mentoring_status',
    ];

    public function masterRecords(): BelongsTo
    {
        return $this->belongsTo(MasterRecord::class, 'master_id');
    }
}
