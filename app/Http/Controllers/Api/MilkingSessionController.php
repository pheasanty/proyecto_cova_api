<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MilkingSession;
use App\Http\Requests\StoreMilkingSessionRequest;
use App\Http\Resources\MilkingSessionResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class MilkingSessionController extends Controller
{
// app/Http/Controllers/Api/MilkingSessionController.php
public function index(Request $request)
{
    $query = MilkingSession::with('animal')->latest();

    // ?animal_id=UUID   →  filtra solo esas sesiones
    if ($request->filled('animal_id')) {
        $query->where('animal_id', $request->animal_id);
    }

    // ?per_page=20      →  cambia tamaño de página
    $sessions = $query->paginate($request->integer('per_page', 15));

    return MilkingSessionResource::collection($sessions);
}


    /** POST /api/milking-sessions */


public function store(StoreMilkingSessionRequest $request)
{
    $session = DB::transaction(function () use ($request) {

        /** @var \App\Models\MilkingSession $session */
        $session = MilkingSession::create($request->validated());

        /** @var \App\Models\Animal $animal */
        $animal = $session->animal;          // relación belongsTo

        // ─────── actualiza métricas del animal ───────
// app/Http/Controllers/Api/MilkingSessionController.php
$animal->last_milking = $session->date        // ← instancia Carbon
    ->copy()                                  // evita mutar $session->date
    ->setTimeFromTimeString($session->end_time); // «HH:MM»


        // suma la producción
        $animal->total_production += $session->milk_yield;

        // recálculo de average_daily (promedio últimos 30 días)
        $avg = $animal->milkingSessions()         // relación hasMany
                      ->whereDate('date', '>=', now()->subDays(30))
                      ->avg('milk_yield');

        $animal->average_daily = $avg ?? 0;
        $animal->save();

        return $session->fresh('animal');         // devuelve con datos actualizados
    });

    return MilkingSessionResource::make($session)
           ->response()
           ->setStatusCode(201);
}

    /** GET /api/milking-sessions/{id} */
    public function show(MilkingSession $milkingSession)
    {
        return MilkingSessionResource::make(
            $milkingSession->load('animal')
        );
    }

    /** PUT/PATCH /api/milking-sessions/{id} */
    public function update(StoreMilkingSessionRequest $request, MilkingSession $milkingSession)
    {
        $milkingSession->update($request->validated());

        return MilkingSessionResource::make(
            $milkingSession->load('animal')
        );
    }

    /** DELETE /api/milking-sessions/{id} */
    public function destroy(MilkingSession $milkingSession)
    {
        $milkingSession->delete();

        return response()->noContent();
    }
}
