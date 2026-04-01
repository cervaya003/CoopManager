@extends('layouts.app')

@section('title', 'Todos los pagos')
@section('page-title', 'Gestión de pagos')

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">Historial global de pagos</span>
        <span class="badge badge-gray">{{ $pagos->total() }} registros</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Participante</th>
                    <th>Cooperación</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Notas</th>
                    <th>Registrado por</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                <tr>
                    <td class="text-xs text-muted">{{ $pago->id }}</td>
                    <td>
                        <div style="font-weight:500">{{ $pago->usuario->name }}</div>
                        <div class="text-xs text-muted">{{ $pago->usuario->email }}</div>
                    </td>
                    <td>
                        <a href="{{ route('cooperaciones.show', $pago->cooperacion_id) }}"
                           style="color:var(--accent);font-weight:500;text-decoration:none;font-size:.85rem">
                            {{ $pago->cooperacion->nombre }}
                        </a>
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
                    <td class="text-sm text-muted" style="max-width:140px">
                        <span class="truncate" style="display:block">{{ $pago->notas ?? '—' }}</span>
                    </td>
                    <td class="text-xs text-muted">{{ $pago->registradoPor?->name ?? '—' }}</td>
                    <td class="text-xs text-muted" style="white-space:nowrap">
                        {{ $pago->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div class="flex gap-2">
                            {{-- Cambiar estado --}}
                            <form method="POST" action="{{ route('pagos.updateEstado', $pago->id) }}">
                                @csrf @method('PATCH')
                                <select name="estado" onchange="this.form.submit()" class="form-control"
                                        style="padding:4px 8px;font-size:.75rem;width:auto">
                                    <option value="confirmado" {{ $pago->estado === 'confirmado' ? 'selected' : '' }}>✓ Conf.</option>
                                    <option value="pendiente"  {{ $pago->estado === 'pendiente'  ? 'selected' : '' }}>⏳ Pend.</option>
                                    <option value="rechazado"  {{ $pago->estado === 'rechazado'  ? 'selected' : '' }}>✕ Rech.</option>
                                </select>
                            </form>
                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('pagos.destroy', $pago->id) }}"
                                  onsubmit="return confirm('¿Eliminar este pago permanentemente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:40px;color:var(--ink-3)">
                        Sin pagos registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pagos->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $pagos->links() }}
    </div>
    @endif
</div>

@endsection
