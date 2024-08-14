<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherEngagement extends Model
{
    use HasFactory;

//    protected $fillable = [
//        'society_engaged_or_to_engage',
//        'engagement_type',
//        'engagement_happened',
//        'notes'
//    ];

    protected $table = 'other_engagement';

    public function masterRecord(): BelongsTo
    {
        return $this->belongsTo(MasterRecord::class, 'master_record_id');
    }
}
