<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    use AuthorizesRequests; 
    
    public function index(Request $request)
    {
        // Autorização manual (mantém a segurança)
        $this->authorize('viewAny', Activity::class);

        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        $logs = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['log_name', 'event', 'causer_id']),
        ]);
    }
}
