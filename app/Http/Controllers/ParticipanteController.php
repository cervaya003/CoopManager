<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cooperacion;
use App\Models\Participante;

class ParticipanteController extends Controller
{
    /**
     * Agregar un participante a una cooperación.
     */
    public function store(Request $request, $cooperacionId)
    {
        $cooperacion = Cooperacion::findOrFail($cooperacionId);

        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'monto_asignado' => 'nullable|numeric|min:0',
        ]);

        // Verificar que no exista ya
        $existe = Participante::where('cooperacion_id', $cooperacionId)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Este usuario ya es participante.');
        }

        Participante::create([
            'cooperacion_id' => $cooperacionId,
            'user_id'        => $validated['user_id'],
            'monto_asignado' => $validated['monto_asignado'] ?? $cooperacion->monto_por_persona,
            'estado'         => 'pendiente',
        ]);

        return back()->with('success', 'Participante agregado correctamente.');
    }

    /**
     * Eliminar un participante de la cooperación.
     */
    public function destroy($cooperacionId, $participanteId)
    {
        $participante = Participante::where('cooperacion_id', $cooperacionId)
            ->findOrFail($participanteId);

        $participante->delete();

        return back()->with('success', 'Participante eliminado.');
    }
}
