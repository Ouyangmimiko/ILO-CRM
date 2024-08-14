<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherEngagement extends Model
{
    use HasFactory;

    protected $table = 'other_engagement';

    public function masterRecord(): BelongsTo
    {
        return $this->belongsTo(MasterRecord::class, 'master_record_id');
    }
}
