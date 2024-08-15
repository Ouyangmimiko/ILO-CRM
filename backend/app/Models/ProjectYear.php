<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectYear extends Model
{
    use HasFactory;

    protected $table = 'project_years';

    protected $fillable = [
        'academic_year',
        'project_client'
    ];

    public function masterRecord(): BelongsTo
    {
        return $this->belongsTo(MasterRecord::class,'master_record_id');
    }
}
