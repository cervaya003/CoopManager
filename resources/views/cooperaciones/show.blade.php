@extends('layouts.app')

@section('title', $cooperacion->nombre)
@section('page-title', $cooperacion->nombre)

@section('topbar-actions')
    <a href="{{ route('cooperaciones.index') }}" class="btn btn-outline btn-sm">← Volver</a>
    @if(Auth::user()->esAdmin())
    <a href="{{ route('cooperaciones.edit', $cooperacion->id) }}" class="btn btn-outline btn-sm">Editar</a>
    @endif
@endsection

@section('content')

{{-- ── Resumen superior ──────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

    {{-- Info principal --}}
    <div class="card">
        <div class="card-body">
            <div class="flex items-center gap-2 mb-4">
                @if($cooperacion->estado === 'activa')
                    <span class="badge badge-blue">Activa</span>
                @elseif($cooperacion->estado === 'completada')
                    <span class="badge badge-green">✓ Completada</span>
                @elseif($cooperacion->estado === 'vencida')
                    <span class="badge badge-red">Vencida</span>
                @else
                    <span class="badge badge-gray">{{ ucfirst($cooperacion->estado) }}</span>
                @endif
                <span class="text-xs text-muted">Creada por {{ $cooperacion->creador->name }}</span>
                <span class="text-xs text-muted">· Límite: {{ $cooperacion->fecha_limite->format('d M Y') }}</span>
            </div>

            @if($cooperacion->descripcion)
            <p class="text-sm" style="margin-bottom:16px;color:var(--ink-2)">{{ $cooperacion->descripcion }}</p>
            @endif

            {{-- Barra de progreso grande --}}
            <div style="margin-bottom:8px">
                <div class="flex justify-between" style="margin-bottom:6px">
                    <span style="font-weight:700;font-size:1.4rem;font-family:'DM Serif Display',serif">
                        ${{ number_format($cooperacion->totalRecaudado(), 2) }}
                    </span>
                    <span class="text-muted text-sm" style="align-self:flex-end">
                        de ${{ number_format($cooperacion->monto_objetivo, 2) }}
                    </span>
                </div>
                <div class="progress-bar" style="height:14px">
                    <div class="progress-fill {{ $cooperacion->porcentajeAvance() >= 100 ? '' : ($cooperacion->estaVencida() ? 'danger' : '') }}"
                         style="width:{{ $cooperacion->porcentajeAvance() }}%"></div>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-xs text-muted">{{ $cooperacion->porcentajeAvance() }}% recaudado</span>
                    <span class="text-xs text-muted">Falta: ${{ number_format($cooperacion->montoRestante(), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats compactos --}}
    <div style="display:flex;flex-direction:column;gap:12px">
        <div class="stat-card">
            <div class="stat-label">Total participantes</div>
            <div class="stat-value">{{ $cooperacion->totalParticipantes() }}</div>
        </div>
        <div class="stat-card accent">
            <div class="stat-label">Han pagado</div>
            <div class="stat-value">{{ $cooperacion->participantesPagados() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pendientes</div>
            <div class="stat-value" style="color:var(--warning)">{{ $cooperacion->participantesPendientes() }}</div>
        </div>
    </div>
</div>

{{-- ── Dos columnas: Participantes + Registrar pago ─────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">

    {{-- Participantes --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Participantes</span>
            <span class="badge badge-gray">{{ $cooperacion->totalParticipantes() }}</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Monto asignado</th>
                        <th>Pagado</th>
                        <th>Estado</th>
                        @if(Auth::user()->esAdmin())<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($cooperacion->participantes as $p)
                    <tr>
                        <td>
                            <div style="font-weight:500">{{ $p->usuario->name }}</div>
                            <div class="text-xs text-muted">{{ $p->usuario->email }}</div>
                        </td>
                        <td class="text-sm">${{ number_format($p->monto_asignado ?? $cooperacion->monto_por_persona, 2) }}</td>
                        <td class="text-sm font-bold" style="color:var(--accent)">
                            ${{ number_format($p->montoPagado(), 2) }}
                        </td>
                        <td>
                            @if($p->estado === 'pagado')
                                <span class="badge badge-green">✓ Pagado</span>
                            @elseif($p->estado === 'parcial')
                                <span class="badge badge-yellow">Parcial</span>
                            @else
                                <span class="badge badge-red">Pendiente</span>
                            @endif
                        </td>
                        @if(Auth::user()->esAdmin())
                        <td>
                            <form method="POST"
                                  action="{{ route('participantes.destroy', [$cooperacion->id, $p->id]) }}"
                                  onsubmit="return confirm('¿Eliminar a {{ $p->usuario->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted text-sm" style="padding:24px;text-align:center">
                            Sin participantes aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Agregar participante --}}
        @if(Auth::user()->esAdmin() && $usuariosDisponibles->count())
        <div style="padding:16px;border-top:1px solid var(--border);background:var(--surface);border-radius:0 0 12px 12px">
            <form method="POST" action="{{ route('participantes.store', $cooperacion->id) }}"
                  class="flex gap-2 items-center">
                @csrf
                <select name="user_id" class="form-control" required>
                    <option value="">Agregar participante…</option>
                    @foreach($usuariosDisponibles as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap">+ Agregar</button>
            </form>
        </div>
        @endif
    </div>

    {{-- Registrar pago --}}
    @if(Auth::user()->esAdmin())
    <div class="card">
        <div class="card-header">
            <span class="card-title">Registrar pago</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pagos.store', $cooperacion->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Participante *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Seleccionar…</option>
                        @foreach($cooperacion->participantes as $p)
                            <option value="{{ $p->usuario->id }}">
                                {{ $p->usuario->name }}
                                @if($p->estado === 'pagado') ✓@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Monto ($) *</label>
                        <input type="number" name="monto" class="form-control"
                               value="{{ $cooperacion->monto_por_persona }}"
                               min="0.01" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Método de pago *</label>
                        <select name="metodo_pago" class="form-control" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" class="form-control" rows="2"
                              placeholder="Observaciones opcionales…"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Comprobante (opcional)</label>
                    <input type="file" name="comprobante" class="form-control"
                           accept="image/*,.pdf">
                    <p class="form-hint">JPG, PNG o PDF. Máx 5MB.</p>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%">
                    Registrar pago
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

{{-- ── Historial de pagos ───────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Historial de pagos</span>
        <span class="badge badge-gray">{{ $cooperacion->pagos->count() }} registros</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Participante</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Notas</th>
                    <th>Registrado por</th>
                    <th>Fecha</th>
                    @if(Auth::user()->esAdmin())<th>Acciones</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($cooperacion->pagos->sortByDesc('created_at') as $pago)
                <tr>
                    <td class="text-muted text-xs">{{ $pago->id }}</td>
                    <td>
                        <div style="font-weight:500">{{ $pago->usuario->name }}</div>
                    </td>
                    <td class="font-bold" style="color:var(--accent)">${{ number_format($pago->monto, 2) }}</td>
                    <td class="text-sm">{{ ucfirst($pago->metodo_pago) }}</td>
                    <td>
                        @if($pago->estado === 'confirmado')
                            <span class="badge badge-green">Confirmado</span>
                        @elseif($pago->estado === 'rechazado')
                            <span class="badge badge-red">Rechazado</span>
                        @else
                            <span class="badge badge-yellow">Pendiente</span>
                        @endif
                    </td>
                    <td class="text-sm text-muted">{{ $pago->notas ?? '—' }}</td>
                    <td class="text-sm text-muted">{{ $pago->registradoPor?->name ?? '—' }}</td>
                    <td class="text-xs text-muted">{{ $pago->created_at->format('d/m/Y H:i') }}</td>

                    @if(Auth::user()->esAdmin())
                    <td>
                        <div class="flex gap-2">
                            {{-- Cambiar estado --}}
                            <form method="POST" action="{{ route('pagos.updateEstado', $pago->id) }}">
                                @csrf @method('PATCH')
                                <select name="estado" onchange="this.form.submit()" class="form-control"
                                        style="padding:4px 8px;font-size:.75rem">
                                    <option value="confirmado" {{ $pago->estado === 'confirmado' ? 'selected' : '' }}>✓ Conf.</option>
                                    <option value="pendiente"  {{ $pago->estado === 'pendiente'  ? 'selected' : '' }}>⏳ Pend.</option>
                                    <option value="rechazado"  {{ $pago->estado === 'rechazado'  ? 'selected' : '' }}>✕ Rech.</option>
                                </select>
                            </form>
                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('pagos.destroy', $pago->id) }}"
                                  onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-muted text-sm" style="padding:32px;text-align:center">
                        Sin pagos registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
