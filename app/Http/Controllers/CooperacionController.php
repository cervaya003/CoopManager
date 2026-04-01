<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cooperacion;
use App\Models\Participante;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CooperacionController extends Controller
{
    //  Listado

    public function index()
    {
        $cooperaciones = Cooperacion::with(['creador', 'participantes', 'pagos'])
            ->latest()
            ->paginate(12);

        return view('cooperaciones.index', compact('cooperaciones'));
    }

    //  Formulario creación

    public function create()
    {
        $usuarios = User::orderBy('name')->get();
        return view('cooperaciones.create', compact('usuarios'));
    }

    //  Guardar nueva cooperación

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:150',
                'regex:/^[\pL\pN\s\-\.\,\(\)]+$/u',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'min:10',
                'max:1000',
            ],
            'monto_objetivo' => [
                'required',
                'numeric',
                'min:1',
                'max:9999999.99',
            ],
            'monto_por_persona' => [
                'required',
                'numeric',
                'min:1',
                'max:9999999.99',
                'lte:monto_objetivo',
            ],
            'fecha_limite' => [
                'required',
                'date',
                'after:today',
                'before:' . now()->addYears(5)->toDateString(),
            ],
            'es_publica' => [
                'nullable',
                'boolean',
            ],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],
            'participantes'   => ['nullable', 'array', 'max:500'],
            'participantes.*' => ['integer', 'exists:users,id'],
        ], [
            'nombre.required'            => 'El nombre de la cooperación es obligatorio.',
            'nombre.min'                 => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max'                 => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex'               => 'El nombre contiene caracteres no permitidos.',
            'descripcion.min'            => 'La descripción debe tener al menos 10 caracteres.',
            'descripcion.max'            => 'La descripción no puede superar los 1000 caracteres.',
            'monto_objetivo.required'    => 'El monto objetivo es obligatorio.',
            'monto_objetivo.numeric'     => 'El monto objetivo debe ser un número.',
            'monto_objetivo.min'         => 'El monto objetivo debe ser mayor a $0.',
            'monto_objetivo.max'         => 'El monto objetivo no puede superar $9,999,999.99.',
            'monto_por_persona.required' => 'El monto por persona es obligatorio.',
            'monto_por_persona.numeric'  => 'El monto por persona debe ser un número.',
            'monto_por_persona.min'      => 'El monto por persona debe ser mayor a $0.',
            'monto_por_persona.lte'      => 'El monto por persona no puede ser mayor al monto objetivo.',
            'fecha_limite.required'      => 'La fecha límite es obligatoria.',
            'fecha_limite.date'          => 'La fecha límite no tiene un formato válido.',
            'fecha_limite.after'         => 'La fecha límite debe ser una fecha futura.',
            'fecha_limite.before'        => 'La fecha límite no puede superar 5 años desde hoy.',
            'imagen.image'               => 'El archivo debe ser una imagen.',
            'imagen.mimes'               => 'La imagen debe ser JPG, PNG o WEBP.',
            'imagen.max'                 => 'La imagen no puede pesar más de 2 MB.',
            'imagen.dimensions'          => 'La imagen debe medir entre 100×100 y 4000×4000 píxeles.',
            'participantes.max'          => 'No puedes agregar más de 500 participantes a la vez.',
            'participantes.*.exists'     => 'Uno o más usuarios seleccionados no existen.',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('cooperaciones', 'public');
        }

        $cooperacion = Cooperacion::create([
            'nombre'            => trim($request->nombre),
            'descripcion'       => $request->descripcion ? trim($request->descripcion) : null,
            'monto_objetivo'    => $request->monto_objetivo,
            'monto_por_persona' => $request->monto_por_persona,
            'fecha_limite'      => $request->fecha_limite,
            'es_publica'        => $request->boolean('es_publica', true),
            'imagen'            => $imagenPath,
            'created_by'        => Auth::id(),
        ]);

        foreach ($request->input('participantes', []) as $userId) {
            Participante::create([
                'cooperacion_id' => $cooperacion->id,
                'user_id'        => $userId,
                'monto_asignado' => $cooperacion->monto_por_persona,
                'estado'         => 'pendiente',
            ]);
        }

        return redirect()->route('cooperaciones.show', $cooperacion)
            ->with('success', 'Cooperación creada exitosamente.');
    }

    //  Detalle

    public function show($id)
    {
        $cooperacion = Cooperacion::with([
            'creador',
            'participantes.usuario',
            'pagos.usuario',
            'pagos.registradoPor',
        ])->findOrFail($id);

        $usuariosDisponibles = User::whereNotIn(
            'id',
            $cooperacion->participantes->pluck('user_id')
        )->orderBy('name')->get();

        return view('cooperaciones.show', compact('cooperacion', 'usuariosDisponibles'));
    }

    //  Formulario edición

    public function edit($id)
    {
        $cooperacion = Cooperacion::findOrFail($id);

        if (Auth::id() !== $cooperacion->created_by && ! Auth::user()->esAdmin()) {
            abort(403, 'No tienes permiso para editar esta cooperación.');
        }

        return view('cooperaciones.edit', compact('cooperacion'));
    }

    //  Actualizar

    public function update(Request $request, $id)
    {
        $cooperacion = Cooperacion::findOrFail($id);

        if (Auth::id() !== $cooperacion->created_by && ! Auth::user()->esAdmin()) {
            abort(403, 'No tienes permiso para editar esta cooperación.');
        }

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:150',
                'regex:/^[\pL\pN\s\-\.\,\(\)]+$/u',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'min:10',
                'max:1000',
            ],
            'monto_objetivo' => [
                'required',
                'numeric',
                'min:1',
                'max:9999999.99',
            ],
            'monto_por_persona' => [
                'required',
                'numeric',
                'min:1',
                'max:9999999.99',
                'lte:monto_objetivo',
            ],
            'fecha_limite' => [
                'required',
                'date',
                'before:' . now()->addYears(5)->toDateString(),
            ],
            'estado' => [
                'required',
                'string',
                'in:activa,completada,cancelada,vencida',
            ],
            'es_publica' => [
                'nullable',
                'boolean',
            ],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],
        ], [
            'nombre.required'            => 'El nombre de la cooperación es obligatorio.',
            'nombre.min'                 => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max'                 => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex'               => 'El nombre contiene caracteres no permitidos.',
            'descripcion.min'            => 'La descripción debe tener al menos 10 caracteres.',
            'descripcion.max'            => 'La descripción no puede superar los 1000 caracteres.',
            'monto_objetivo.required'    => 'El monto objetivo es obligatorio.',
            'monto_objetivo.min'         => 'El monto objetivo debe ser mayor a $0.',
            'monto_objetivo.max'         => 'El monto objetivo no puede superar $9,999,999.99.',
            'monto_por_persona.required' => 'El monto por persona es obligatorio.',
            'monto_por_persona.min'      => 'El monto por persona debe ser mayor a $0.',
            'monto_por_persona.lte'      => 'El monto por persona no puede ser mayor al monto objetivo.',
            'fecha_limite.required'      => 'La fecha límite es obligatoria.',
            'fecha_limite.date'          => 'La fecha límite no tiene un formato válido.',
            'fecha_limite.before'        => 'La fecha límite no puede superar 5 años desde hoy.',
            'estado.required'            => 'El estado es obligatorio.',
            'estado.in'                  => 'El estado seleccionado no es válido.',
            'imagen.image'               => 'El archivo debe ser una imagen.',
            'imagen.mimes'               => 'La imagen debe ser JPG, PNG o WEBP.',
            'imagen.max'                 => 'La imagen no puede pesar más de 2 MB.',
            'imagen.dimensions'          => 'La imagen debe medir entre 100×100 y 4000×4000 píxeles.',
        ]);

        if ($request->hasFile('imagen')) {
            if ($cooperacion->imagen) {
                Storage::disk('public')->delete($cooperacion->imagen);
            }
            $cooperacion->imagen = $request->file('imagen')->store('cooperaciones', 'public');
        }

        $cooperacion->nombre            = trim($request->nombre);
        $cooperacion->descripcion       = $request->descripcion ? trim($request->descripcion) : null;
        $cooperacion->monto_objetivo    = $request->monto_objetivo;
        $cooperacion->monto_por_persona = $request->monto_por_persona;
        $cooperacion->fecha_limite      = $request->fecha_limite;
        $cooperacion->estado            = $request->estado;
        $cooperacion->es_publica        = $request->boolean('es_publica', true);
        $cooperacion->save();

        return redirect()->route('cooperaciones.show', $cooperacion)
            ->with('success', 'Cooperación actualizada.');
    }

    //  Eliminar

    public function destroy($id)
    {
        $cooperacion = Cooperacion::findOrFail($id);

        if (Auth::id() !== $cooperacion->created_by && ! Auth::user()->esAdmin()) {
            abort(403, 'No tienes permiso para eliminar esta cooperación.');
        }

        if ($cooperacion->imagen) {
            Storage::disk('public')->delete($cooperacion->imagen);
        }

        $cooperacion->delete();

        return redirect()->route('cooperaciones.index')
            ->with('success', 'Cooperación eliminada.');
    }
}
