<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YearInIndustry extends Model
{
    use HasFactory;

    protected $table = 'years_in_industry';
    protected $fillable = [
        'master_id',
        'academic_year',
        'had_placement_status',
    ];

public function masterRecords(): BelongsTo
{
    return $this->belongsTo(MasterRecord::class, 'master_id');
}
}
