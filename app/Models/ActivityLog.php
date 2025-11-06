<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class ActivityLog extends SpatieActivity
{
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event',
        'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array',
        'batch_uuid' => 'string',
    ];

    /**
     * Relação polimórfica para o modelo afetado pela atividade.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /**
     * Relação polimórfica para o modelo que causou a atividade.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo('causer');
    }
}
