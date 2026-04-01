@extends('layouts.app')

@section('title', 'Editar: ' . $cooperacion->nombre)
@section('page-title', 'Editar cooperación')

@section('topbar-actions')
    <a href="{{ route('cooperaciones.show', $cooperacion->id) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection

@section('content')

<div style="max-width:680px">
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ $cooperacion->nombre }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cooperaciones.update', $cooperacion->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $cooperacion->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ old('descripcion', $cooperacion->descripcion) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Monto objetivo ($) *</label>
                        <input type="number" name="monto_objetivo" class="form-control"
                               value="{{ old('monto_objetivo', $cooperacion->monto_objetivo) }}"
                               min="1" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monto por persona ($) *</label>
                        <input type="number" name="monto_por_persona" class="form-control"
                               value="{{ old('monto_por_persona', $cooperacion->monto_por_persona) }}"
                               min="1" step="0.01" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Fecha límite *</label>
                        <input type="date" name="fecha_limite" class="form-control"
                               value="{{ old('fecha_limite', $cooperacion->fecha_limite->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-control" required>
                            <option value="activa"      {{ $cooperacion->estado === 'activa'      ? 'selected' : '' }}>Activa</option>
                            <option value="completada"  {{ $cooperacion->estado === 'completada'  ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada"   {{ $cooperacion->estado === 'cancelada'   ? 'selected' : '' }}>Cancelada</option>
                            <option value="vencida"     {{ $cooperacion->estado === 'vencida'     ? 'selected' : '' }}>Vencida</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Imagen de portada</label>
                    @if($cooperacion->imagen)
                    <div style="margin-bottom:8px">
                        <img src="{{ Storage::url($cooperacion->imagen) }}" alt=""
                             style="width:100%;max-height:160px;object-fit:cover;border-radius:8px">
                    </div>
                    @endif
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <p class="form-hint">Dejar vacío para mantener la imagen actual.</p>
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:10px">
                    <input type="hidden" name="es_publica" value="0">
                    <input type="checkbox" name="es_publica" value="1" id="es_publica"
                           {{ old('es_publica', $cooperacion->es_publica) ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--accent)">
                    <label for="es_publica" class="form-label" style="margin:0;cursor:pointer">Cooperación pública</label>
                </div>

                <div class="flex gap-2" style="margin-top:8px">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('cooperaciones.show', $cooperacion->id) }}" class="btn btn-outline">Cancelar</a>
                </div>
            </form>

            {{-- Zona peligrosa --}}
            <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border)">
                <h4 style="color:var(--danger);margin-bottom:8px;font-size:.88rem">Zona peligrosa</h4>
                <form method="POST" action="{{ route('cooperaciones.destroy', $cooperacion->id) }}"
                      onsubmit="return confirm('¿Seguro que quieres eliminar esta cooperación? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar cooperación</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
