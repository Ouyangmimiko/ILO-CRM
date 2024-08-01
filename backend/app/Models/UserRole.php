<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRole extends Model
{
    use HasFactory;

    // Assign table
    protected $table = 'user_roles';

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
