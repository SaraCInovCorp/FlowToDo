<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLogger
{
    public static function log($event, $model = null, $message = null, $extra = [])
    {
        $activity = activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->event($event)
            ->withProperties(array_merge([
                'ip' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
            ], $extra))
            ->log($message ?? ucfirst($event));

        return $activity;
    }
}
