@extends('layouts.app')

@section('title', 'Cooperaciones')
@section('page-title', 'Cooperaciones')

@section('topbar-actions')
    @if(Auth::user()->esAdmin())
    <a href="{{ route('cooperaciones.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva cooperación
    </a>
    @endif
@endsection

@section('content')

{{-- ── Cuadrícula de cooperaciones ──────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;margin-bottom:24px">

    @forelse($cooperaciones as $c)
    <div class="card" style="display:flex;flex-direction:column">

        {{-- Imagen / cabecera de color --}}
        @if($c->imagen)
            <div style="height:140px;overflow:hidden;border-radius:12px 12px 0 0">
                <img src="{{ Storage::url($c->imagen) }}" alt="{{ $c->nombre }}"
                     style="width:100%;height:100%;object-fit:cover">
            </div>
        @else
            <div style="height:10px;background:var(--accent);border-radius:12px 12px 0 0"></div>
        @endif

        <div class="card-body" style="flex:1;display:flex;flex-direction:column;gap:10px">
            {{-- Estado --}}
            <div class="flex items-center gap-2">
                @if($c->estado === 'activa')
                    <span class="badge badge-blue">Activa</span>
                @elseif($c->estado === 'completada')
                    <span class="badge badge-green">✓ Completada</span>
                @elseif($c->estado === 'vencida')
                    <span class="badge badge-red">Vencida</span>
                @else
                    <span class="badge badge-gray">{{ ucfirst($c->estado) }}</span>
                @endif
                <span class="text-xs text-muted">{{ $c->totalParticipantes() }} participantes</span>
            </div>

            {{-- Nombre --}}
            <div>
                <h3 style="font-weight:700;font-size:1rem;margin-bottom:2px">{{ $c->nombre }}</h3>
                @if($c->descripcion)
                <p class="text-sm text-muted" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    {{ $c->descripcion }}
                </p>
                @endif
            </div>

            {{-- Progreso --}}
            <div>
                <div class="flex justify-between text-xs text-muted mb-1">
                    <span>${{ number_format($c->totalRecaudado(), 0) }} recaudados</span>
                    <span>Meta: ${{ number_format($c->monto_objetivo, 0) }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $c->porcentajeAvance() }}%"></div>
                </div>
                <div class="text-xs text-muted mt-1">{{ $c->porcentajeAvance() }}% completado</div>
            </div>

            {{-- Pie --}}
            <div class="flex items-center justify-between mt-1" style="margin-top:auto;padding-top:10px;border-top:1px solid var(--border)">
                <span class="text-xs text-muted">Límite: {{ $c->fecha_limite->format('d M Y') }}</span>
                <a href="{{ route('cooperaciones.show', $c->id) }}" class="btn btn-outline btn-sm">Ver detalle →</a>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px 20px">
        <div style="font-size:3rem;margin-bottom:12px">🤝</div>
        <h3 style="font-weight:600;margin-bottom:6px">No hay cooperaciones aún</h3>
        <p class="text-muted text-sm">Crea la primera cooperación para comenzar.</p>
        @if(Auth::user()->esAdmin())
        <a href="{{ route('cooperaciones.create') }}" class="btn btn-primary" style="margin-top:16px">
            Crear cooperación
        </a>
        @endif
    </div>
    @endforelse

</div>

{{-- Paginación --}}
<div>{{ $cooperaciones->links() }}</div>

@endsection
