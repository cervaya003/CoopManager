@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    @if (Auth::user()->esAdmin())
        <a href="{{ route('cooperaciones.create') }}" class="btn btn-primary btn-sm">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nueva cooperación
        </a>
    @endif
@endsection

@section('content')

    {{-- ── Stats ─────────────────────────────────────────────────────────────── --}}
    @if (Auth::user()->esAdmin())
        <div class="stats-grid">
            <div class="stat-card accent">
                <div class="stat-label">Total recaudado</div>
                <div class="stat-value">${{ number_format($stats['total_recaudado'], 0) }}</div>
                <div class="stat-sub">En todas las cooperaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Cooperaciones activas</div>
                <div class="stat-value">{{ $stats['cooperaciones_activas'] }}</div>
                <div class="stat-sub">De {{ $stats['total_cooperaciones'] }} totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completadas</div>
                <div class="stat-value">{{ $stats['cooperaciones_completadas'] }}</div>
                <div class="stat-sub">Meta alcanzada</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Usuarios registrados</div>
                <div class="stat-value">{{ $stats['total_usuarios'] }}</div>
                <div class="stat-sub">{{ $stats['pagos_hoy'] }} pagos hoy</div>
            </div>
        </div>
    @else
        <div class="stats-grid">
            <div class="stat-card accent">
                <div class="stat-label">Total aportado</div>
                <div class="stat-value">${{ number_format($stats['total_aportado'], 0) }}</div>
                <div class="stat-sub">Tus contribuciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Mis cooperaciones</div>
                <div class="stat-value">{{ $stats['mis_cooperaciones'] }}</div>
                <div class="stat-sub">En las que participas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Al corriente</div>
                <div class="stat-value">{{ $stats['pagados'] }}</div>
                <div class="stat-sub">Completamente pagadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pendientes</div>
                <div class="stat-value" style="color:var(--warning)">{{ $stats['pendientes'] }}</div>
                <div class="stat-sub">Por pagar</div>
            </div>
        </div>
    @endif

    {{-- ── Dos columnas ─────────────────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

        {{-- Cooperaciones recientes --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Cooperaciones recientes</span>
                <a href="{{ route('cooperaciones.index') }}" class="btn btn-outline btn-sm">Ver todas</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Avance</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cooperacionesRecientes as $c)
                            <tr>
                                <td>
                                    <a href="{{ route('cooperaciones.show', $c->id) }}"
                                        style="color:var(--ink);font-weight:500;text-decoration:none">
                                        {{ $c->nombre }}
                                    </a>
                                    <div class="text-xs text-muted mt-1">Límite:
                                        {{ $c->fecha_limite->format('d/m/Y') }}</div>
                                </td>
                                <td style="min-width:120px">
                                    <div class="progress-bar mb-1">
                                        <div class="progress-fill" style="width:{{ $c->porcentajeAvance() }}%"></div>
                                    </div>
                                    <div class="text-xs text-muted">{{ $c->porcentajeAvance() }}%</div>
                                </td>
                                <td>
                                    @if ($c->estado === 'activa')
                                        <span class="badge badge-blue">Activa</span>
                                    @elseif($c->estado === 'completada')
                                        <span class="badge badge-green">Completada</span>
                                    @elseif($c->estado === 'vencida')
                                        <span class="badge badge-red">Vencida</span>
                                    @else
                                        <span class="badge badge-gray">{{ ucfirst($c->estado) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-sm" style="padding:24px;text-align:center">Sin
                                    cooperaciones aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagos recientes --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Pagos recientes</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Cooperación</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagosRecientes as $p)
                            <tr>
                                <td>
                                    <div style="font-weight:500">{{ $p->usuario->name }}</div>
                                    <div class="text-xs text-muted">{{ $p->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-sm truncate" style="max-width:120px">{{ $p->cooperacion->nombre }}</td>
                                <td class="text-right font-bold" style="color:var(--accent)">
                                    ${{ number_format($p->monto, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-sm" style="padding:24px;text-align:center">Sin
                                    pagos aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
