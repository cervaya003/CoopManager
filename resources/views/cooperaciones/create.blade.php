@extends('layouts.app')

@section('title', 'Nueva cooperación')
@section('page-title', 'Nueva cooperación')

@section('topbar-actions')
    <a href="{{ route('cooperaciones.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection

@section('content')

<div style="max-width:680px">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Información de la cooperación</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cooperaciones.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Validación general --}}
                @if($errors->any())
                <div class="alert alert-error mb-4">
                    <ul style="margin:0;padding-left:16px">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Nombre de la cooperación *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre') }}" placeholder="Ej. Cooperación navideña 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="¿Para qué es esta cooperación?">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Monto objetivo ($) *</label>
                        <input type="number" name="monto_objetivo" class="form-control"
                               value="{{ old('monto_objetivo') }}" min="1" step="0.01" required>
                        <p class="form-hint">Total que se quiere recaudar</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monto por persona ($) *</label>
                        <input type="number" name="monto_por_persona" class="form-control"
                               value="{{ old('monto_por_persona') }}" min="1" step="0.01" required>
                        <p class="form-hint">Cuánto debe aportar cada participante</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha límite *</label>
                    <input type="date" name="fecha_limite" class="form-control"
                           value="{{ old('fecha_limite') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Imagen de portada</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <p class="form-hint">JPG, PNG o WEBP. Máx 2MB.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Participantes iniciales</label>
                    <select name="participantes[]" class="form-control" multiple
                            style="min-height:130px">
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}"
                                {{ in_array($u->id, old('participantes', [])) ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="form-hint">Mantén Ctrl/Cmd para seleccionar varios. Puedes agregar más después.</p>
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:10px">
                    <input type="hidden" name="es_publica" value="0">
                    <input type="checkbox" name="es_publica" value="1" id="es_publica"
                           {{ old('es_publica', 1) ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--accent)">
                    <label for="es_publica" class="form-label" style="margin:0;cursor:pointer">
                        Cooperación pública (visible para todos)
                    </label>
                </div>

                <div class="flex gap-2" style="margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear cooperación
                    </button>
                    <a href="{{ route('cooperaciones.index') }}" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
