<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlertController extends Controller
{
    /**
     * Display a listing of alerts.
     */
    public function index()
    {
        return AlertResource::collection(
            Alert::latest()->paginate(15)
        );
    }

    /**
     * Store a newly created alert.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['health', 'schedule', 'production', 'maintenance'])],
            'severity' => ['required', Rule::in(['low', 'medium', 'high'])],
            'message' => 'required|string',
            'animal_id' => 'nullable|exists:animals,id',
        ]);

        $alert = Alert::create([
            'type' => $validated['type'],
            'severity' => $validated['severity'],
            'message' => $validated['message'],
            'animal_id' => $validated['animal_id'] ?? null,
            'date' => now(),
            'resolved' => false,
        ]);

        return AlertResource::make($alert)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified alert.
     */
    public function show(Alert $alert)
    {
        return AlertResource::make($alert);
    }

    /**
     * Mark an alert as resolved.
     */
    public function resolve(Alert $alert)
    {
        $alert->update([
            'resolved' => true,
            'resolved_at' => now(),
        ]);

        return AlertResource::make($alert->fresh());
    }

    /**
     * Remove the specified alert.
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();

        return response()->json([
            'message' => 'Alerta eliminada exitosamente'
        ]);
    }
}
