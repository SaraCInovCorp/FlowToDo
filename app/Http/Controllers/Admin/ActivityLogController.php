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

        // Filtro por nome do log (busca parcial)
        if ($request->filled('log_name')) {
            $query->where('log_name', 'like', '%' . $request->log_name . '%');
        }

        // Filtro por evento (igualdade exata)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filtro por ID do usuário (numérico)
        if ($request->filled('causer_id')) {
            $query->where('causer_id', intval($request->causer_id));
        }

        $logs = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['log_name', 'event', 'causer_id']),
        ]);
    }

}
