<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $query = ActivityLog::query()
            ->with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // filtros
        if ($request->filled('log_name')) {
            $query->where('log_name', 'like', '%' . $request->log_name . '%');
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', intval($request->causer_id));
        }

        // Paginação
        $logs = $query->paginate(15)->withQueryString();

        // Converter a propriedade properties para objeto JSON antes de enviar para o frontend
        $logs->getCollection()->transform(function ($log) {
            if (is_string($log->properties)) {
                $log->properties = json_decode($log->properties, true);
            }
            return $log;
        });

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['log_name', 'event', 'causer_id']),
        ]);
    }
}
