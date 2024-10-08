<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustryYear extends Model
{
    use HasFactory;

    protected $table = 'industry_years';

    protected $fillable = [
        'academic_year',
        'had_placement_status',
    ];

public function masterRecord(): BelongsTo
{
    return $this->belongsTo(MasterRecord::class, 'master_record_id');
}
}
