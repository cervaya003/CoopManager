<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Cooperacion;
use App\Models\Participante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    /**
     * Mostrar todos los pagos (admin).
     */
    public function index()
    {
        $pagos = Pago::with(['usuario', 'cooperacion', 'registradoPor'])
            ->latest()
            ->paginate(20);

        return view('pagos.index', compact('pagos'));
    }

    /**
     * Registrar un nuevo pago en una cooperación.
     */
    public function store(Request $request, $cooperacionId)
    {
        $cooperacion = Cooperacion::findOrFail($cooperacionId);

        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'monto'       => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,transferencia,otro',
            'notas'       => 'nullable|string|max:500',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $comprobante = null;
        if ($request->hasFile('comprobante')) {
            $comprobante = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago = Pago::create([
            'cooperacion_id'  => $cooperacionId,
            'user_id'         => $validated['user_id'],
            'monto'           => $validated['monto'],
            'metodo_pago'     => $validated['metodo_pago'],
            'estado'          => 'confirmado',
            'notas'           => $validated['notas'] ?? null,
            'comprobante'     => $comprobante,
            'registrado_por'  => Auth::id(),
        ]);

        // Recalcular estado del participante
        $participante = Participante::where('cooperacion_id', $cooperacionId)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($participante) {
            $participante->recalcularEstado();
        }

        // Verificar si la cooperación se completó
        $cooperacion->actualizarEstado();

        return back()->with('success', "Pago de $" . number_format($pago->monto, 2) . " registrado correctamente.");
    }

    /**
     * Cambiar estado de un pago (confirmar / rechazar).
     */
    public function updateEstado(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:confirmado,rechazado,pendiente',
        ]);

        $pago->update(['estado' => $validated['estado']]);

        // Recalcular estado del participante
        $participante = Participante::where('cooperacion_id', $pago->cooperacion_id)
            ->where('user_id', $pago->user_id)
            ->first();

        if ($participante) {
            $participante->recalcularEstado();
        }

        $pago->cooperacion->actualizarEstado();

        return back()->with('success', 'Estado del pago actualizado.');
    }

    /**
     * Eliminar un pago.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->comprobante) {
            Storage::disk('public')->delete($pago->comprobante);
        }

        $cooperacionId = $pago->cooperacion_id;
        $userId        = $pago->user_id;

        $pago->delete();

        // Recalcular participante
        $participante = Participante::where('cooperacion_id', $cooperacionId)
            ->where('user_id', $userId)
            ->first();

        if ($participante) {
            $participante->recalcularEstado();
        }

        return back()->with('success', 'Pago eliminado.');
    }
}
