<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends SpatieActivity
{

    use HasFactory;

    protected $table = 'activity_log'; 

    protected $fillable = [
        'log_name', 'description', 'subject_type', 'subject_id',
        'causer_type', 'causer_id', 'properties', 'event', 'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array',
        'batch_uuid' => 'string',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    public function causer(): MorphTo
    {
        return $this->morphTo('causer');
    }
}
