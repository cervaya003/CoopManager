<?php

namespace App\Http\Controllers;

use App\Models\Cooperacion;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->esAdmin()) {

            $stats = [
                'total_cooperaciones'      => Cooperacion::count(),
                'cooperaciones_activas'    => Cooperacion::where('estado', 'activa')->count(),
                'cooperaciones_completadas'=> Cooperacion::where('estado', 'completada')->count(),
                'total_recaudado'          => Pago::where('estado', 'confirmado')->sum('monto'),
                'total_usuarios'           => User::count(),
                'pagos_hoy'                => Pago::whereDate('created_at', today())->count(),
            ];

            $cooperacionesRecientes = Cooperacion::with(['creador', 'pagos', 'participantes'])
                ->latest()
                ->take(5)
                ->get();

            $pagosRecientes = Pago::with(['usuario', 'cooperacion'])
                ->where('estado', 'confirmado')
                ->latest()
                ->take(10)
                ->get();

        } else {

            // Cargar participaciones con su cooperación y los pagos de esa cooperación
            $misParticipaciones = $user->participaciones()
                ->with(['cooperacion' => function ($q) {
                    $q->with('pagos');
                }])
                ->get();

            $stats = [
                'mis_cooperaciones' => $misParticipaciones->count(),
                'pendientes'        => $misParticipaciones->where('estado', 'pendiente')->count(),
                'pagados'           => $misParticipaciones->where('estado', 'pagado')->count(),
                'total_aportado'    => $user->totalAportado(),
            ];

            // Extraer las cooperaciones de las participaciones, filtrando nulos
            $cooperacionesRecientes = $misParticipaciones
                ->take(5)
                ->map(fn($p) => $p->cooperacion)
                ->filter()          // elimina cualquier null
                ->values();

            $pagosRecientes = $user->pagos()
                ->with('cooperacion')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact('stats', 'cooperacionesRecientes', 'pagosRecientes'));
    }
}
